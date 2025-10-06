<?php

Route::post('integrations/devices/{device}/logs', [DeviceIngestController::class, 'ingest']);


use App\Http\Controllers\DeviceIngestController;