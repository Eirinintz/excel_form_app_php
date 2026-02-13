<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('upload_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->dateTime('uploaded_at');
            $table->string('filename', 255);
            $table->unsignedInteger('rows_added')->default(0);
            $table->unsignedInteger('rows_updated')->default(0);
            $table->index('uploaded_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('upload_logs');
    }
};
