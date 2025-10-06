@php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

$companyId = session('company_id') ?? optional(auth()->user()->employee)->company_id;

$modulesJson = DB::table('settings')
    ->where('company_id', $companyId)
    ->where('key', 'modules_enabled')
    ->value('value');

$modules = collect(json_decode($modulesJson ?? '[]', true));

$canSee = function (string $module, string $permission) use ($modules) {
    return $modules->contains($module) && Gate::allows('perm', $permission);
};
@endphp


<aside class="sidebar border-end bg-light">
  <ul class="list-unstyled m-0 p-3">
    {{-- Always visible to authenticates (dashboard) --}}
    <li><a class="d-block py-2" href="{{ route('dashboard') }}">🏠 Dashboard</a></li>

    {{-- Superadmin-only block (global) --}}
    @if(auth()->user()->is_superadmin ?? false)
      <li class="mt-2 fw-bold text-uppercase small text-muted">Superadmin</li>
      <li><a class="d-block py-2" href="{{ route('sa.tenants.index') }}">🏢 Tenants</a></li>
      <li><a class="d-block py-2" href="{{ route('sa.plans.index') }}">📦 Plans</a></li>
    @endif

    {{-- Company admin tools --}}
    @if(Gate::allows('perm','company.manage'))
      <li class="mt-2 fw-bold text-uppercase small text-muted">Company</li>
      <li><a class="d-block py-2" href="{{ route('company.settings') }}">⚙️ Settings</a></li>
      <li><a class="d-block py-2" href="{{ route('roles.index') }}">👥 Roles & Permissions</a></li>
      <li><a class="d-block py-2" href="{{ route('employees.index') }}">🧑‍💼 Employees</a></li>
      <li><a class="d-block py-2" href="{{ route('locations.index') }}">📍 Locations/Branches</a></li>
    @endif

    {{-- Attendance --}}
    @if(canSee('attendance','attendance.view'))
      <li class="mt-2 fw-bold text-uppercase small text-muted">Attendance</li>
      <li><a class="d-block py-2" href="{{ route('attendance.my') }}">🕒 My Attendance</a></li>
      @if(Gate::allows('perm','attendance.manage'))
        <li><a class="d-block py-2" href="{{ route('attendance.index') }}">📊 Team Board</a></li>
        <li><a class="d-block py-2" href="{{ route('attendance.corrections.index') }}">✅ Corrections</a></li>
      @endif
    @endif

    {{-- Leave --}}
    @if(canSee('leave','leave.view'))
      <li class="mt-2 fw-bold text-uppercase small text-muted">Leave</li>
      <li><a class="d-block py-2" href="{{ route('leave.my') }}">🗓️ My Leave</a></li>
      @if(Gate::allows('perm','leave.approve'))
        <li><a class="d-block py-2" href="{{ route('leave.approvals') }}">✅ Approvals</a></li>
      @endif
    @endif

    {{-- Payroll --}}
    @if(canSee('payroll','payroll.view'))
      <li class="mt-2 fw-bold text-uppercase small text-muted">Payroll</li>
      @if(Gate::allows('perm','payroll.run'))
        <li><a class="d-block py-2" href="{{ route('payroll.runs.index') }}">🧾 Runs</a></li>
      @endif
      <li><a class="d-block py-2" href="{{ route('payslips.my') }}">📄 My Payslips</a></li>
    @endif
  </ul>
</aside>
