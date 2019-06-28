<?php

namespace Outlandish\Wordpress\Eloquoowp\Observers;

use Outlandish\Wordpress\Eloquoowp\Models\Base;

class BaseObserver
{
    public function created(Base $instance)
    {
        $instance->findOrCreateWordpressPost();
    }

    public function updated(Base $instance)
    {
        $post = $instance->findOrCreateWordpressPost();
        $instance->updateWordPressPost($post);
    }

    // Delete the WordPress Post if the deletion wasn't started by WordPress
    public function deleted(Base $instance)
    {
        $alreadyDeleting = current_action() === 'before_delete_post';
        if (!$alreadyDeleting) {
            wp_delete_post($instance->id, true);
        }
    }
}
