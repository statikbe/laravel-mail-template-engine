<?php

namespace Statikbe\LaravelMailEditor\Mails;

use Statikbe\LaravelMailEditor\AbstractMail;

class ResetPassword extends AbstractMail
{
    public static function name(){
        return __('ResetPasswordTemplate');
    }

    public static function getContentVariables(){
        return [
            'url' => __('Reset password URL'),
        ];
    }

    public static function getRecipientVariables(){
        return [
            'user' => __('User')
        ];
    }
}
