<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('files', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->foreignId('user_id')->constrained('users');
            $table->string('filename');
            $table->string('client_filename');
            $table->foreignId('message_id')->nullable()->constrained('messages')->noActionOnDelete();
            $table->tinyInteger('type')->unsigned()->index();
            $table->timestamp('created_at');

            $table->index(['message_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
