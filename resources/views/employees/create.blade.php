@extends('layouts.app')

@section('content')
<div class="container py-4">
  <h1 class="h4 mb-3">Add Employee</h1>
  <form method="post" action="{{ route('employees.store') }}">
    @csrf
    <input type="hidden" name="company_id" value="{{ $company->id }}">
    <div class="row g-3">
      <div class="col-md-3">
        <label class="form-label">Emp Code</label>
        <input name="emp_code" class="form-control" required>
      </div>
      <div class="col-md-3">
        <label class="form-label">First Name</label>
        <input name="first_name" class="form-control" required>
      </div>
      <div class="col-md-3">
        <label class="form-label">Last Name</label>
        <input name="last_name" class="form-control">
      </div>
      <div class="col-md-3">
        <label class="form-label">Email</label>
        <input name="email" type="email" class="form-control">
      </div>
      <div class="col-md-3">
        <label class="form-label">Phone</label>
        <input name="phone" class="form-control">
      </div>
      <div class="col-md-3">
        <label class="form-label">DOJ</label>
        <input name="doj" type="date" class="form-control">
      </div>
      <div class="col-md-3">
        <label class="form-label">Designation</label>
        <input name="designation" class="form-control">
      </div>
      <div class="col-md-3">
        <label class="form-label">CTC</label>
        <input name="ctc" type="number" step="0.01" class="form-control">
      </div>
    </div>
    <div class="mt-3">
      <button class="btn btn-primary">Save</button>
    </div>
  </form>
</div>
@endsection
