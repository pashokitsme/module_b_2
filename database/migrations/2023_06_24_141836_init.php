<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists("personal_access_tokens");

        Schema::create("users", function (Blueprint $schema) {
            $schema->id();
            $schema->string("name");
            $schema->string("email")->unique();
            $schema->string("password");
            $schema->string("token")->unique()->nullable();
            $schema->timestamps();
        });

        Schema::create("files", function (Blueprint $schema) {
            $schema->id();
            $schema->string("hash")->unique();
            $schema->string("name");
            $schema->unsignedBigInteger("author_ref");
            $schema->timestamps();
        });

        Schema::create("accesses", function (Blueprint $schema) {
            $schema->id();
            $schema->unsignedBigInteger("file_ref");
            $schema->unsignedBigInteger("user_ref");
            $schema->timestamps();

            $schema->unique(['file_ref', 'user_ref']);
        });

        Schema::table("files", function(Blueprint $schema) {
            $schema->foreign('author_ref')->on('users')->references('id');
        });

        Schema::table("accesses", function (Blueprint $schema) {
            $schema->foreign("file_ref")->on("files")->references("id")->onDelete("cascade");
            $schema->foreign("user_ref")->on("users")->references("id")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
