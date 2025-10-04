<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayrollItem extends Model
{
    protected $fillable = [
        'payroll_cycle_id',
        'employee_id',
        'basic',
        'hra',
        'allowances',
        'deductions',
        'net_pay',
        'breakup',
    ];

    protected $casts = [
        'basic' => 'decimal:2',
        'hra' => 'decimal:2',
        'allowances' => 'decimal:2',
        'deductions' => 'decimal:2',
        'net_pay' => 'decimal:2',
        'breakup' => 'array',
    ];

    public function cycle()
    {
        return $this->belongsTo(PayrollCycle::class, 'payroll_cycle_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function payslip()
    {
        return $this->hasOne(Payslip::class);
    }
}
