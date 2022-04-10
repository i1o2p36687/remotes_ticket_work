<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket', function (Blueprint $table) {
            $table->id();
            $table->string('type')->comment('類型 bug or test or feature');
            $table->string('title')->comment('標題');
            $table->text('description')->comment('描述');
            $table->boolean('priority')->default(false)->comment('是否優先解決');
            $table->integer('status')->default(1)->comment('狀態 0:刪除 1:建立 2:完成');
            $table->timestamps();
            $table->index('type');
            $table->index('priority');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ticket');
    }
}
