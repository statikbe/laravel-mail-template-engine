<?php

namespace Statikbe\LaravelMailEditor\Mails;

use Statikbe\LaravelMailEditor\AbstractMail;

class VerifyEmailMail extends AbstractMail
{
    public static function name(){
        return __('VerifyEmailTemplate');
    }

    public static function getContentVariables(){
        return [
            'name' => __('Name'),
            'url' => __('Verification URL'),
        ];
    }

    public static function getRecipientVariables(){
        return [
            'user' => __('User')
        ];
    }
}
