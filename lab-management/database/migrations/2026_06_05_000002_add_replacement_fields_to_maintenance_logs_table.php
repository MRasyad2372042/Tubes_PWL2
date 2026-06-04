<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('maintenance_logs', function (Blueprint $table) {
            if (! Schema::hasColumn('maintenance_logs', 'replacement_item')) {
                $table->string('replacement_item')->nullable()->after('condition');
            }
            if (! Schema::hasColumn('maintenance_logs', 'replaced_by')) {
                $table->string('replaced_by')->nullable()->after('replacement_item');
            }
        });
    }

    public function down(): void
    {
        Schema::table('maintenance_logs', function (Blueprint $table) {
            if (Schema::hasColumn('maintenance_logs', 'replaced_by')) {
                $table->dropColumn('replaced_by');
            }
            if (Schema::hasColumn('maintenance_logs', 'replacement_item')) {
                $table->dropColumn('replacement_item');
            }
        });
    }
};
