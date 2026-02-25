<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    public function up(): void
    {
     Schema::table('people', function (Blueprint $table) {

    // Drop the index first
    $table->dropIndex(['titlos']);

    // Change column type
    $table->text('titlos')->nullable()->change();

     });
    }


    public function down(): void
    {
        Schema::table('people', function (Blueprint $table) {
            $table->string('titlos', 255)->nullable()->change();
        });
    }

};
