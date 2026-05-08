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
    Schema::table('picas', function (Blueprint $table) {
        $table->foreignId('session_id')->nullable()->constrained('genba_sessions')->onDelete('cascade');
        $table->foreignId('question_id')->nullable()->constrained()->onDelete('cascade');
        $table->string('indikator')->nullable(); // Tidak Paham / Tidak Dipakai
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('picas', function (Blueprint $table) {
            //
        });
    }
};
