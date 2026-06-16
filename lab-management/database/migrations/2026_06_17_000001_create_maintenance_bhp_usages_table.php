<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintenance_bhp_usages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('maintenance_id');
            $table->unsignedBigInteger('bhp_item_id');
            $table->unsignedInteger('quantity_used')->default(0);
            $table->timestamps();

            $table->foreign('maintenance_id')->references('id')->on('inventory_maintenances')->onDelete('cascade');
            $table->foreign('bhp_item_id')->references('id')->on('bhp_items')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_bhp_usages');
    }
};
