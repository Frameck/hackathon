<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->string('account_id')->nullable()->index();
            $table->string('alliance_id')->nullable()->index();
            $table->timestamp('timestamp')->nullable();
            $table->date('date')->nullable();
            $table->text('raw_message')->nullable();
            $table->text('filtered_message')->nullable();
            $table->boolean('filtered')->nullable();
            $table->json('filtered_content')->nullable();
            $table->double('risk')->nullable();
            $table->string('filter_detected_language')->nullable();
            $table->boolean('is_family_friendly')->nullable();
            $table->integer('general_risk')->nullable();
            $table->double('bullying')->nullable();
            $table->double('violence')->nullable();
            $table->double('relationship_sexual_content')->nullable();
            $table->double('vulgarity')->nullable();
            $table->double('drugs_alcohol')->nullable();
            $table->double('in_app')->nullable();
            $table->double('alarm')->nullable();
            $table->double('fraud')->nullable();
            $table->double('hate_speech')->nullable();
            $table->double('religious')->nullable();
            $table->double('website')->nullable();
            $table->double('child_grooming')->nullable();
            $table->double('public_threat')->nullable();
            $table->double('extremism')->nullable();
            $table->double('subversive')->nullable();
            $table->double('sentiment')->nullable();
            $table->double('politics')->nullable();
            $table->foreignIdFor(User::class, 'created_by')->nullable()->constrained(User::getTableName());
            $table->foreignIdFor(User::class, 'updated_by')->nullable()->constrained(User::getTableName());
            $table->timestamps();
            $table->softDeletes();
        });
    }
};
