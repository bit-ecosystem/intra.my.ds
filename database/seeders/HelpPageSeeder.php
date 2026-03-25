<?php

namespace Database\Seeders;

use Bites\Shared\Models\HelpPage;
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
        "Bites\Core\Organization\Models\Company",
        "Bites\Core\Organization\Models\OrgUnit",
        "Bites\Core\Organization\Models\OrgRole",
        "Bites\Shared\Models\HelpPage",
        "App\Models\Menu",
        "Bites\Hrm\Models\Application",
        "Bites\Hrm\Models\ApplicationStatusHistory",
        "Bites\Hrm\Models\Interview",
        "Bites\Hrm\Models\JobVacancy",
        "Bites\Core\Organization\Models\JobPosition",
        "Bites\Hrm\Models\WorkforcePlan",
        "Bites\Hrm\Models\Offer",
        "Bites\Hrm\Models\Screening",
        "Bites\Hrm\Models\Skill",
        "Bites\Hrm\Models\Staff",
        "Bites\Hrm\Models\JobDescriptionTemplate",
        "App\Models\Workflow\Turtle",
        "App\Models\PersonAttribute",
        "Bites\Kbm\Dms\Models\Document",
        "Bites\Kbm\Dms\Models\Vector",
        "App\Models\Qas\Methodology",
        "App\Models\Qas\RunInitiative",
        "Bites\Kbm\Lms\Models\Course",
        "Bites\Kbm\Lms\Models\Module",
        "Bites\Kbm\Lms\Models\Quiz",
        "Bites\Kbm\Lms\Models\QuizAttempt",
        "Bites\Kbm\Lms\Models\Certificate",
        "Bites\Kbm\Lms\Models\Material",
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
