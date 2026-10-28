<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kategoris', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
            $table->index(['user_id', 'jenis']);
        });

        Schema::table('transaksis', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
            $table->index(['user_id', 'tanggal']);
        });

        Schema::table('anggarans', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
            $table->index(['user_id', 'tahun', 'bulan']);
        });
    }

    public function down(): void
    {
        Schema::table('anggarans', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'tahun', 'bulan']);
            $table->dropConstrainedForeignId('user_id');
        });
        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'tanggal']);
            $table->dropConstrainedForeignId('user_id');
        });
        Schema::table('kategoris', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'jenis']);
            $table->dropConstrainedForeignId('user_id');
        });
    }
};
