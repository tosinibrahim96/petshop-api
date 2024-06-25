<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->uuid('uuid')->unique()->after('id');
            $table->string('first_name')->after('name');
            $table->string('last_name')->after('first_name');
            $table->boolean('is_admin')->default(0)->after('last_name');
            $table->string('avatar')->nullable()->after('password');
            $table->string('address')->nullable()->after('avatar');
            $table->string('phone_number')->nullable()->after('address');
            $table->boolean('is_marketing')->default(0)->after('phone_number');
            $table->timestamp('last_login_at')->nullable()->after('updated_at');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'uuid',
                'first_name',
                'last_name',
                'is_admin',
                'avatar',
                'address',
                'phone_number',
                'is_marketing',
                'last_login_at',
            ]);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('name');
        });
    }
};
