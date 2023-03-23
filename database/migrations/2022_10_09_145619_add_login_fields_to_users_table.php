<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->after('email', function () use ($table) {
                $table->timestamp('last_login')->nullable();
                $table->string('ip', 50)->nullable();
                $table->string('user_agent')->nullable();
            });
            $table->softDeletes()->after('updated_at');
        });
    }
};
