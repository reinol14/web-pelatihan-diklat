<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('admin', function (Blueprint $table) {
            $table->id(); // Bigint auto-increment
            $table->string('name');
            $table->string('email')->unique(); // Add unique constraint
            $table->string('password');
            $table->tinyInteger('is_admin')->default(0); // 0: Non-admin, 1: Admin
            $table->timestamps(); // Includes created_at and updated_at
            
        });
        DB::table('admin')->insert([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('12345678'), // Hash the password
            'is_admin' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin');
    }
};
