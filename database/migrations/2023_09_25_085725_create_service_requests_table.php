<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained('services')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            ## Translation / Interpretation ##
            $table->unsignedInteger('trans_type')->nullable(); // 0 for translation , 1 for interpretation.
            $table->unsignedInteger('lang_from')->nullable();
            $table->unsignedInteger('lang_to')->nullable();
            $table->string('email')->nullable();
            $table->unsignedInteger('trans_industry')->nullable();
            $table->string('full_name')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('attachment')->nullable();
            $table->text('notes')->nullable();
            $table->date('date')->nullable();
            $table->string('time')->nullable();
            $table->string('country')->nullable();
            $table->string('adress')->nullable();
            $table->string('town_city')->nullable();
            $table->string('state_zone')->nullable();
            $table->unsignedBigInteger('postal_code')->nullable();

            $table->unsignedInteger('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_requests');
    }
};
