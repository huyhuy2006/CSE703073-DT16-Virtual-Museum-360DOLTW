<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Bài viết giới thiệu, tin tức và nội dung hướng dẫn
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->char('locale', 2);
            $table->string('slug', 160);
            $table->string('title', 220);
            $table->string('excerpt', 500)->nullable();
            $table->mediumText('body');

            $table->foreignId('author_id')
                ->constrained('users');

            $table->foreignId('museum_id')
                ->nullable()
                ->constrained('museums')
                ->nullOnDelete();

            $table->dateTime('published_at')->nullable();
            $table->timestamps();

            $table->unique(['locale', 'slug']);
            $table->index('published_at');
        });

        DB::statement(
            "ALTER TABLE posts ADD CONSTRAINT chk_post_locale
             CHECK (locale IN ('vi', 'en'))"
        );

        // Biểu mẫu liên hệ
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
            $table->string('email', 180);

            $table->enum('topic', [
                'consult',
                'support',
                'ticket',
                'partnership',
                'other',
            ]);

            $table->text('message');

            $table->enum('status', [
                'new',
                'handled',
            ])->default('new');

            $table->foreignId('handled_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
            $table->index(['status', 'created_at']);
        });

        // Nhật ký hành động quan trọng trong hệ thống
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('actor_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('action', 60);
            $table->string('entity', 60);
            $table->unsignedBigInteger('entity_id')->nullable();

            $table->json('before_json')->nullable();
            $table->json('after_json')->nullable();

            $table->string('ip_address', 45)->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(
                ['entity', 'entity_id', 'created_at'],
                'idx_audit_entity'
            );
        });
    }

    public function down(): void
    {
        foreach ([
            'audit_logs',
            'contact_messages',
            'posts',
        ] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
