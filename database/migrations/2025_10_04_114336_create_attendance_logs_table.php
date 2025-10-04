<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('attendance_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->foreignId('location_id')->nullable()->constrained()->nullOnDelete();

            $table->date('work_date');
            $table->dateTime('check_in')->nullable();
            $table->dateTime('check_out')->nullable();
            $table->unsignedInteger('late_minutes')->default(0);
            $table->unsignedInteger('ot_minutes')->default(0);

            $table->string('source')->default('device'); // device, manual, api
            $table->string('device_ref')->nullable();    // punch id/reference
            $table->json('raw')->nullable();
            $table->timestamps();

            $table->unique(['employee_id','work_date']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('attendance_logs');
    }
};
