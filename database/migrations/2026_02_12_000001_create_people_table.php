<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('people', function (Blueprint $table) {
            $table->integer('ari8mosEisagoghs')->primary();
            $table->string('hmeromhnia_eis', 200)->nullable();
            $table->string('syggrafeas', 200)->nullable();
            $table->string('koha', 200)->nullable();
            $table->string('titlos', 200)->nullable();
            $table->string('ekdoths', 200)->nullable();
            $table->string('ekdosh', 200)->nullable();
            $table->string('etosEkdoshs', 20)->nullable();
            $table->string('toposEkdoshs', 200)->nullable();
            $table->string('sxhma', 200)->nullable();
            $table->string('selides', 50)->nullable();
            $table->string('tomos', 50)->nullable();
            $table->string('troposPromPar', 200)->nullable();
            $table->string('ISBN', 50)->nullable();
            $table->string('sthlh1', 200)->nullable();
            $table->string('sthlh2', 200)->nullable();
            $table->index('titlos');
            $table->index('ekdoths');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('people');
    }
};
