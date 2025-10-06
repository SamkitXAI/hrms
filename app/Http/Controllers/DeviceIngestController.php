<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AttendanceLog;
use Illuminate\Support\Facades\Log;

class DeviceIngestController extends Controller
{
    public function ingest(Request $request, string $device)
    {
        // Validate HMAC (X-Signature: sha256(secret, rawBody))
        $raw = $request->getContent();
        $sig = $request->header('X-Signature');
        $secret = config("devices.$device.secret");
        if (!$secret || !hash_equals(hash_hmac('sha256', $raw, $secret), $sig ?? '')) {
            abort(401, 'Invalid signature');
        }

        $payload = $request->validate([
            'company_id'  => ['required','integer'],
            'employee_id' => ['required','integer'],
            'work_date'   => ['required','date'],
            'direction'   => ['required','in:in,out'],
            'timestamp'   => ['required','date'],
            'device_ref'  => ['nullable','string'],
        ]);

        $log = AttendanceLog::firstOrCreate(
            ['company_id'=>$payload['company_id'], 'employee_id'=>$payload['employee_id'], 'work_date'=>$payload['work_date']],
            []
        );

        if ($payload['direction'] === 'in')  $log->check_in  = $payload['timestamp'];
        if ($payload['direction'] === 'out') $log->check_out = $payload['timestamp'];
        $log->source = 'device';
        $log->device_ref = $payload['device_ref'] ?? null;
        $log->save();

        return response()->json(['ok' => true]);
    }
}
