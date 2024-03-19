<?php
declare(strict_types=1);

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
        Schema::create('drafts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from_id')->constrained('users');
            $table->foreignId('to_id')->constrained('users');
            $table->text('text')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->unique(['from_id', 'to_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drafts');
    }
};
