<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('params', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('is_block');
            $table->string('permitted_ip', 255)->nullable();
            $table->string('link_for_redirects', 255)->nullable();
            $table->boolean('is_logs');
            $table->boolean('is_cabinet');
        });

        DB::table('params')->insert(
            array(
                'is_block' => 1,
                'permitted_ip' => "46.201.243.237,109.87.115.3,159.224.52.101,178.165.9.88,109.87.9.151",
                'link_for_redirects' => "",
                'is_logs' => 1,
                'is_cabinet' => 0
            )
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('params');
    }
}
