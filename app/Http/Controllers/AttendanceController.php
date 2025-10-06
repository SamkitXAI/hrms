<?php

namespace App\Http\Controllers;

use App\Models\AttendanceLog;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request) {
        $date = $request->date ? Carbon::parse($request->date) : now();
        $logs = AttendanceLog::with('employee')->whereDate('work_date', $date->toDateString())->paginate(50);
        return view('attendance.index', compact('logs','date'));
    }

    public function checkIn(Employee $employee) {
        $today = now()->toDateString();
        $log = AttendanceLog::firstOrCreate(
            ['employee_id' => $employee->id, 'company_id' => $employee->company_id, 'work_date' => $today],
            ['location_id' => $employee->location_id]
        );

        if (!$log->check_in) {
            $log->check_in = now();
            // TODO: compute late_minutes from assigned shift
            $log->save();
        }

        return back()->with('success', 'Checked in');
    }

    public function checkOut(Employee $employee) {
        $today = now()->toDateString();
        $log = AttendanceLog::where('employee_id', $employee->id)->whereDate('work_date', $today)->first();

        if ($log && !$log->check_out) {
            $log->check_out = now();
            // TODO: compute ot_minutes if beyond shift end
            $log->save();
        }

        return back()->with('success', 'Checked out');
    }

    public function my(Request $request)
{
    $user = $request->user();
    $employee = $user->employee; // if you store 1-to-1 mapping

    abort_unless($employee, 404);

    $month = $request->input('month', now()->format('Y-m'));
    [$y,$m] = explode('-', $month);
    $from = \Carbon\Carbon::createFromDate($y,$m,1)->startOfMonth();
    $to   = (clone $from)->endOfMonth();

    $logs = $employee->attendanceLogs()
        ->whereBetween('work_date', [$from->toDateString(), $to->toDateString()])
        ->orderBy('work_date','asc')
        ->get()
        ->keyBy(fn($l) => $l->work_date->format('Y-m-d'));

    return view('attendance.my', compact('employee','month','from','to','logs'));
}

}
