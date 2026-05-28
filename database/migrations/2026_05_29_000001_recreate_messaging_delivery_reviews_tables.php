<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('delivery_requests');
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('messages');

        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('listing_id')->constrained()->onDelete('cascade');
            $table->foreignId('offer_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('receiver_id')->constrained('users')->onDelete('cascade');
            $table->text('body');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            $table->engine = 'InnoDB';
        });

        Schema::create('delivery_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('listing_id')->constrained()->onDelete('cascade');
            $table->foreignId('offer_id')->constrained()->onDelete('cascade');
            $table->string('requester_address');
            $table->string('offerer_address');
            $table->decimal('delivery_cost', 10, 2);
            $table->string('cost_bearer'); // requester, offerer, split
            $table->enum('status', ['pending', 'picked_up', 'delivered', 'cancelled'])->default('pending');
            $table->timestamps();
            $table->engine = 'InnoDB';
        });

        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('listing_id')->constrained()->onDelete('cascade');
            $table->foreignId('reviewer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('reviewed_id')->constrained('users')->onDelete('cascade');
            $table->unsignedTinyInteger('rating');
            $table->text('comment')->nullable();
            $table->timestamps();
            $table->engine = 'InnoDB';
            $table->unique(['listing_id', 'reviewer_id', 'reviewed_id'], 'listing_reviewer_reviewed_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('delivery_requests');
        Schema::dropIfExists('messages');
    }
};