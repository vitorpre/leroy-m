<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductSheetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_sheets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('file', 255);
            $table->boolean('processed')->default(false);
            $table->boolean('success')->nullable(true)->default(null);
            $table->text('message')->nullable(true);
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
        Schema::dropIfExists('product_sheets');
    }
}
