@extends('layouts.app')

@section('content')
<div class="container py-4">
  <h1 class="h4">Employee: {{ $employee->first_name }} {{ $employee->last_name }}</h1>
  <p class="text-muted">Code: {{ $employee->emp_code }} | {{ optional($employee->company)->name }}</p>

  <form class="d-flex gap-2" method="post" action="{{ route('attendance.checkin',$employee) }}">
    @csrf
    <button class="btn btn-success">Check In</button>
  </form>
  <form class="d-inline" method="post" action="{{ route('attendance.checkout',$employee) }}">
    @csrf
    <button class="btn btn-danger ms-2">Check Out</button>
  </form>

  <div class="card mt-4">
    <div class="card-header">Attendance</div>
    <div class="card-body p-0">
      <table class="table table-sm mb-0">
        <thead><tr><th>Date</th><th>In</th><th>Out</th><th>Late</th><th>OT</th></tr></thead>
        <tbody>
        @foreach($employee->attendanceLogs()->latest('work_date')->limit(30)->get() as $log)
          <tr>
            <td>{{ $log->work_date->format('Y-m-d') }}</td>
            <td>{{ $log->check_in }}</td>
            <td>{{ $log->check_out }}</td>
            <td>{{ $log->late_minutes }}m</td>
            <td>{{ $log->ot_minutes }}m</td>
          </tr>
        @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
