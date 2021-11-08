<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsFbToAccountClientsTbl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('account_clients', function (Blueprint $table) {
            $table->tinyInteger('is_fb')->default(0)->nullable()->after('address_plus');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('account_clients', function (Blueprint $table) {
            //
        });
    }
}
