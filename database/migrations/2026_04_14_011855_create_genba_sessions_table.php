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
    Schema::create('genba_sessions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('dealer_id')->constrained()->onDelete('cascade');
        $table->foreignId('role_id')->constrained()->onDelete('cascade');
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); // user MD yg ngisi
        $table->string('auditee_name'); // nama staf dealer yg diwawancara
        $table->string('honda_id')->nullable();
        $table->enum('status', ['draft', 'submitted'])->default('draft');
        $table->timestamp('submitted_at')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('genba_sessions');
    }
};
