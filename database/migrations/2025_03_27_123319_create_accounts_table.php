<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('accounts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('account_name')->unique();
            $table->bigInteger('account_number')->unique();
            $table->enum('account_type', ['Personal', 'Business']);
            $table->enum('currency', ['USD', 'EUR', 'GBP']);
            $table->decimal('balance', 15, 2)->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('accounts');
    }
};

