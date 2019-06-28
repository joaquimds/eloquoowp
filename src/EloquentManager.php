<?php

namespace Outlandish\Wordpress\Eloquoowp;

use Outlandish\Wordpress\Eloquoowp\Observers\BaseObserver;

class EloquentManager
{
    public static function registerModels($models)
    {
        foreach ($models as $model) {
            $model::observe(BaseObserver::class);
        }
    }
}