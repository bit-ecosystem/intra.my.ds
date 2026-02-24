<?php

namespace Database\Seeders;

use App\Models\Core\HelpPage;
use Filament\Facades\Filament;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class HelpPageSeeder extends Seeder
{
    /**
     * List of target Eloquent model classes you provided.
     */
    protected array $targetModels = [
        "App\Models\User",
        "App\Models\Core\Company",
        "App\Models\Core\OrgUnit",
        "App\Models\Core\OrgRole",
        "App\Models\Core\HelpPage",
        "App\Models\Menu",
        "App\Models\Hrm\Application",
        "App\Models\Hrm\ApplicationStatusHistory",
        "App\Models\Hrm\Interview",
        "App\Models\Hrm\JobVacancy",
        "App\Models\Hrm\JobPosition",
        "App\Models\Hrm\WorkforcePlan",
        "App\Models\Hrm\Offer",
        "App\Models\Hrm\Screening",
        "App\Models\Hrm\Skill",
        "App\Models\Hrm\Staff",
        "App\Models\Hrm\JobDescriptionTemplate",
        "App\Models\Workflow\Turtle",
        "App\Models\PersonAttribute",
        "App\Models\Dms\Document",
        "App\Models\Dms\Vector",
        "App\Models\Qas\Methodology",
        "App\Models\Qas\RunInitiative",
        "App\Models\Lms\Course",
        "App\Models\Lms\Module",
        "App\Models\Lms\Quiz",
        "App\Models\Lms\QuizAttempt",
        "App\Models\Lms\Certificate",
        "App\Models\Lms\Material",
    ];

    public function run(): void
    {
        // Build a quick set for faster lookup
        $targets = collect($this->targetModels)->flip();

        // Iterate all Filament panels and their resources
        foreach (Filament::getPanels() as $panel) {
            $panelId = $panel->getId();

            foreach ($panel->getResources() as $resourceClass) {
                // Ask the resource which Eloquent model it manages
                $modelClass = $resourceClass::getModel();

                // Only seed for models in your list
                if (! $targets->has($modelClass)) {
                    continue;
                }

                // Create a slug and title from model short name
                $modelShort = class_basename($modelClass); // e.g., 'User', 'Company', 'OrgUnit'
                $slug = Str::kebab($modelShort);           // 'user', 'company', 'org-unit'
                $title = 'Help: '.Str::headline($modelShort);

                $markdown = <<<MD
# {$title}

This help page explains how to use the **{$modelShort}** screens.

## Common Actions
- Use filters to narrow down results.
- Sort columns by clicking the header.
- Click a row to view or edit details.
- Use bulk actions for mass updates (if available).

## Tips
- Secure data and follow company SOPs.
- Refer to role-based permissions for access.

MD;

                // We’ll store page_class as '*' (wildcard) so it applies to all pages of this resource.
                HelpPage::updateOrCreate(
                    [
                        'panel_id' => $panelId,
                        'resource_class' => $resourceClass,
                        'page_class' => '*',     // wildcard coverage
                    ],
                    [
                        'slug' => $slug,
                        'title' => $title,
                        'content' => $markdown,
                    ]
                );
            }
        }
    }
}
