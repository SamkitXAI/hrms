<?php

namespace App\Console\Commands;

use App\Models\AttendanceLog;
use App\Models\Employee;
use App\Models\Location;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ImportAttendanceFromDevice extends Command
{
    protected $signature = 'attendance:import {location_id}';
    protected $description = 'Import attendance punches from device or CSV for a location';

    public function handle(): int
    {
        $location = Location::findOrFail($this->argument('location_id'));

        // TODO: replace with real device SDK/API
        // Example: read a CSV at storage/app/attendance/location_{id}.csv
        $path = storage_path("app/attendance/location_{$location->id}.csv");
        if (!file_exists($path)) {
            $this->error("No file found: $path");
            return self::FAILURE;
        }

        $rows = array_map('str_getcsv', file($path));
        // expected: emp_code,work_date(YYYY-MM-DD),check_in(YYYY-MM-DD HH:mm),check_out(...),device_ref
        foreach ($rows as $r) {
            [$empCode, $wdate, $in, $out, $ref] = $r;
            $employee = Employee::where('emp_code', $empCode)->first();
            if (!$employee) continue;

            AttendanceLog::updateOrCreate(
                ['employee_id' => $employee->id, 'work_date' => $wdate],
                [
                    'company_id' => $employee->company_id,
                    'location_id' => $employee->location_id,
                    'check_in' => $in ?: null,
                    'check_out' => $out ?: null,
                    'device_ref' => $ref ?: null,
                    'source' => 'device',
                ]
            );
        }

        $this->info("Imported: ".count($rows));
        return self::SUCCESS;
    }
}
