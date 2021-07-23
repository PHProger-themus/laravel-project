<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditorPrivilegesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('editors', function (Blueprint $table) {
            $table->id('editor_id');
            $table->integer('user_id')->unsigned();
            $table->tinyInteger('canModifyMessages')->default(0);
            $table->tinyInteger('canDeleteMessages')->default(0);
            $table->tinyInteger('canModifyUsers')->default(0);
            $table->tinyInteger('canDeleteUsers')->default(0);
            $table->tinyInteger('canBlockUsers')->default(0);
            $table->tinyInteger('canFilterUsers')->default(0);
            $table->tinyInteger('canManageEditors')->default(0);
            $table->tinyInteger('canCleanChat')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('editors');
    }
}
