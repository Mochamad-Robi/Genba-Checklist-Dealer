<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('users', function (Blueprint $table) {
        \DB::statement("ALTER TABLE users MODIFY user_type ENUM('admin', 'auditor', 'kacab') DEFAULT 'auditor'");
        $table->foreignId('dealer_id')->nullable()->constrained()->onDelete('set null')->after('user_type');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
