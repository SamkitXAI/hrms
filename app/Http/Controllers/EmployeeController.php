<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index() {
        $employees = Employee::with(['department','location'])->latest()->paginate(20);
        return view('employees.index', compact('employees'));
    }

    public function create() {
        $company = auth()->user()->company ?? Company::first();
        return view('employees.create', compact('company'));
    }

    public function store(Request $request) {
        $data = $request->validate([
            'company_id'  => ['required','exists:companies,id'],
            'emp_code'    => ['required','unique:employees,emp_code'],
            'first_name'  => ['required','string','max:100'],
            'last_name'   => ['nullable','string','max:100'],
            'email'       => ['nullable','email'],
            'phone'       => ['nullable','string','max:20'],
            'department_id' => ['nullable','exists:departments,id'],
            'location_id' => ['nullable','exists:locations,id'],
            'doj'         => ['nullable','date'],
            'designation' => ['nullable','string','max:100'],
            'ctc'         => ['nullable','numeric'],
        ]);

        $employee = Employee::create($data);
        return redirect()->route('employees.show', $employee)->with('success', 'Employee created');
    }

    public function show(Employee $employee) {
        $employee->load(['company','department','location','attendanceLogs']);
        return view('employees.show', compact('employee'));
    }
}
