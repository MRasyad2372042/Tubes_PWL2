<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintenance_logs', function (Blueprint $table) {
            $table->id();
            $table->string('inventory_item');
            $table->string('condition');
            $table->unsignedBigInteger('bhp_item_id')->nullable();
            $table->unsignedInteger('bhp_used')->default(0);
            $table->date('maintenance_date');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('bhp_item_id')->references('id')->on('bhp_items')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_logs');
    }
};
