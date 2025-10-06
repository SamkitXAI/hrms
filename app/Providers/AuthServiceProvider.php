<?php

// app/Providers/AuthServiceProvider.php
use Illuminate\Support\Facades\Gate;

public function boot()
{
    // Permission check from role_permissions table
    Gate::define('perm', function ($user, string $permission) {
        $companyId = session('company_id'); // or resolve from subdomain
        $roleId = $user->memberships()->where('company_id', $companyId)->value('role_id');
        if (!$roleId) return false;
        return \DB::table('role_permissions')->where('role_id', $roleId)->where('permission', $permission)->exists();
    });
}
