<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->uuid('event_id');
            $table->uuid('aggregate_root_id');
            $table->integer('version')->unsigned()->nullable();
            $table->string('payload', 16001);
            $table->index(['aggregate_root_id', 'version'], 'reconstitution');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
