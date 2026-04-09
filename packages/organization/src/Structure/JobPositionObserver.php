<?php

declare(strict_types=1);

namespace Bites\Organization\Structure;

use Bites\Employment\Models\JobDescriptionTemplate;
use Bites\Employment\Models\WorkforcePlan;

class JobPositionObserver
{
    public function created(JobPosition $jobPosition): void
    {
        $this->createWorkforcePlan($jobPosition);
    }

    /**
     * Create WorkforcePlan if none exists for this org_unit_id.
     */
    protected function createWorkforcePlan(JobPosition $jobPosition): void
    {
        if ($jobPosition->org_unit_id && $jobPosition->title) {
            // Find matching JobDescriptionTemplate by title
            $template = JobDescriptionTemplate::where('title', $jobPosition->title)->first();

            $plan = WorkforcePlan::firstOrCreate(
                [
                    'org_unit_id' => $jobPosition->org_unit_id,
                    'title' => $jobPosition->title, // Include title in the lookup
                ],
                [
                    'job_title_id' => $template?->id,
                    'required_quantity' => 1,
                ]
            );

            if (! $plan->wasRecentlyCreated) {
                $plan->increment('required_quantity');
            }
        }
    }
}
