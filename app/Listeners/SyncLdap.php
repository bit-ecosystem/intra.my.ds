<?php

namespace App\Listeners;

use Illuminate\Support\Str;
use LdapRecord\Laravel\Events\Import\Synchronized;
use LdapRecord\Models\Attributes\Guid;
use App\Models\Hrm\Staff;

class SyncLdap
{
    public function handle(Synchronized $event): void
    {
        $ldap = $event->object;
        $user = $event->eloquent;

        // Ensure canonical GUID:
        if ($bin = $ldap->getFirstAttribute('objectguid')) {
            $user->ldap_guid = (new Guid($bin))->getValue();
        }

        if (empty($user->app_authentication_secret)) {
            $user->app_authentication_secret = Str::random(32);
        }

        // Link staff by employee_id -> staff_number:
        if (!empty($user->employee_id)) {
            $staff = Staff::where('staff_number', $user->employee_id)->first();

            if ($staff) {
                // Set FK on Staff side because relationship expects staff.user_id
                if ($staff->user_id !== $user->id) {
                    $staff->user_id = $user->id;
                    $staff->save();
                }
            } else {
                // Optional: auto-create or log
                // $staff = Staff::create([...]);
            }
        }

        $user->save();
    }
}