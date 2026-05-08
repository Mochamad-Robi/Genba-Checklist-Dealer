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
    Schema::create('picas', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); // yang ngisi
        $table->foreignId('dealer_id')->constrained()->onDelete('cascade');
        $table->string('pic'); // nama PIC yang bertanggung jawab
        $table->text('masalah'); // kendala/temuan
        $table->text('analisa')->nullable(); // analisa masalah
        $table->text('tindakan')->nullable(); // tindakan yang diambil
        $table->date('target_date')->nullable(); // target selesai
        $table->enum('status', ['open', 'on_progress', 'closed'])->default('open');
        $table->text('keterangan')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('picas');
    }
};
