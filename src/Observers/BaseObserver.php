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

    // Delete the WordPress Post if the deletion wasn't started from the WordPress Admin
    public function deleted(Base $instance)
    {
        $self = ($_SERVER && !empty($_SERVER['PHP_SELF'])) ? $_SERVER['PHP_SELF'] : null;

        switch ($self) {
            case '/wp/wp-admin/post.php':
                $action = ($_REQUEST && !empty($_REQUEST['action'])) ? $_REQUEST['action'] : null;
                $alreadyDeleting = $action === 'delete';
                break;
            case '/wp/wp-admin/edit.php':
                $alreadyDeleting = $_REQUEST && !empty($_REQUEST['delete_all']);
                break;
            default:
                $alreadyDeleting = false;
        }

        if (!$alreadyDeleting) {
            wp_delete_post($instance->id, true);
        }
    }
}
