<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AttendanceLog;
use App\Models\Employee;
use Carbon\Carbon;

class ComputeDailyAttendance extends Command
{
    protected $signature = 'attendance:compute {date?}';
    protected $description = 'Compute daily status/late/OT for all employees';

    public function handle()
    {
        $date = $this->argument('date') ? Carbon::parse($this->argument('date'))->toDateString() : now()->toDateString();
        $logs = AttendanceLog::with('employee','employee.shift')
            ->whereDate('work_date', $date)->get();

        foreach ($logs as $log) {
            // naïve example; replace with your shift timings and grace rules
            $shiftStart = Carbon::parse($log->work_date.' 09:30:00');
            $shiftEnd   = Carbon::parse($log->work_date.' 18:00:00');

            if ($log->check_in) {
                $log->late_minutes = max(0, Carbon::parse($log->check_in)->diffInMinutes($shiftStart, false) * -1);
            }
            if ($log->check_out) {
                $worked = Carbon::parse($log->check_in ?? $shiftStart)->diffInMinutes(Carbon::parse($log->check_out));
                $log->ot_minutes = max(0, Carbon::parse($log->check_out)->gt($shiftEnd) ? Carbon::parse($log->check_out)->diffInMinutes($shiftEnd) : 0);
            }
            $log->save();
        }

        $this->info("Attendance computed for {$date}");
        return Command::SUCCESS;
    }
}
