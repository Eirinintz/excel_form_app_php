<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    public function up(): void
    {
        Schema::table('people', function (Blueprint $table) {

            // Drop indexed columns first
           
            $table->dropIndex(['ekdoths']);

            // Convert all string columns to TEXT
            $table->text('hmeromhnia_eis')->nullable()->change();
            $table->text('syggrafeas')->nullable()->change();
            $table->text('koha')->nullable()->change();
            $table->text('titlos')->nullable()->change();
            $table->text('ekdoths')->nullable()->change();
            $table->text('ekdosh')->nullable()->change();
            $table->text('etosEkdoshs')->nullable()->change();
            $table->text('toposEkdoshs')->nullable()->change();
            $table->text('sxhma')->nullable()->change();
            $table->text('selides')->nullable()->change();
            $table->text('tomos')->nullable()->change();
            $table->text('troposPromPar')->nullable()->change();
            $table->text('ISBN')->nullable()->change();
            $table->text('sthlh1')->nullable()->change();
            $table->text('sthlh2')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('people', function (Blueprint $table) {

            // Revert back to original sizes
            $table->string('hmeromhnia_eis', 200)->nullable()->change();
            $table->string('syggrafeas', 200)->nullable()->change();
            $table->string('koha', 200)->nullable()->change();
            $table->string('titlos', 255)->nullable()->change();
            $table->string('ekdoths', 255)->nullable()->change();
            $table->string('ekdosh', 255)->nullable()->change();
            $table->string('etosEkdoshs', 20)->nullable()->change();
            $table->string('toposEkdoshs', 255)->nullable()->change();
            $table->string('sxhma', 200)->nullable()->change();
            $table->string('selides', 50)->nullable()->change();
            $table->string('tomos', 50)->nullable()->change();
            $table->string('troposPromPar', 200)->nullable()->change();
            $table->string('ISBN', 50)->nullable()->change();
            $table->string('sthlh1', 200)->nullable()->change();
            $table->string('sthlh2', 200)->nullable()->change();

            // Re-add indexes
            $table->index('titlos');
            $table->index('ekdoths');
        });
    }
};



