<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('account_id')->nullable()->index();
            $table->string('alliance_id')->nullable()->index();
            $table->integer('session_count')->nullable();
            $table->double('session_duration')->nullable();
            $table->integer('transaction_count')->nullable();
            $table->double('revenue')->nullable();
            $table->date('date')->nullable();
            $table->boolean('active')->nullable();
            $table->integer('account_state')->nullable();
            $table->date('last_active_date')->nullable();
            $table->double('level')->nullable();
            $table->string('created_language')->nullable();
            $table->string('created_country_code')->nullable();
            $table->timestamp('created_time')->nullable();
            $table->integer('session_count_today')->nullable();
            $table->double('session_duration_today')->nullable();
            $table->integer('transaction_count_today')->nullable();
            $table->double('revenue_today')->nullable();
            $table->string('last_login_game_client_language')->nullable();
            $table->string('last_login_country_code')->nullable();
            $table->foreignIdFor(User::class, 'created_by')->nullable()->constrained(User::getTableName());
            $table->foreignIdFor(User::class, 'updated_by')->nullable()->constrained(User::getTableName());
            $table->timestamps();
            $table->softDeletes();
        });
    }
};
