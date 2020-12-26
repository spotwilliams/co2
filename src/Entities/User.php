<?php

namespace Co2Control\Entities;

use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword;
use Co2Control\Events\ForgotPasswordEvent;
use LaravelDoctrine\ORM\Auth\Authenticatable;

class User extends Entity implements AuthenticatableContract, CanResetPassword
{
    use Authenticatable;

    /** @var Person */
    private $whoIs;
    /** @var Email */
    protected $email;

    public function getEmailForPasswordReset()
    {
        return $this->getEmail()->getAddress();
    }

    public function sendPasswordResetNotification($token)
    {
        event(new ForgotPasswordEvent($this, $token));
    }

    public function isPasswordValid(string $plainPassword)
    {
        return password_verify($plainPassword, $this->password);
    }
}
