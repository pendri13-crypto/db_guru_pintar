<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('grades', function (Blueprint $table) {
            $table->dropColumn(['tugas_1', 'tugas_2', 'formatif', 'sumatif', 'uts', 'uas']);
            
            $table->float('uh1')->nullable()->after('semester');
            $table->float('uh2')->nullable()->after('uh1');
            $table->float('th1')->nullable()->after('uh2');
            $table->float('th2')->nullable()->after('th1');
            $table->float('pts')->nullable()->after('th2');
            $table->float('sumatif_akhir')->nullable()->after('pts');
        });
    }

    public function down(): void
    {
        Schema::table('grades', function (Blueprint $table) {
            $table->dropColumn(['uh1', 'uh2', 'th1', 'th2', 'pts', 'sumatif_akhir']);
            
            $table->float('tugas_1')->nullable();
            $table->float('tugas_2')->nullable();
            $table->float('formatif')->nullable();
            $table->float('sumatif')->nullable();
            $table->float('uts')->nullable();
            $table->float('uas')->nullable();
        });
    }
};
