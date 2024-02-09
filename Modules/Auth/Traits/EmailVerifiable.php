<?php

namespace Modules\Auth\Traits;

trait EmailVerifiable
{
    public function resend(string $handle)
    {

    }

    public function verify()
    {
    }

    public function shouldVerifyUser()
    {
        return true;
    }
}
