<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('name');                 // e.g., "Head Office"
            $table->string('address_line')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('pin')->nullable();
            $table->string('attendance_device_ip')->nullable(); // for biometric/RFID
            $table->string('attendance_device_type')->nullable(); // zkteco, essl, etc.
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('locations');
    }
};
