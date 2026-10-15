
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
        Schema::create('licenses', function (Blueprint $t) {
            $t->id();
            $t->string('code', 30)->unique();
            $t->string('name', 120);
            $t->string('url', 255);
            $t->boolean('requires_attribution')->default(true);
            $t->boolean('share_alike')->default(true);
            $t->timestamps();
        });

        Schema::create('museums', function (Blueprint $t) {
            $t->id();
            $t->string('slug', 80)->unique();
            $t->char('country_code', 2);
            $t->string('city', 80)->nullable();
            $t->decimal('lat', 10, 7)->nullable();
            $t->decimal('lng', 10, 7)->nullable();
            $t->foreignId('license_id')->constrained('licenses');
            $t->string('author_credit', 255);
            $t->string('source_url', 500)->nullable();
            $t->string('source_note', 255)->nullable();
            $t->boolean('is_published')->default(true);
            $t->unsignedSmallInteger('sort_order')->default(0);
            $t->timestamps();
            $t->index(['is_published', 'sort_order']);
        });

        $this->check(
            'museums',
            'chk_museum_lat',
            'lat IS NULL OR lat BETWEEN -90 AND 90'
        );

        $this->check(
            'museums',
            'chk_museum_lng',
            'lng IS NULL OR lng BETWEEN -180 AND 180'
        );

        Schema::create('museum_translations', function (Blueprint $t) {
            $t->id();
            $t->foreignId('museum_id')
                ->constrained('museums')
                ->cascadeOnDelete();
            $t->char('locale', 2);
            $t->string('name', 160);
            $t->string('tagline', 255)->nullable();
            $t->text('description')->nullable();
            $t->timestamps();
            $t->unique(['museum_id', 'locale']);
        });

        $this->check(
            'museum_translations',
            'chk_mt_locale',
            "locale IN ('vi','en')"
        );

        Schema::create('scenes', function (Blueprint $t) {
            $t->id();
            $t->foreignId('museum_id')
                ->constrained('museums')
                ->cascadeOnDelete();
            $t->string('slug', 80);
            $t->string('tile_path', 255)->nullable();
            $t->string('preview_path', 255)->nullable();

            $t->enum('panorama_type', [
                'equirectangular',
                'cubemap',
                'multires'
            ])->default('multires');

            $t->unsignedInteger('width_px')->nullable();
            $t->unsignedInteger('height_px')->nullable();
            $t->unsignedInteger('file_size_kb')->nullable();

            $t->decimal('initial_yaw', 7, 3)->default(0);
            $t->decimal('initial_pitch', 7, 3)->default(0);
            $t->decimal('initial_hfov', 6, 2)->default(100);

            $t->string('floor_label', 20)->nullable();
            $t->decimal('map_x', 5, 2)->nullable();
            $t->decimal('map_y', 5, 2)->nullable();

            $t->unsignedSmallInteger('sort_order')->default(0);
            $t->boolean('is_published')->default(true);
            $t->timestamps();

            $t->unique(['museum_id', 'slug']);
            $t->index(['museum_id', 'sort_order']);
        });

        $this->check(
            'scenes',
            'chk_scene_yaw',
            'initial_yaw BETWEEN -180 AND 180'
        );

        $this->check(
            'scenes',
            'chk_scene_pitch',
            'initial_pitch BETWEEN -90 AND 90'
        );

        $this->check(
            'scenes',
            'chk_scene_hfov',
            'initial_hfov BETWEEN 30 AND 120'
        );

        $this->check(
            'scenes',
            'chk_scene_map',
            '(map_x IS NULL OR map_x BETWEEN 0 AND 100) ' .
            'AND (map_y IS NULL OR map_y BETWEEN 0 AND 100)'
        );

        Schema::create('scene_translations', function (Blueprint $t) {
            $t->id();
            $t->foreignId('scene_id')
                ->constrained('scenes')
                ->cascadeOnDelete();
            $t->char('locale', 2);
            $t->string('title', 160);
            $t->text('description')->nullable();
            $t->timestamps();
            $t->unique(['scene_id', 'locale']);
        });

        $this->check(
            'scene_translations',
            'chk_st_locale',
            "locale IN ('vi','en')"
        );

        Schema::create('gateways', function (Blueprint $t) {
            $t->id();
            $t->foreignId('from_scene_id')
                ->constrained('scenes')
                ->cascadeOnDelete();
            $t->foreignId('to_scene_id')
                ->constrained('scenes')
                ->cascadeOnDelete();

            $t->decimal('yaw', 7, 3);
            $t->decimal('pitch', 7, 3)->default(0);
            $t->decimal('target_yaw', 7, 3)->nullable();
            $t->unsignedSmallInteger('transition_ms')->default(800);
            $t->timestamps();

            $t->unique(['from_scene_id', 'to_scene_id', 'yaw']);
        });

        $this->check(
            'gateways',
            'chk_gw_yaw',
            'yaw BETWEEN -180 AND 180'
        );

        $this->check(
            'gateways',
            'chk_gw_pitch',
            'pitch BETWEEN -90 AND 90'
        );

        Schema::table('users', function (Blueprint $t) {
            $t->enum('role', [
                'admin',
                'manager',
                'partner',
                'user'
            ])->default('user')->after('password');

            $t->enum('status', [
                'active',
                'locked'
            ])->default('active')->after('role');

            $t->char('locale', 2)->default('vi')->after('status');

            $t->foreignId('museum_id')
                ->nullable()
                ->after('locale')
                ->constrained('museums')
                ->nullOnDelete();

            $t->timestamp('last_login_at')->nullable()->after('museum_id');
            $t->index(['role', 'status']);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $t) {
            $t->dropIndex(['role', 'status']);
            $t->dropConstrainedForeignId('museum_id');
            $t->dropColumn([
                'role',
                'status',
                'locale',
                'last_login_at'
            ]);
        });

        foreach ([
            'gateways',
            'scene_translations',
            'scenes',
            'museum_translations',
            'museums',
            'licenses'
        ] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
