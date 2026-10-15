
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    private function check(string $table, string $name, string $expr): void
    {
        DB::statement(
            "ALTER TABLE {$table} ADD CONSTRAINT {$name} CHECK ({$expr})"
        );
    }

    public function up(): void
    {
        Schema::create('artifacts', function (Blueprint $t) {
            $t->id();
            $t->foreignId('museum_id')
                ->constrained('museums')
                ->cascadeOnDelete();
            $t->string('code', 40)->unique();
            $t->string('category', 60);
            $t->string('period_label', 80)->nullable();
            $t->string('origin', 120)->nullable();
            $t->string('image_path', 255)->nullable();
            $t->string('source_note', 255)->nullable();
            $t->boolean('is_published')->default(true);
            $t->timestamps();
            $t->index(['museum_id', 'category']);
        });

        Schema::create('artifact_translations', function (Blueprint $t) {
            $t->id();
            $t->foreignId('artifact_id')
                ->constrained('artifacts')
                ->cascadeOnDelete();
            $t->char('locale', 2);
            $t->string('name', 200);
            $t->string('summary', 500)->nullable();
            $t->text('detail')->nullable();
            $t->timestamps();

            $t->unique(['artifact_id', 'locale']);

            $t->fullText(
                ['name', 'summary', 'detail'],
                'ft_artifact_text'
            );
        });

        $this->check(
            'artifact_translations',
            'chk_at_locale',
            "locale IN ('vi','en')"
        );

        Schema::create('hotspots', function (Blueprint $t) {
            $t->id();
            $t->foreignId('scene_id')
                ->constrained('scenes')
                ->cascadeOnDelete();
            $t->foreignId('artifact_id')
                ->constrained('artifacts')
                ->cascadeOnDelete();
            $t->decimal('yaw', 7, 3);
            $t->decimal('pitch', 7, 3);
            $t->string('icon', 30)->default('artifact');
            $t->unsignedSmallInteger('sort_order')->default(0);
            $t->timestamps();

            $t->unique(['scene_id', 'artifact_id']);
        });

        $this->check(
            'hotspots',
            'chk_hs_yaw',
            'yaw BETWEEN -180 AND 180'
        );

        $this->check(
            'hotspots',
            'chk_hs_pitch',
            'pitch BETWEEN -90 AND 90'
        );

        Schema::create('audio_tracks', function (Blueprint $t) {
            $t->id();
            $t->foreignId('artifact_id')
                ->nullable()
                ->constrained('artifacts')
                ->cascadeOnDelete();
            $t->foreignId('scene_id')
                ->nullable()
                ->constrained('scenes')
                ->cascadeOnDelete();
            $t->char('locale', 2);
            $t->string('file_path', 255);
            $t->unsignedSmallInteger('duration_sec')->nullable();
            $t->string('narrator', 120)->nullable();
            $t->text('transcript')->nullable();
            $t->timestamps();

            $t->unique(['artifact_id', 'locale']);
            $t->unique(['scene_id', 'locale']);
        });

        $this->check(
            'audio_tracks',
            'chk_au_locale',
            "locale IN ('vi','en')"
        );

        Schema::create('tour_routes', function (Blueprint $t) {
            $t->id();
            $t->string('slug', 80)->unique();
            $t->string('theme', 60);
            $t->unsignedSmallInteger('estimated_minutes');
            $t->boolean('is_published')->default(true);
            $t->unsignedSmallInteger('sort_order')->default(0);
            $t->timestamps();
        });

        Schema::create('tour_route_translations', function (Blueprint $t) {
            $t->id();
            $t->foreignId('route_id')
                ->constrained('tour_routes')
                ->cascadeOnDelete();
            $t->char('locale', 2);
            $t->string('title', 160);
            $t->text('description')->nullable();
            $t->timestamps();

            $t->unique(['route_id', 'locale']);
        });

        $this->check(
            'tour_route_translations',
            'chk_rt_locale',
            "locale IN ('vi','en')"
        );

        Schema::create('tour_route_steps', function (Blueprint $t) {
            $t->id();
            $t->foreignId('route_id')
                ->constrained('tour_routes')
                ->cascadeOnDelete();
            $t->unsignedTinyInteger('step_no');
            $t->foreignId('scene_id')
                ->constrained('scenes')
                ->cascadeOnDelete();
            $t->foreignId('artifact_id')
                ->nullable()
                ->constrained('artifacts')
                ->nullOnDelete();
            $t->unsignedSmallInteger('dwell_sec')->default(30);
            $t->decimal('focus_yaw', 7, 3)->nullable();
            $t->decimal('focus_pitch', 7, 3)->nullable();
            $t->timestamps();

            $t->unique(['route_id', 'step_no']);
        });
    }

    public function down(): void
    {
        foreach ([
            'tour_route_steps',
            'tour_route_translations',
            'tour_routes',
            'audio_tracks',
            'hotspots',
            'artifact_translations',
            'artifacts'
        ] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
