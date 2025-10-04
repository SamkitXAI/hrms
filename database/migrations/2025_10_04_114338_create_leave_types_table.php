<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('leave_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('name'); // CL, SL, PL, etc.
            $table->decimal('annual_quota', 5, 2)->default(0);
            $table->boolean('requires_approval')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('leave_types');
    }
};
