<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DatabaseStructureTest extends TestCase
{
    /**
     * Danh sách các bảng nghiệp vụ dự kiến của dự án.
     */
    private array $requiredTables = [
        'licenses',
        'museums',
        'museum_translations',
        'scenes',
        'scene_translations',
        'gateways',
        'artifacts',
        'artifact_translations',
        'hotspots',
        'audio_tracks',
        'tour_routes',
        'tour_route_translations',
        'tour_route_steps',
        'visit_sessions',
        'visit_events',
        'guestbook_entries',
        'collection_items',
        'ticket_plans',
        'orders',
        'order_items',
        'payments',
        'posts',
        'contact_messages',
        'audit_logs',
    ];

    public function test_required_business_tables_exist(): void
    {
        foreach ($this->requiredTables as $table) {
            $this->assertTrue(
                DB::getSchemaBuilder()->hasTable($table),
                "Thieu bang nghiep vu: {$table}"
            );
        }
    }

    public function test_user_table_has_role_fields(): void
    {
        $schema = DB::getSchemaBuilder();

        $this->assertTrue($schema->hasTable('users'));

        foreach (['role', 'status', 'locale'] as $column) {
            $this->assertTrue(
                $schema->hasColumn('users', $column),
                "Thieu cot users.{$column}"
            );
        }
    }

    public function test_artifact_tables_have_expected_columns(): void
    {
        $schema = DB::getSchemaBuilder();

        $this->assertTrue($schema->hasTable('artifacts'));
        $this->assertTrue($schema->hasTable('artifact_translations'));

        foreach (['museum_id', 'code'] as $column) {
            $this->assertTrue(
                $schema->hasColumn('artifacts', $column),
                "Thieu cot artifacts.{$column}"
            );
        }
    }

    public function test_visit_sessions_have_analytics_fields(): void
    {
        $schema = DB::getSchemaBuilder();

        $this->assertTrue($schema->hasTable('visit_sessions'));

        foreach ([
            'museum_id',
            'mode',
            'started_at',
            'completed',
            'is_simulated',
        ] as $column) {
            $this->assertTrue(
                $schema->hasColumn('visit_sessions', $column),
                "Thieu cot visit_sessions.{$column}"
            );
        }
    }
}
