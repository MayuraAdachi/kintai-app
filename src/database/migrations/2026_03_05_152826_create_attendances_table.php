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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->comment('ユーザーID');
            $table->date('date')->comment('出勤日');
            $table->time('clock_in_time')->nullable()->comment('出勤時刻');
            $table->time('clock_out_time')->nullable()->comment('退勤時刻');
            $table->integer('break_time_minutes')->default(0)->comment('休憩時間（分）');
            $table->text('notes')->nullable()->comment('備考');
            $table->enum('status', ['draft', 'submitted', 'approved', 'rejected'])->default('draft')->comment('ステータス');
            $table->timestamps();

            $table->unique(['user_id', 'date'])->comment('1ユーザー1日1レコードの制約');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
