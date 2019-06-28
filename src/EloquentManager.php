<?php

namespace Outlandish\Wordpress\Eloquoowp;

use Outlandish\Wordpress\Eloquoowp\Observers\BaseObserver;

class EloquentManager
{
    static $models = [];

    public static function registerModels($models)
    {
        foreach ($models as $model) {
            $model::observe(BaseObserver::class);
        }
        static::$models[] = $model;
    }

    public static function migrate()
    {
        foreach (static::$models as $model) {
            $model::createTable();
        }
        return 0;
    }
}