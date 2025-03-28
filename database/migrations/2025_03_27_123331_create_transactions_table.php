<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('account_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['Credit', 'Debit']);
            $table->decimal('amount', 15, 2);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('transactions');
    }
};

