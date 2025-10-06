<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('attendance_corrections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->date('work_date');

            // what the employee is asking to change
            $table->enum('type', ['check_in','check_out','full_day','half_day'])->default('check_in');
            $table->dateTime('requested_time')->nullable();  // for in/out fix
            $table->text('reason')->nullable();

            $table->enum('status', ['pending','approved','rejected'])->default('pending');
            $table->foreignId('decider_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('decided_at')->nullable();
            $table->json('meta')->nullable();

            $table->timestamps();
            $table->index(['company_id','employee_id','work_date']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('attendance_corrections');
    }
};
