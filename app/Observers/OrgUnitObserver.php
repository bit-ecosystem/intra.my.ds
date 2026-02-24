<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Core\OrgUnit;
use App\Models\RoleMapper;

class OrgUnitObserver
{
    protected static $defaults = [
        ['role_name' => 'ou_member',    'label' => 'Members to this OU.'],
        ['role_name' => 'ou_owner',    'label' => 'Head of this OU.'],
        ['role_name' => 'ou_service_builder',    'label' => 'Role for designs, builds, and transitions service offerings and workflows.'],
        ['role_name' => 'ou_trainer',    'label' => 'Role for builds of learning content and assessments in the Knowlege & Learning.'],
        ['role_name' => 'ou_people_planner',    'label' => 'Role for planning of manpower resource.'],
        // ['role_name' => 'ou_people_approver',   'label' => 'Role for approving manpower resource, hiring'],
        // ['role_name' => 'ou_document_editor',   'label' => 'Role for drafting and editing OU documents'],
        // ['role_name' => 'ou_document_controller', 'label' => 'Role for maintaining the validity of OU documents'],
        // ['role_name' => 'ou_document_publisher', 'label' => 'Role for approving and publishing OU documents into knowledge base'],
        // ['role_name' => 'ou_process_designer',  'label' => 'Role for drafting OU process of catalog (services & items)'],
        // ['role_name' => 'ou_process_publisher', 'label' => 'Role for approving and publishing OU processes into catalog'],
        // ['role_name' => 'ou_catalog_approver',  'label' => 'Role for approver of services and items provided by OU'],
        // ['role_name' => 'ou_catalog_planner',   'label' => 'Role for planning of services and items offered by OU'],
        // ['role_name' => 'ou_item_controller',   'label' => 'Role for maintaining of services and items provided by OU'],
        // ['role_name' => 'ou_item_approver',     'label' => 'Role for approver for refilling of items provided by OU'],
        // ['role_name' => 'ou_service_provider',  'label' => 'Role for executing the services and items provided by OU'],
    ];

    /**
     * Handle the OrgUnit "created" event.
     */
    public function created(OrgUnit $orgUnit): void
    {
        foreach (self::$defaults as $default) {
            RoleMapper::firstOrCreate(
                ['role_name' => $default['role_name'], 'org_unit_id' => $orgUnit->id],
                ['scope' => 'ou', 'enabled' => true, 'label' => $default['label'], 'category' => 'implied']
            );
        }
    }

    /**
     * Handle the OrgUnit "updated" event.
     */
    public function updated(OrgUnit $orgUnit): void
    {
        // $this->created($orgUnit);
    }

    /**
     * Handle the OrgUnit "deleted" event.
     */
    public function deleted(OrgUnit $orgUnit): void
    {
        //
    }

    /**
     * Handle the OrgUnit "restored" event.
     */
    public function restored(OrgUnit $orgUnit): void
    {
        //
    }

    /**
     * Handle the OrgUnit "force deleted" event.
     */
    public function forceDeleted(OrgUnit $orgUnit): void
    {
        RoleMapper::where('org_unit_id', $orgUnit->id)->delete();
    }
}
