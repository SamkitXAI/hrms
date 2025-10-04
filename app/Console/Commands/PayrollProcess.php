<?php

namespace App\Console\Commands;

use App\Models\Employee;
use App\Models\PayrollCycle;
use App\Models\PayrollItem;
use Carbon\CarbonPeriod;
use Illuminate\Console\Command;

class PayrollProcess extends Command
{
    protected $signature = 'payroll:process {cycle_id}';
    protected $description = 'Process payroll for a cycle';

    public function handle(): int
    {
        $cycle = PayrollCycle::findOrFail($this->argument('cycle_id'));
        $period = CarbonPeriod::create($cycle->start_date, $cycle->end_date);

        $employees = Employee::where('company_id', $cycle->company_id)->get();

        foreach ($employees as $e) {
            // naive example: prorate by presence; replace by your policy soon
            $days = iterator_count($period);
            $present = $e->attendanceLogs()
                ->whereBetween('work_date', [$cycle->start_date, $cycle->end_date])
                ->whereNotNull('check_in')
                ->count();

            $basic = ($e->ctc ?? 0) / 12;
            $net   = $days ? round($basic * ($present / $days), 2) : 0;

            PayrollItem::updateOrCreate(
                ['payroll_cycle_id' => $cycle->id, 'employee_id' => $e->id],
                ['basic' => $basic, 'hra' => 0, 'allowances' => 0, 'deductions' => 0, 'net_pay' => $net]
            );
        }

        $cycle->update(['status' => 'processed']);
        $this->info('Payroll processed.');
        return self::SUCCESS;
    }
}
