<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateOutputGeneralTableAddUrlStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('research_output_generals', function (Blueprint $table) {
            $table->string('url_address', 80)->nullable();
            $table->string('status', 30)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('research_output_generals', function (Blueprint $table) {
            $table->dropColumn('url_address');
            $table->dropColumn('status');
        });
    }
}
