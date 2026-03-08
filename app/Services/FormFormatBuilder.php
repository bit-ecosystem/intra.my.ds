<?php

declare(strict_types=1);

namespace App\Services;

use Closure;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class FormFormatBuilder
{
    /**
     * Build Filament components from a JSON-like schema.
     *
     * @param  array  $schema  e.g., ['type'=>'object','properties'=>[...],'required'=>['x','y'], 'x-sections'=>[...] ]
     * @param  string  $prefix  dot-notation for nested contexts, if any.
     * @return array<int, \Filament\Forms\Components\Component>
     */
    public function build(array $schema, string $prefix = ''): array
    {
        $required = $schema['required'] ?? [];
        $sectionsLayout = $schema['x-sections'] ?? [];

        // First pass: build components and group by section
        $grouped = [];        // [sectionName|null => [components...]]
        $hiddenFields = [];   // always appended outside sections (not visible)
        $sectionOrder = [];   // preserve first-seen order of sections

        foreach ($schema['properties'] ?? [] as $name => $props) {
            $key = $this->makeKey($prefix, $name);
            $label = $props['title'] ?? $this->humanize($name);
            $isRequired = in_array($name, $required, true);
            $section = $props['x-section'] ?? null;

            // Hidden first
            if (! empty($props['x-hidden'])) {
                $hiddenFields[] = Hidden::make($key);

                continue;
            }

            $component = $this->buildFieldComponent($key, $label, $isRequired, $props);

            // Column span (respected when inside Grid)
            if (! empty($props['x-colSpan']) && method_exists($component, 'columnSpan')) {
                $component->columnSpan($props['x-colSpan']);
            }

            // Track section order
            if ($section !== null && ! array_key_exists($section, $sectionOrder)) {
                $sectionOrder[$section] = count($sectionOrder);
            }

            $groupKey = $section ?? '__root__';
            $grouped[$groupKey] ??= [];
            $grouped[$groupKey][] = $component;
        }

        // Second pass: assemble sections (with optional grid)
        $components = [];

        // Sections in order of first appearance
        foreach (array_keys($sectionOrder) as $sectionName) {
            $fields = $grouped[$sectionName] ?? [];
            $sectionMeta = $sectionsLayout[$sectionName] ?? [];

            $schemaWithinSection = $this->wrapWithGridIfNeeded($fields, $sectionMeta);

            $components[] = Section::make($sectionName)
                ->schema($schemaWithinSection);
        }

        // Root-level (no section)
        if (! empty($grouped['__root__'])) {
            foreach ($grouped['__root__'] as $field) {
                $components[] = $field;
            }
        }

        // Append hidden fields last (order doesn’t matter visually)
        foreach ($hiddenFields as $hiddenField) {
            $components[] = $hiddenField;
        }

        return $components;
    }

    /**
     * Compute default values for the form from schema.
     * Use in Create pages: return this from getDefaultFormData().
     */
    public function getDefaults(array $schema, string $prefix = ''): array
    {
        $defaults = [];

        foreach ($schema['properties'] ?? [] as $name => $props) {
            $key = $this->makeKey($prefix, $name);

            // Evaluate default for this property
            $value = $this->evaluateDefault($props);

            if ($value !== null) {
                $defaults[$key] = $value;
            }
        }

        return $defaults;
    }

    // ------------------------------
    // Field builders
    // ------------------------------

    protected function buildFieldComponent(string $key, string $label, bool $isRequired, array $props)
    {
        $component = $this->makeBaseComponent($key, $label, $props);

        // Placeholder
        $placeholder = $props['ui:placeholder'] ?? $props['placeholder'] ?? null;
        if (! empty($placeholder) && method_exists($component, 'placeholder')) {
            $component->placeholder($placeholder);
        }

        // Defaults (component-level default—not the same as Create page defaults,
        // but harmless if both are present)
        if (array_key_exists('default', $props) && method_exists($component, 'default')) {
            $component->default($props['default']);
        }

        // Disabled / Readonly
        if (! empty($props['ui:disabled'])) {
            $component->disabled();
        } elseif (! empty($props['ui:readonly'])) {
            if (method_exists($component, 'readOnly')) {
                $component->readOnly();
            } else {
                $component->disabled();
            }
        }

        // Required (static)
        if ($isRequired) {
            $component->required();
        }

        // Conditional visibility
        if (! empty($props['x-visibleIf'])) {
            $cond = $props['x-visibleIf'];
            $component->visible($this->makeCondition($cond));
        }

        // Conditional required
        if (! empty($props['x-requiredIf'])) {
            $cond = $props['x-requiredIf'];
            $component->required($this->makeCondition($cond));
        }

        return $component;
    }

    protected function makeBaseComponent(string $key, string $label, array $props): \Filament\Forms\Components\Radio|\Filament\Forms\Components\Select|\Filament\Forms\Components\FileUpload|\Filament\Forms\Components\DatePicker|\Filament\Forms\Components\DateTimePicker|\Filament\Forms\Components\TimePicker|\Filament\Forms\Components\Textarea|\Filament\Forms\Components\TextInput
    {
        $format = $props['format'] ?? null;
        $widget = $props['ui:widget'] ?? null;

        // Radio widget
        if ($widget === 'radio') {
            $options = $this->buildOptions($props);
            $radio = Radio::make($key)->label($label)->options($options);

            if (! empty($props['ui:inline'])) {
                $radio->inline();
            }

            return $radio;
        }

        // Select (oneOf / enum / relationship / optionsSource / x-options)
        if (! empty($props['oneOf']) || ! empty($props['enum']) || ! empty($props['x-relationship']) || ! empty($props['x-options']) || ! empty($props['x-optionsSource'])) {
            $select = Select::make($key)->label($label);

            // Relationship takes precedence over options
            if (! empty($props['x-relationship']) && is_array($props['x-relationship'])) {
                $rel = $props['x-relationship'];
                $relName = $rel['name'] ?? null;
                $relLabel = $rel['label'] ?? 'name';
                if ($relName) {
                    $select->relationship($relName, $relLabel);
                }
            } else {
                $options = $this->buildOptions($props);
                $select->options($options);
            }

            if (! empty($props['ui:multiple'])) {
                $select->multiple();
            }

            if (! empty($props['ui:searchable'])) {
                $select->searchable();
            }

            if (! empty($props['ui:preload'])) {
                $select->preload();
            }

            return $select;
        }

        // File / Image
        if ($format === 'file' || $format === 'image') {
            $file = FileUpload::make($key)->label($label);

            if ($format === 'image') {
                $file->image()->imageEditor();
            }

            if (! empty($props['x-storage']) && is_array($props['x-storage'])) {
                $disk = $props['x-storage']['disk'] ?? null;
                $dir = $props['x-storage']['directory'] ?? null;
                if ($disk) {
                    $file->disk($disk);
                }

                if ($dir) {
                    $file->directory($dir);
                }
            }

            return $file;
        }

        // Date / DateTime / Time
        if ($format === 'date') {
            return DatePicker::make($key)->label($label);
        }

        if ($format === 'datetime') {
            return DateTimePicker::make($key)->label($label);
        }

        if ($format === 'time') {
            return TimePicker::make($key)->label($label);
        }

        // Textarea
        if ($format === 'textarea' || $widget === 'textarea') {
            return Textarea::make($key)->label($label);
        }

        // Specialized TextInput widgets
        if (in_array($widget, ['password', 'email', 'url', 'tel', 'number'], true)) {
            $input = TextInput::make($key)->label($label);
            switch ($widget) {
                case 'password':
                    $input->password();
                    break;
                case 'email':
                    $input->email();
                    break;
                case 'url':
                    $input->url();
                    break;
                case 'tel':
                    $input->tel();
                    break;
                case 'number':
                    $input->numeric();
                    break;
            }

            return $input;
        }

        // Default: TextInput
        return TextInput::make($key)->label($label);
    }

    /**
     * Build options array for Select/Radio.
     * Supports:
     * - oneOf: [{const, title, x-group?}]
     * - enum (+ x-enumNames)
     * - x-options: map or array of objects [{value,label,group?}]
     * - x-optionsSource: "FQCN::method"
     * Returns flat map or grouped map: [ "Group" => [value=>label] ].
     */
    protected function buildOptions(array $props): array
    {
        // 1) Explicit x-options
        if (! empty($props['x-options'])) {
            $src = $props['x-options'];
            if (is_array($src)) {
                // Map
                if ($this->isAssoc($src) && $this->isValueLabelMap($src)) {
                    return $src;
                }

                // Array of objects
                $grouped = [];
                foreach ($src as $row) {
                    if (! is_array($row)) {
                        continue;
                    }

                    $value = $row['value'] ?? null;
                    $label = $row['label'] ?? (string) $value;
                    $group = $row['group'] ?? null;
                    if ($value === null) {
                        continue;
                    }

                    if ($group) {
                        $grouped[$group] ??= [];
                        $grouped[$group][$value] = $label;
                    } else {
                        $grouped[$value] = $label; // flat fallback
                    }
                }

                // If any group used, keep grouped; else flatten
                return $this->hasStringKeys($grouped) ? $grouped : $this->flattenIfGrouped($grouped);
            }
        }

        // 2) oneOf with (optional) x-group
        if (! empty($props['oneOf']) && is_array($props['oneOf'])) {
            $grouped = [];
            $flat = [];
            foreach ($props['oneOf'] as $item) {
                $value = $item['const'] ?? null;
                if ($value === null) {
                    continue;
                }

                $label = $item['title'] ?? (string) $value;
                $group = $item['x-group'] ?? null;
                if ($group) {
                    $grouped[$group] ??= [];
                    $grouped[$group][$value] = $label;
                } else {
                    $flat[$value] = $label;
                }
            }

            // Merge flat into grouped if groups exist
            if ($grouped !== []) {
                if ($flat !== []) {
                    $grouped['Other'] = ($grouped['Other'] ?? []) + $flat;
                }

                return $grouped;
            }

            return $flat;
        }

        // 3) enum + x-enumNames
        if (! empty($props['enum']) && is_array($props['enum'])) {
            $values = $props['enum'];
            $names = (array) ($props['x-enumNames'] ?? []);
            if ($names !== [] && count($names) === count($values)) {
                return array_combine($values, $names);
            }

            return array_combine($values, $values);
        }

        // 4) x-optionsSource provider
        if (! empty($props['x-optionsSource']) && is_string($props['x-optionsSource'])) {
            return $this->resolveOptionsSource($props['x-optionsSource']);
        }

        return [];
    }

    /**
     * Convert "App\\Class::method" into ['value' => 'label'] (or grouped).
     */
    protected function resolveOptionsSource(string $fqMethod): array
    {
        if (strpos($fqMethod, '::') !== false) {
            [$class, $method] = explode('::', $fqMethod, 2);
            if (class_exists($class) && method_exists($class, $method)) {
                $data = \call_user_func([$class, $method]);

                if ($data instanceof Collection) {
                    $data = $data->toArray();
                }

                // Normalize arrays like: [ ['id'=>1,'label'=>'A'], ... ]
                if (is_array($data)) {
                    if ($data !== [] && isset($data[0]) && is_array($data[0])) {
                        $first = $data[0];
                        // id/label pattern
                        if (array_key_exists('id', $first) && array_key_exists('label', $first)) {
                            return collect($data)->mapWithKeys(fn ($row): array => [$row['id'] => $row['label']])->all();
                        }

                        // value/label (+ group) pattern
                        if (array_key_exists('value', $first) && array_key_exists('label', $first)) {
                            $grouped = [];
                            foreach ($data as $row) {
                                $value = $row['value'];
                                $label = $row['label'];
                                $group = $row['group'] ?? null;
                                if ($group) {
                                    $grouped[$group] ??= [];
                                    $grouped[$group][$value] = $label;
                                } else {
                                    $grouped[$value] = $label;
                                }
                            }

                            return $this->hasStringKeys($grouped) ? $grouped : $this->flattenIfGrouped($grouped);
                        }
                    }

                    // Already a map
                    if ($this->isAssoc($data)) {
                        return $data;
                    }
                }
            }
        }

        return [];
    }

    // ------------------------------
    // Defaults
    // ------------------------------

    /**
     * Decide the default value for a property.
     * Priority: x-default (tokenized) > default (static) > x-defaultSource (callable)
     */
    protected function evaluateDefault(array $props)
    {
        // 1) x-default (token string or scalar)
        if (array_key_exists('x-default', $props)) {
            $val = $props['x-default'];

            // token handling for strings like "now:date" or "user.staff.id"
            if (is_string($val)) {
                $maybe = $this->evaluateDefaultToken($val, $props);
                if ($maybe !== null) {
                    return $maybe;
                }
            }

            // scalar fallback as-is
            if (is_scalar($val)) {
                return $val;
            }
        }

        // 2) default (static scalar)
        if (array_key_exists('default', $props) && is_scalar($props['default'])) {
            return $props['default'];
        }

        // 3) x-defaultSource ("FQCN::method") + optional x-defaultArgs
        if (! empty($props['x-defaultSource']) && is_string($props['x-defaultSource'])) {
            $args = [];
            if (! empty($props['x-defaultArgs']) && is_array($props['x-defaultArgs'])) {
                $args = $props['x-defaultArgs'];
            }

            $fromProvider = $this->invokeProvider($props['x-defaultSource'], $args);

            // Attempt to coerce type-friendly value for formats
            return $this->coerceFormatValue($fromProvider, $props);
        }

        return null;
    }

    /**
     * Handle token strings for x-default (now:user etc).
     *
     * Supported tokens:
     *  - now:date      -> Y-m-d
     *  - now:datetime  -> Y-m-d H:i:s
     *  - now:time      -> H:i:s
     *  - user.*        -> Auth::user() chain (e.g., user.staff.id)
     *  - literal strings return as-is
     */
    protected function evaluateDefaultToken(string $token, array $props)
    {
        $token = trim($token);

        // now:* shortcuts
        if (str_starts_with($token, 'now:')) {
            $part = substr($token, 4);
            $now = now();

            return match ($part) {
                'date' => $now->toDateString(),     // YYYY-MM-DD
                'datetime' => $now->format('Y-m-d H:i:s'),
                'time' => $now->format('H:i:s'),
                default => null,
            };
        }

        // user.* path
        if (str_starts_with($token, 'user')) {
            $user = Auth::user();
            if (! $user) {
                return null;
            }

            // Resolve nested path after "user"
            $path = explode('.', $token);
            // first is "user", skip it
            array_shift($path);

            $current = $user;
            foreach ($path as $segment) {
                if ($current === null) {
                    return null;
                }

                // object property or relation
                if (is_object($current)) {
                    if (isset($current->{$segment})) {
                        $current = $current->{$segment};
                    } elseif (method_exists($current, $segment)) {
                        $current = $current->{$segment}();
                        if (method_exists($current, 'getResults')) {
                            $current = $current->getResults();
                        }
                    } else {
                        return null;
                    }
                } elseif (is_array($current)) {
                    $current = $current[$segment] ?? null;
                } else {
                    return null;
                }
            }

            return $this->coerceFormatValue($current, $props);
        }

        // literal fallback (accept raw strings)
        return $token;
    }

    /**
     * Invoke static providers such as "App\\Class::method".
     */
    protected function invokeProvider(string $fqMethod, array $args = [])
    {
        if (strpos($fqMethod, '::') === false) {
            return null;
        }

        [$class, $method] = explode('::', $fqMethod, 2);
        if (! class_exists($class) || ! method_exists($class, $method)) {
            return null;
        }

        return \call_user_func_array([$class, $method], $args);
    }

    /**
     * Coerce provider/user values to match field format expectations.
     * e.g., DatePicker needs Y-m-d, DateTimePicker Y-m-d H:i:s
     */
    protected function coerceFormatValue($value, array $props)
    {
        $format = $props['format'] ?? null;

        if ($value instanceof \Carbon\CarbonInterface) {
            return match ($format) {
                'date' => $value->toDateString(),
                'datetime' => $value->format('Y-m-d H:i:s'),
                'time' => $value->format('H:i:s'),
                default => (string) $value,
            };
        }

        // If the field expects date/time, and we got a DateTime-string-ish, try to coerce
        if (is_string($value)) {
            if ($format === 'date') {
                try {
                    $dt = \Carbon\Carbon::parse($value);

                    return $dt->toDateString();
                } catch (\Throwable $e) {
                }
            }

            if ($format === 'datetime') {
                try {
                    $dt = \Carbon\Carbon::parse($value);

                    return $dt->format('Y-m-d H:i:s');
                } catch (\Throwable $e) {
                }
            }

            if ($format === 'time') {
                try {
                    $dt = \Carbon\Carbon::parse($value);

                    return $dt->format('H:i:s');
                } catch (\Throwable $e) {
                }
            }
        }

        // For relationship Select, default should be the foreign key id (scalar)
        // If we get a model, try id
        if (is_object($value) && method_exists($value, 'getAttribute')) {
            $id = $value->getAttribute('id');
            if ($id !== null) {
                return $id;
            }
        }

        // scalar or array as-is
        return $value;
    }

    // ------------------------------
    // Conditions
    // ------------------------------

    /**
     * Build a Filament Get-closure evaluator from condition spec.
     *
     * Accepts:
     *  - Simple: ['field' => 'value']
     *  - Advanced:
     *      ['all' => [ ['field'=>'x','eq'=>1], ['field'=>'y','in'=>[2,3]] ]]
     *      ['any' => [ ... ]]
     */
    protected function makeCondition(array $cond): Closure
    {
        // Simple shorthand: { "field": "value" } or { process: 'other' }
        if (isset($cond['field']) || $this->isSimpleCondition($cond)) {
            return function (callable $get) use ($cond): bool {
                if (isset($cond['field'])) {
                    return $this->evaluateRule($get, $cond);
                }

                // { field: value } style
                [$f, $v] = $this->firstKV($cond);

                return $get($f) === $v;
            };
        }

        // Logic groups: all / any
        $all = $cond['all'] ?? null;
        $any = $cond['any'] ?? null;

        if (is_array($all)) {
            return function (callable $get) use ($all): bool {
                foreach ($all as $rule) {
                    if (! $this->evaluateRule($get, $rule)) {
                        return false;
                    }
                }

                return true;
            };
        }

        if (is_array($any)) {
            return function (callable $get) use ($any): bool {
                foreach ($any as $rule) {
                    if ($this->evaluateRule($get, $rule)) {
                        return true;
                    }
                }

                return false;
            };
        }

        // Fallback (unexpected): always true
        return fn (): true => true;
    }

    protected function evaluateRule(callable $get, array $rule): bool
    {
        $field = $rule['field'] ?? null;
        if ($field === null) {
            return true;
        }

        $value = $get($field);

        // Operators
        if (array_key_exists('eq', $rule)) {
            return $value === $rule['eq'];
        }

        if (array_key_exists('ne', $rule)) {
            return $value !== $rule['ne'];
        }

        if (array_key_exists('in', $rule)) {
            return in_array($value, (array) $rule['in'], true);
        }

        if (array_key_exists('notIn', $rule)) {
            return ! in_array($value, (array) $rule['notIn'], true);
        }

        if (array_key_exists('truthy', $rule)) {
            return (bool) $value;
        }

        if (array_key_exists('falsy', $rule)) {
            return (bool) $value === false;
        }

        if (array_key_exists('gt', $rule)) {
            return $value > $rule['gt'];
        }

        if (array_key_exists('gte', $rule)) {
            return $value >= $rule['gte'];
        }

        if (array_key_exists('lt', $rule)) {
            return $value < $rule['lt'];
        }

        if (array_key_exists('lte', $rule)) {
            return $value <= $rule['lte'];
        }

        // Default: strict eq if 'value' present
        if (array_key_exists('value', $rule)) {
            return $value === $rule['value'];
        }

        return true;
    }

    // ------------------------------
    // Helpers
    // ------------------------------

    protected function makeKey(string $prefix, string $name): string
    {
        return $prefix !== '' && $prefix !== '0' ? sprintf('%s.%s', $prefix, $name) : $name;
    }

    protected function humanize(string $name): string
    {
        return ucfirst(str_replace('_', ' ', $name));
    }

    protected function firstKV(array $arr): array
    {
        foreach ($arr as $k => $v) {
            return [$k, $v];
        }

        return [null, null];
    }

    protected function isAssoc(array $arr): bool
    {
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    protected function hasStringKeys(array $arr): bool
    {
        foreach (array_keys($arr) as $k) {
            if (is_string($k)) {
                return true;
            }
        }

        return false;
    }

    protected function isValueLabelMap(array $map): bool
    {
        foreach ($map as $v) {
            if (is_string($v) || is_int($v)) {
                return true;
            }
        }

        return false;
    }

    protected function flattenIfGrouped(array $grouped): array
    {
        if (! $this->hasStringKeys($grouped)) {
            $flat = [];
            foreach ($grouped as $maybe) {
                if (is_array($maybe)) {
                    foreach ($maybe as $k => $v) {
                        $flat[$k] = $v;
                    }
                }
            }

            return $flat;
        }

        return $grouped;
    }

    protected function isSimpleCondition(array $cond): bool
    {
        return count($cond) === 1 && ! isset($cond['all'], $cond['any']);
    }

    protected function wrapWithGridIfNeeded(array $fields, array $sectionMeta): array
    {
        $gridSpec = $sectionMeta['grid'] ?? null;
        if (! $gridSpec || ! is_array($gridSpec)) {
            return $fields;
        }

        $grid = Grid::make($gridSpec);

        return [$grid->schema($fields)];
    }
}
