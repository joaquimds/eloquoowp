<?php

namespace Outlandish\Wordpress\Eloquoowp\Models;

use Illuminate\Database\Eloquent\Model;
use Outlandish\Wordpress\Oowp\PostTypes\WordpressPost;

abstract class Base extends Model
{
    public $incrementing = false;

    public static function findOrCreate($postId)
    {
        $instance = static::find($postId);
        if (!$instance) {
            $instance = new static;
            $instance->id = $postId;
            $instance->save();
        }
        return $instance;
    }

    public static abstract function createTable();

    public function findOrCreateWordpressPost()
    {
        $post = WordpressPost::fetchById($this->id);
        if (!$post) {
            $post = $this->createWordpressPost();

        }
        return $post;
    }

    public abstract function createWordpressPost();

    public abstract function updateWordpressPost(WordpressPost $post);

    public abstract function updateFromPost(WordpressPost $post);

}
