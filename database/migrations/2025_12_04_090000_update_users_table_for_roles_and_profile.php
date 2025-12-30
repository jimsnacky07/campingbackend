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
        Schema::table('users', function (Blueprint $table) {
            $table->string('nama')->after('id');
            $table->enum('role', ['admin', 'user'])->default('user')->after('password');
            $table->text('alamat')->nullable()->after('role');
            $table->string('telp')->nullable()->after('alamat');
            $table->string('foto')->nullable()->after('telp');

            // Rename existing name -> full compatibility with spec
            $table->renameColumn('name', 'username');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nama', 'role', 'alamat', 'telp', 'foto']);
            $table->renameColumn('username', 'name');
        });
    }
};


