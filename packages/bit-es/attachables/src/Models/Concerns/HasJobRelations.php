<?php
 
namespace App\Models\Traits;
 
use Bites\Attachables\Models\JobPosition;
 
trait HasJobRelations
{
/**
* For Staff and User models:
* A model can have many job positions assigned.
*/
public function jobPositions()
{
return $this->morphMany(JobPosition::class, 'assignable');
}
 
/**
* For JobPosition model:
* A job position belongs to either Staff or User.
*/
public function assignable()
{
return $this->morphTo();
}
}