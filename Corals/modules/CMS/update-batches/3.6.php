<?php

if (!\Schema::hasColumn('posts', 'properties')) {

    \Schema::table('posts', function (\Illuminate\Database\Schema\Blueprint $table) {
        $table->text('properties')->nullable();
    });
}