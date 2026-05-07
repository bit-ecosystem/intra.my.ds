<?php

namespace Database\Seeders;

use Bites\Service\Models\HelpPage;
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
        "Bites\Organization\Structure\Company",
        "Bites\Organization\Structure\OrgUnit",
        "Bites\Organization\Structure\OrgRole",
        "Bites\Service\Models\HelpPage",
        "App\Models\Menu",
        "Bites\Employment\Models\Application",
        "Bites\Employment\Models\ApplicationStatusHistory",
        "Bites\Employment\Models\Interview",
        "Bites\Employment\Models\JobVacancy",
        "Bites\Organization\Structure\JobPosition",
        "Bites\Employment\Models\WorkforcePlan",
        "Bites\Employment\Models\Offer",
        "Bites\Employment\Models\Screening",
        "Bites\Employment\Models\Skill",
        "Bites\Employment\Models\Staff",
        "Bites\Employment\Models\JobDescriptionTemplate",
        "App\Models\Workflow\Turtle",
        "App\Models\PersonAttribute",
        "Bites\Knowledge\Library\Document",
        "Bites\Knowledge\Library\Vector",
        "App\Models\Qas\Methodology",
        "App\Models\Qas\RunInitiative",
        "Bites\Business\Lms\Entities\Course",
        "Bites\Business\Lms\Entities\Module",
        "Bites\Business\Lms\Entities\Quiz",
        "Bites\Business\Lms\Entities\QuizAttempt",
        "Bites\Business\Lms\Entities\Certificate",
        "Bites\Business\Lms\Entities\Material",
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
