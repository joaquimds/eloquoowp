<?php

use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Outlandish\Wordpress\Eloquoowp\EloquentManager;

/*
Plugin Name: Eloquent extension for OOWP
Plugin URI: https://github.com/outlandishideas/eloquoowp
Description: OOWP is a tool for WordPress theme developers that makes templating in WordPress more sensible. It replaces [The Loop](https://codex.wordpress.org/The_Loop) and contextless functions such as the_title() with object-oriented methods such as $event->title(), $event->parent() and $event->getConnected('people').
Version: 0.1
*/

if (!function_exists('init_eloquoowp')) {
    function init_eloquoowp($args = [])
    {
        $args['driver'] = 'mysql';
        $args['charset'] = 'utf8mb4';
        $args['collation'] = 'utf8mb4_general_ci';

        $capsule = new Capsule;

        $capsule->addConnection($args);
        $capsule->setEventDispatcher(new Dispatcher(new Container));
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
    }
}

if (class_exists('WP_CLI')) {
    try {
        WP_CLI::add_command('eloquent:migrate', function () { EloquentManager::migrate(); });
    } catch (\Exception $e) {
        error_log($e->getMessage());
    }
}
