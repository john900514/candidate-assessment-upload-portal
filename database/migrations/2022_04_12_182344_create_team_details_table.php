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
        Schema::create('team_details', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->foreignUuid('team_id')->constrained('teams', 'id');
            $table->string('detail');
            $table->string('value')->nullable();
            $table->longText('misc')->nullable();
            $table->boolean('active')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('team_details');
    }
};
