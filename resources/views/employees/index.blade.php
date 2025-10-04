@extends('layouts.app')

@section('content')
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4">Employees</h1>
    <a href="{{ route('employees.create') }}" class="btn btn-primary">Add Employee</a>
  </div>
  <div class="card">
    <div class="card-body p-0">
      <table class="table table-sm mb-0">
        <thead><tr><th>Code</th><th>Name</th><th>Dept</th><th>Location</th><th></th></tr></thead>
        <tbody>
        @foreach($employees as $e)
          <tr>
            <td>{{ $e->emp_code }}</td>
            <td>{{ $e->first_name }} {{ $e->last_name }}</td>
            <td>{{ optional($e->department)->name }}</td>
            <td>{{ optional($e->location)->name }}</td>
            <td><a class="btn btn-link btn-sm" href="{{ route('employees.show',$e) }}">View</a></td>
          </tr>
        @endforeach
        </tbody>
      </table>
    </div>
  </div>
  <div class="mt-3">{{ $employees->links() }}</div>
</div>
@endsection
