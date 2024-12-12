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
        Schema::table('poems', function (Blueprint $table) {
            // Rename existing columns
            $table->renameColumn('position_x', 'icon_position_x');
            $table->renameColumn('position_y', 'icon_position_y');

            // Add new columns
            $table->integer('window_position_x')->default(50);
            $table->integer('window_position_y')->default(50);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('poems', function (Blueprint $table) {
            // Reverse the changes
            $table->renameColumn('icon_position_x', 'position_x');
            $table->renameColumn('icon_position_y', 'position_y');

            $table->dropColumn('window_position_x');
            $table->dropColumn('window_position_y');

        });
    }
};
