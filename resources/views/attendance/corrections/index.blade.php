@extends('layouts.app')
@section('content')
<div class="container py-4">
  <h1 class="h5">Attendance Corrections</h1>
  <div class="card">
    <div class="table-responsive">
      <table class="table table-sm mb-0">
        <thead><tr><th>Emp</th><th>Date</th><th>Type</th><th>Time</th><th>Reason</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
        <tbody>
        @foreach($corrections as $c)
          <tr>
            <td>{{ $c->employee->first_name }} {{ $c->employee->last_name }}</td>
            <td>{{ $c->work_date }}</td>
            <td>{{ $c->type }}</td>
            <td>{{ $c->requested_time }}</td>
            <td>{{ $c->reason }}</td>
            <td><span class="badge bg-{{ $c->status === 'pending' ? 'warning' : ($c->status === 'approved' ? 'success' : 'danger') }}">{{ $c->status }}</span></td>
            <td class="text-end">
              @if($c->status === 'pending')
              <form class="d-inline" method="post" action="{{ route('attendance.corrections.approve',$c) }}">@csrf <button class="btn btn-success btn-sm">Approve</button></form>
              <form class="d-inline" method="post" action="{{ route('attendance.corrections.reject',$c) }}">@csrf <button class="btn btn-outline-danger btn-sm">Reject</button></form>
              @endif
            </td>
          </tr>
        @endforeach
        </tbody>
      </table>
    </div>
  </div>
  <div class="mt-3">{{ $corrections->links() }}</div>
</div>
@endsection
