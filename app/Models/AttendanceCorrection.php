<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceCorrection extends Model
{
    protected $fillable = [
        'company_id','employee_id','work_date','type','requested_time','reason',
        'status','decider_id','decided_at','meta'
    ];

    protected $casts = [
        'work_date' => 'date',
        'requested_time' => 'datetime',
        'meta' => 'array',
    ];

    public function employee(){ return $this->belongsTo(Employee::class); }
    public function decider(){ return $this->belongsTo(User::class, 'decider_id'); }
}
