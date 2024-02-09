<?php

namespace Modules\Auth\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyUserEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(private readonly array $data, private readonly ?string $viewFile = null)
    {

    }

    public function build(): VerifyUserEmail
    {
        return $this->view($this->viewFile ?: 'auth::verify-email', ['data' => $this->data]);
    }
}
