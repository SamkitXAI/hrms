<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('payroll_cycles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('name'); // e.g., "Sep 2025"
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['open','locked','processed'])->default('open');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('payroll_cycles');
    }
};
