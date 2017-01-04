<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResearchReviewersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('research_reviewers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('propose_id', false, true);
            $table->smallInteger('item', false, true);
            $table->string('nidn');
            $table->string('created_by', 30);
            $table->string('updated_by', 30)->nullable();
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
        Schema::dropIfExists('research_reviewers');
    }
}
