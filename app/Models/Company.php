<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = ['name','code','employee_cap','timezone','is_active'];

    public function locations(){ return $this->hasMany(Location::class); }
    public function departments(){ return $this->hasMany(Department::class); }
    public function employees(){ return $this->hasMany(Employee::class); }
    public function leaveTypes(){ return $this->hasMany(LeaveType::class); }
}
