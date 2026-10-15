<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private function addCheck(
        string $table,
        string $name,
        string $expression
    ): void {
        DB::statement(
            "ALTER TABLE {$table} ADD CONSTRAINT {$name} CHECK ({$expression})"
        );
    }

    public function up(): void
    {
        // Phiên tham quan bảo tàng
        Schema::create('visit_sessions', function (Blueprint $table) {
            $table->id();
            $table->uuid('public_id')->unique();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('museum_id')
                ->constrained('museums')
                ->cascadeOnDelete();

            $table->enum('mode', ['free', 'guided']);
            $table->foreignId('route_id')
                ->nullable()
                ->constrained('tour_routes')
                ->nullOnDelete();

            $table->char('locale', 2);
            $table->enum('device', ['desktop', 'mobile', 'tablet']);

            $table->dateTime('started_at');
            $table->dateTime('ended_at')->nullable();

            $table->unsignedSmallInteger('scenes_viewed')->default(0);
            $table->boolean('completed')->default(false);
            $table->boolean('is_simulated')->default(false);

            $table->index(
                ['museum_id', 'started_at'],
                'idx_vs_museum_started'
            );

            $table->index('started_at', 'idx_vs_started');

            $table->index(
                ['mode', 'completed'],
                'idx_vs_mode_completed'
            );

            $table->index(
                ['user_id', 'started_at'],
                'idx_vs_user_started'
            );
        });

        $this->addCheck(
            'visit_sessions',
            'chk_vs_locale',
            "locale IN ('vi', 'en')"
        );

        $this->addCheck(
            'visit_sessions',
            'chk_vs_time',
            'ended_at IS NULL OR ended_at >= started_at'
        );

        // Nhật ký sự kiện khi người dùng tham quan
        Schema::create('visit_events', function (Blueprint $table) {
            $table->id();

            $table->foreignId('session_id')
                ->constrained('visit_sessions')
                ->cascadeOnDelete();

            $table->enum('event_type', [
                'scene_view',
                'gateway_click',
                'hotspot_open',
                'audio_play',
                'audio_finish',
                'route_step',
                'exit',
            ]);

            $table->foreignId('scene_id')
                ->nullable()
                ->constrained('scenes')
                ->nullOnDelete();

            $table->foreignId('hotspot_id')
                ->nullable()
                ->constrained('hotspots')
                ->nullOnDelete();

            $table->foreignId('audio_track_id')
                ->nullable()
                ->constrained('audio_tracks')
                ->nullOnDelete();

            $table->unsignedInteger('duration_ms')->nullable();
            $table->dateTime('occurred_at', 3);

            $table->index(
                ['session_id', 'occurred_at'],
                'idx_ev_session_time'
            );

            $table->index(
                ['scene_id', 'occurred_at'],
                'idx_ev_scene_time'
            );

            $table->index(
                ['event_type', 'occurred_at'],
                'idx_ev_type_time'
            );
        });

        // Sổ lưu bút
        Schema::create('guestbook_entries', function (Blueprint $table) {
            $table->id();

            $table->foreignId('museum_id')
                ->constrained('museums')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('display_name', 80);
            $table->unsignedTinyInteger('rating')->nullable();
            $table->string('message', 1000);
            $table->char('locale', 2);

            $table->enum('status', [
                'pending',
                'approved',
                'hidden',
            ])->default('pending');

            $table->dateTime('moderated_at')->nullable();
            $table->boolean('is_simulated')->default(false);
            $table->timestamps();

            $table->index(
                ['museum_id', 'status', 'created_at'],
                'idx_gb_museum_status'
            );
        });

        $this->addCheck(
            'guestbook_entries',
            'chk_gb_rating',
            'rating IS NULL OR rating BETWEEN 1 AND 5'
        );

        $this->addCheck(
            'guestbook_entries',
            'chk_gb_locale',
            "locale IN ('vi', 'en')"
        );

        // Hiện vật người dùng lưu vào bộ sưu tập cá nhân
        Schema::create('collection_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('artifact_id')
                ->constrained('artifacts')
                ->cascadeOnDelete();

            $table->string('note', 255)->nullable();
            $table->timestamp('saved_at')->useCurrent();

            $table->unique(['user_id', 'artifact_id']);
        });
    }

    public function down(): void
    {
        foreach ([
            'collection_items',
            'guestbook_entries',
            'visit_events',
            'visit_sessions',
        ] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
