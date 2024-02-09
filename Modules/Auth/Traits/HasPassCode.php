<?php

namespace Modules\Auth\Traits;

use Modules\Auth\Entities\PassCode;

trait HasPassCode
{
    public function passCodes()
    {
        return $this->hasMany(PassCode::class);
    }
}
