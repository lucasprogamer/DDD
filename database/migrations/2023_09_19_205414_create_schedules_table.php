<?php

use App\Domain\Schedule\Enums\ScheduleStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->string('title', 40);
            $table->string('description');
            $table->enum('status', array_map(fn ($item) => $item->value, ScheduleStatusEnum::cases()))
                ->default(ScheduleStatusEnum::OPEN->value);
            $table->unsignedBigInteger('user_id');
            $table->datetime('starts_at');
            $table->datetime('ends_at')->nullable();
            $table->datetime('finished_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
