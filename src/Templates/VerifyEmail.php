<?php

namespace Statikbe\LaravelMailEditor\Templates;

use Statikbe\LaravelMailEditor\AbstractMail;

class VerifyEmail extends AbstractMail
{
    public static function name(){
        return __('VerifyEmailTemplate');
    }

    public static function getContentVariables(){
        return [
            'url' => __('Verification URL'),
        ];
    }

    public static function getRecipientVariables(){
        return [
            'user' => __('User')
        ];
    }

    //Todo: AttachmentVariables same as the ones above
}
