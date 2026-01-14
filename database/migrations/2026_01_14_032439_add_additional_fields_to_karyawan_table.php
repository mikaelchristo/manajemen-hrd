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
        Schema::table('karyawan', function (Blueprint $table) {
            $table->date('tglMulaiKerja')->nullable()->after('jenisKelamin');
            $table->date('skTetap')->nullable()->after('tglMulaiKerja');
            $table->string('pendidikan', 50)->nullable()->after('skTetap');
            $table->string('tamatan', 255)->nullable()->after('pendidikan');
            $table->string('noHp', 20)->nullable()->after('tamatan');
            $table->string('email', 100)->nullable()->after('noHp');
            $table->text('alamat')->nullable()->after('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('karyawan', function (Blueprint $table) {
            $table->dropColumn(['tglMulaiKerja', 'skTetap', 'pendidikan', 'tamatan', 'noHp', 'email', 'alamat']);
        });
    }
};
