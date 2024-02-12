<?php

namespace Modules\Category\Helpers;

use Modules\Role\Database\Seeders\PermissionTableSeeder;

class CategoryHelper extends PermissionTableSeeder
{
    public function permissions(): array
    {
        return [
            'category' => $this->excludeOperations(),
        ];
    }
}
