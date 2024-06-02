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
        Schema::table('sewa', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('kaset_id');

            $table->foreign('user_id')
                  ->references('id')->on('users')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');

              $table->foreign('kaset_id')
                  ->references('id')->on('kaset')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sewa', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->dropColumn('kaset_id');

        });
    }
};
