<?php

namespace Outlandish\Wordpress\Eloquoowp;

use Illuminate\Database\Capsule\Manager;
use Outlandish\Wordpress\Eloquoowp\Migrations\Migration;
use Outlandish\Wordpress\Eloquoowp\Observers\BaseObserver;

class EloquentManager
{
    static $migrations = [];

    public static function registerModels($models)
    {
        foreach ($models as $model) {
            $model::observe(BaseObserver::class);
        }
    }

    public static function registerMigrations($migrations)
    {
        foreach ($migrations as $migration) {
            static::$migrations[] = $migration;
        }
    }

    public static function migrateUp()
    {
        static::ensureMigrationsTable();
        foreach (static::$migrations as $migration) {
            /** @var Migration $instance */
            $instance = new $migration;
            if (!$instance->hasRun()) {
                \WP_CLI::line('Migrating ' . $instance->getName());
                $instance->up();
                $instance->save();
            } else {
                \WP_CLI::line('Skipping ' . $instance->getName());
            }
        }
    }

    public static function migrateDown()
    {
        static::ensureMigrationsTable();
        foreach (static::$migrations as $migration) {
            /** @var Migration $instance */
            $instance = new $migration;
            if ($instance->hasRun()) {
                \WP_CLI::line('Rollback ' . $instance->getName());
                $instance->down();
                $instance->remove();
            } else {
                \WP_CLI::line('Skipping ' . $instance->getName());
            }
        }
    }

    private static function ensureMigrationsTable() {
        $schema = Manager::schema();
        if ($schema->hasTable('migrations')) {
            return;
        }
        $schema->create('migrations', function ($table) {
            $table->integer('id')->autoIncrement();
            $table->string('name')->unique();
        });
    }
}