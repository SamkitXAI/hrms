<?php

namespace App\Http\Controllers;

use App\Models\AttendanceCorrection;
use App\Models\AttendanceLog;
use Illuminate\Http\Request;

class AttendanceCorrectionController extends Controller
{
    public function index(Request $request)
    {
        // TODO: gate to HR/Manager only
        $corrections = AttendanceCorrection::with(['employee'])
            ->where('company_id', $request->user()->company_id)
            ->latest()->paginate(25);

        return view('attendance.corrections.index', compact('corrections'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'work_date' => ['required','date'],
            'type' => ['required','in:check_in,check_out,full_day,half_day'],
            'requested_time' => ['nullable','date'],
            'reason' => ['nullable','string','max:500'],
        ]);

        $emp = $request->user()->employee;
        abort_unless($emp, 404);

        AttendanceCorrection::create([
            'company_id'     => $emp->company_id,
            'employee_id'    => $emp->id,
            'work_date'      => $data['work_date'],
            'type'           => $data['type'],
            'requested_time' => $data['requested_time'] ?? null,
            'reason'         => $data['reason'] ?? null,
        ]);

        return back()->with('success', 'Correction request submitted');
    }

    public function approve(Request $request, AttendanceCorrection $correction)
    {
        // TODO: authorize HR/Manager
        $correction->update([
            'status' => 'approved',
            'decider_id' => $request->user()->id,
            'decided_at' => now(),
        ]);

        // Apply to attendance_logs
        $log = AttendanceLog::firstOrCreate(
            ['company_id'=>$correction->company_id,'employee_id'=>$correction->employee_id,'work_date'=>$correction->work_date],
            []
        );

        if ($correction->type === 'check_in' && $correction->requested_time) $log->check_in  = $correction->requested_time;
        if ($correction->type === 'check_out' && $correction->requested_time) $log->check_out = $correction->requested_time;
        if (in_array($correction->type, ['full_day','half_day'])) {
            // mark worked/leave semantics as you prefer (could integrate leave later)
        }
        $log->save();

        return back()->with('success', 'Correction approved');
    }

    public function reject(Request $request, AttendanceCorrection $correction)
    {
        // TODO: authorize
        $correction->update([
            'status' => 'rejected',
            'decider_id' => $request->user()->id,
            'decided_at' => now(),
        ]);

        return back()->with('success', 'Correction rejected');
    }
}
