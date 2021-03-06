<?php

use LaravelEnso\Migrator\app\Database\Migration;

class CreateStructureForAvatars extends Migration
{
    protected $permissions = [
        ['name' => 'core.avatars.update', 'description' => 'Update avatar', 'type' => 1, 'is_default' => true],
        ['name' => 'core.avatars.show', 'description' => 'Display selected avatar', 'type' => 0, 'is_default' => true],
        ['name' => 'core.avatars.store', 'description' => 'Upload a new avatar', 'type' => 1, 'is_default' => true],
    ];
}
