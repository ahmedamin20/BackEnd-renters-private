<?php

namespace Modules\Auth\Contracts;

use App\Models\User;

interface VerifyUser
{
    /**
     * @return mixed
     */
    public function sendMessage(string $receiver, string $body);

    /**
     * @return mixed
     */
    public function resendVerifyUser(User|string $receiver);

    /**
     * @return mixed
     */
    public function verify(string $handle, string $code, User $user = null);

    public function shouldVerifyUser(User|int $user): bool|array;

    /**
     * @return mixed
     */
    public function makeUserVerified(User|int $user);

    public function isMessageSent(string $returnedResponse): bool;

    public function isUserVerified(User $user = null): bool;
}
