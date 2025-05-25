<?php

// database/migrations/YYYY_MM_DD_create_participations_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParticipationsTable extends Migration
{
    public function up()
    {
        Schema::create('participations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('practice_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['būs', 'nebus'])->default('nebus'); // Player status: 'būs' or 'nebus'
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('participations');
    }
}
