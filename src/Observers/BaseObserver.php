<?php

namespace Outlandish\Wordpress\Eloquoowp\Observers;

use Outlandish\Wordpress\Eloquoowp\Models\Base;

class BaseObserver
{
    public function created(Base $instance)
    {
        $this->updated($instance);
    }

    public function updated(Base $instance)
    {
        $alreadyUpdated = current_action() === 'save_post';
        if (!$alreadyUpdated) {
            $post = $instance->findWordpressPost();
            if ($post) {
                $instance->updateWordPressPost($post);
            }
        }
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
