<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUsersEmailUniqueIndex  extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop the existing unique index on email
            $table->dropUnique('users_email_unique');

            // Add a composite unique index on email and deleted_at
            $table->unique(['email', 'deleted_at'], 'users_email_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop the composite unique index
            $table->dropUnique('users_email_unique');

            // Re-add the original unique index on email
            $table->unique('email', 'users_email_unique');
        });
    }
}
