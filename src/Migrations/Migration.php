<?php

namespace Outlandish\Wordpress\Eloquoowp\Migrations;

use Illuminate\Database\Capsule\Manager;

abstract class Migration
{
    public abstract function getName();

    public abstract function up();

    public abstract function down();

    public function hasRun()
    {
        $name = $this->getName();
        $results = Manager::table('migrations')->where(['name' => $name])->get();
        if (count($results)) {
            return true;
        }
        return false;
    }

    public function save()
    {
        $name = $this->getName();
        Manager::table('migrations')->insert(['name' => $name]);
    }

    public function remove()
    {
        $name = $this->getName();
        Manager::table('migrations')->where(['name' => $name])->delete();
    }
}