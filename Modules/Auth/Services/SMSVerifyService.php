<?php

namespace Modules\Auth\Services;

use Modules\Auth\Contracts\VerifyUser;
use Modules\Auth\Traits\SMSVerifiable;

class SMSVerifyService implements VerifyUser
{
    use SMSVerifiable;
}
