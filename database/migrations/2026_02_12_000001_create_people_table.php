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
            $table->string('hmeromhnia_eis')->nullable();
            $table->string('syggrafeas')->nullable();
            $table->string('koha')->nullable();
            $table->string('titlos')->nullable();
            $table->string('ekdoths')->nullable();
            $table->string('ekdosh')->nullable();
            $table->string('etosEkdoshs', 20)->nullable();
            $table->string('toposEkdoshs')->nullable();
            $table->string('sxhma')->nullable();
            $table->string('selides')->nullable();
            $table->string('tomos', 50)->nullable();
            $table->string('troposPromPar')->nullable();
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
