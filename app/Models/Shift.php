<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    protected $fillable = [
        'company_id',
        'name',
        'start_at',
        'end_at',
        'grace_minutes',
    ];

    protected $casts = [
        'start_at' => 'datetime:H:i',
        'end_at' => 'datetime:H:i',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function employees()
    {
        return $this->belongsToMany(Employee::class)
            ->withPivot(['effective_from', 'effective_to'])
            ->withTimestamps();
    }
}
