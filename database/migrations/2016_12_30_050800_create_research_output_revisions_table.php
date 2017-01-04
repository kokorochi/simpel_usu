<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResearchOutputRevisionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('research_output_revisions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('research_id', false, true);
            $table->smallInteger('item', false, true);
            $table->text('revision_text');
            $table->string('created_by', 30);
            $table->string('updated_by', 30)->nullable();
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
        Schema::dropIfExists('research_output_revisions');
    }
}
