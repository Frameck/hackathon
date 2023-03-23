<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('alliances', function (Blueprint $table) {
            $table->id();
            $table->string('alliance_id')->nullable()->index();
            $table->integer('family_friendly')->nullable();
            $table->date('date')->nullable();
            $table->foreignIdFor(User::class, 'created_by')->nullable()->constrained(User::getTableName());
            $table->foreignIdFor(User::class, 'updated_by')->nullable()->constrained(User::getTableName());
            $table->timestamps();
            $table->softDeletes();
        });
    }
};
