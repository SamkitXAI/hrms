@extends('layouts.app')

@section('content')
<div class="container py-4">
  <h1 class="h4">Attendance — {{ $date->toDateString() }}</h1>
  <div class="card">
    <div class="card-body p-0">
      <table class="table table-sm mb-0">
        <thead><tr><th>Employee</th><th>In</th><th>Out</th><th>Late</th><th>OT</th></tr></thead>
        <tbody>
        @foreach($logs as $log)
          <tr>
            <td>{{ $log->employee->first_name }} {{ $log->employee->last_name }}</td>
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
  <div class="mt-3">{{ $logs->links() }}</div>
</div>
@endsection
