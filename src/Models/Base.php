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

    public function findWordpressPost()
    {
        return WordpressPost::fetchById($this->id);
    }

    public function createWordpressPost()
    {
        $args = $this->getPostArray();
        $args = wp_parse_args($args, ['post_status' => 'publish']);
        return wp_insert_post($args);
    }

    public function save(array $options = [])
    {
        if ($this->id) {
            return parent::save($options);
        }
        $id = $this->createWordpressPost();
        /** @var Base $instance */
        $instance = static::find($id);
        if (!$instance) {
            return parent::save($options);
        }
        $attributes = $this->getAttributes();
        foreach ($attributes as $attribute => $value) {
            $instance->setAttribute($attribute, $value);
        }
        return $instance->save();
    }

    protected abstract function getPostArray();

    public abstract function updateWordpressPost(WordpressPost $post);

    public abstract function updateFromPost(WordpressPost $post);

}
