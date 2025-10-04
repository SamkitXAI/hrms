<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('payslips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_item_id')->constrained()->cascadeOnDelete();
            $table->string('number')->unique(); // PS-SEP-2025-0001
            $table->string('file_path')->nullable(); // if exported to PDF
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('payslips');
    }
};
