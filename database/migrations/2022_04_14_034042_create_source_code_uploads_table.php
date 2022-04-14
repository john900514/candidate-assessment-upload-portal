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
        Schema::create('source_code_uploads', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->foreignUuid('file_id')->constrained('uploaded_files', 'id');
            $table->string('file_nickname');
            $table->text('description')->nullable();
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
        Schema::dropIfExists('source_code_uploads');
    }
};
