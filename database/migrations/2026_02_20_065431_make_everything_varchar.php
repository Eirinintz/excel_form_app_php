<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('people', function (Blueprint $table) {

        $table->string('hmeromhnia_eis')->nullable()->change();
        $table->string('syggrafeas', 500)->nullable()->change();
        $table->string('koha', 400)->nullable()->change();
        $table->string('titlos', 500)->nullable()->change();
        $table->string('ekdoths')->nullable()->change();
        $table->string('ekdosh')->nullable()->change();
        $table->string('etosEkdoshs')->nullable()->change();
        $table->string('toposEkdoshs')->nullable()->change();
        $table->string('sxhma')->nullable()->change();
        $table->string('selides')->nullable()->change();
        $table->string('tomos')->nullable()->change();
        $table->string('troposPromPar')->nullable()->change();
        $table->string('ISBN')->nullable()->change();
        $table->string('sthlh1')->nullable()->change();
        $table->string('sthlh2')->nullable()->change();
    });
}

    public function down(): void
    {
        Schema::table('people', function (Blueprint $table) {
            // Revert back to original sizes
            $table->string('hmeromhnia_eis', 200)->nullable()->change();
            $table->string('syggrafeas')->nullable()->change();
            $table->string('koha')->nullable()->change();
            $table->string('titlos')->nullable()->change();
            $table->string('ekdoths' )->nullable()->change();
            $table->string('ekdosh')->nullable()->change();
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
