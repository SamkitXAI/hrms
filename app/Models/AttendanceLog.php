<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceLog extends Model
{
    protected $fillable = [
        'company_id','employee_id','location_id','work_date',
        'check_in','check_out','late_minutes','ot_minutes','source','device_ref','raw'
    ];

    protected $casts = [
        'work_date' => 'date',
        'check_in'  => 'datetime',
        'check_out' => 'datetime',
        'raw'       => 'array',
    ];

    public function employee(){ return $this->belongsTo(Employee::class); }
    public function company(){ return $this->belongsTo(Company::class); }
    public function location(){ return $this->belongsTo(Location::class); }
}
