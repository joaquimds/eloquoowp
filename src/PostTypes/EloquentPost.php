<?php

namespace Outlandish\Wordpress\Eloquoowp\PostTypes;

use Outlandish\Wordpress\Eloquoowp\Models\Base;
use Outlandish\Wordpress\Oowp\PostTypes\WordpressPost;

abstract class EloquentPost extends WordpressPost
{
    public static abstract function getModel();

    public function onSave($postData)
    {
        /** @var Base $model */
        $model = static::getModel();
        $instance = $model::findOrCreate($this->ID);
        $instance->updateFromPost($this);
    }

    public function onDelete()
    {
        /** @var Base $model */
        $model = static::getModel();
        $model::destroy($this->ID);
    }
}
