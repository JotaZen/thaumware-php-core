<?php

namespace Thaumware\Core\Helpers\Migrations;

use Illuminate\Database\Schema\Blueprint;

class MigrationHelper
{
    public static function loadEloquentDateTimes(Blueprint $table, bool $softDeletes = true): void
    {
        $table->dateTime('created_at')->nullable();
        $table->dateTime('updated_at')->nullable();

        if ($softDeletes) {
            $table->dateTime('deleted_at')->nullable();
        }
    }
}