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
        Schema::create('users', function (Blueprint $table) {
            $table->id()->comment('ユーザーID');
            $table->string('name')->comment('ユーザー名');
            $table->string('email')->unique()->comment('メールアドレス');
            $table->timestamp('email_verified_at')->nullable()->comment('メール認証日時');
            $table->string('password')->comment('パスワード');
            $table->rememberToken()->comment('ログイン記録トークン');
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary()->comment('メールアドレス');
            $table->string('token')->comment('パスワードリセットトークン');
            $table->timestamp('created_at')->nullable()->comment('作成日時');
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary()->comment('セッションID');
            $table->foreignId('user_id')->nullable()->index()->comment('ユーザーID');
            $table->string('ip_address', 45)->nullable()->comment('IPアドレス');
            $table->text('user_agent')->nullable()->comment('ユーザーエージェント');
            $table->longText('payload')->comment('セッションデータ');
            $table->integer('last_activity')->index()->comment('最終アクティビティ');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
