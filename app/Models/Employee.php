<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'company_id','user_id','department_id','location_id',
        'emp_code','first_name','last_name','email','phone',
        'doj','designation','ctc','meta'
    ];

    protected $casts = ['meta' => 'array', 'doj' => 'date'];

    public function company(){ return $this->belongsTo(Company::class); }
    public function department(){ return $this->belongsTo(Department::class); }
    public function location(){ return $this->belongsTo(Location::class); }
    public function user(){ return $this->belongsTo(User::class); }
    public function shifts(){ return $this->belongsToMany(Shift::class)->withPivot(['effective_from','effective_to'])->withTimestamps(); }
    public function attendanceLogs(){ return $this->hasMany(AttendanceLog::class); }
}
