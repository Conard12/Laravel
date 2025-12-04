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
        // Drop foreign keys first
        Schema::table('produits', function (Blueprint $table) {
            $table->dropForeign(['stand_id']);
            $table->dropColumn('stand_id');
        });

        Schema::table('commandes', function (Blueprint $table) {
            $table->dropForeign(['stand_id']);
            $table->dropColumn('stand_id');
        });

        Schema::dropIfExists('stands');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('stands', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nom_stand');
            $table->text('description')->nullable();
            $table->string('logo')->nullable();
            $table->string('adresse')->nullable();
            $table->string('telephone')->nullable();
            $table->enum('statut', ['en_attente', 'approuve', 'rejete'])->default('en_attente');
            $table->timestamps();
        });

        Schema::table('produits', function (Blueprint $table) {
            $table->unsignedBigInteger('stand_id')->nullable();
            $table->foreign('stand_id')->references('id')->on('stands')->onDelete('cascade');
        });

        Schema::table('commandes', function (Blueprint $table) {
            $table->unsignedBigInteger('stand_id')->nullable();
            $table->foreign('stand_id')->references('id')->on('stands')->onDelete('cascade');
        });
    }
};
