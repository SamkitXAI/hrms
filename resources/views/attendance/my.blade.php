@extends('layouts.app')
@section('content')
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h5 mb-0">My Attendance — {{ \Carbon\Carbon::parse($month.'-01')->format('F Y') }}</h1>
    <form class="d-flex gap-2">
      <input type="month" class="form-control" name="month" value="{{ $month }}">
      <button class="btn btn-outline-primary">Go</button>
    </form>
  </div>

  <div class="card">
    <div class="card-body p-0">
      <table class="table table-sm mb-0 align-middle">
        <thead><tr><th>Date</th><th>In</th><th>Out</th><th>Late</th><th>OT</th><th></th></tr></thead>
        <tbody>
        @for($day = $from->copy(); $day <= $to; $day->addDay())
          @php $d = $day->format('Y-m-d'); $log = $logs->get($d); @endphp
          <tr>
            <td>{{ $day->format('D, d M') }}</td>
            <td>{{ optional($log)->check_in }}</td>
            <td>{{ optional($log)->check_out }}</td>
            <td>{{ optional($log)->late_minutes }}m</td>
            <td>{{ optional($log)->ot_minutes }}m</td>
            <td class="text-end">
              <button class="btn btn-link btn-sm" data-bs-toggle="modal" data-bs-target="#corrModal" data-date="{{ $d }}">
                Request correction
              </button>
            </td>
          </tr>
        @endfor
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Correction Modal -->
<div class="modal fade" id="corrModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog"><div class="modal-content">
    <form method="post" action="{{ route('attendance.corrections.store') }}">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Request Correction</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="work_date" id="corrDate">
        <div class="mb-3">
          <label class="form-label">Type</label>
          <select name="type" class="form-select" required>
            <option value="check_in">Check In</option>
            <option value="check_out">Check Out</option>
            <option value="full_day">Full Day</option>
            <option value="half_day">Half Day</option>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Requested Time (if applicable)</label>
          <input type="datetime-local" name="requested_time" class="form-control">
        </div>
        <div class="mb-3">
          <label class="form-label">Reason</label>
          <textarea name="reason" class="form-control" rows="3"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary">Submit</button>
      </div>
    </form>
  </div></div>
</div>

<script>
document.getElementById('corrModal').addEventListener('show.bs.modal', (ev) => {
  const btn = ev.relatedTarget;
  const date = btn?.getAttribute('data-date');
  document.getElementById('corrDate').value = date;
});
</script>
@endsection
