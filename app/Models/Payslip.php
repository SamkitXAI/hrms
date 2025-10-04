<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payslip extends Model
{
    protected $fillable = [
        'payroll_item_id',
        'number',
        'file_path',
    ];

    public function payrollItem()
    {
        return $this->belongsTo(PayrollItem::class);
    }

    public function employee()
    {
        return $this->hasOneThrough(Employee::class, PayrollItem::class, 'id', 'id', 'payroll_item_id', 'employee_id');
    }
}
