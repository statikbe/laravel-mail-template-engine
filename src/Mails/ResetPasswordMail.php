<?php

namespace Statikbe\LaravelMailEditor\Mails;

use Statikbe\LaravelMailEditor\AbstractMail;

class ResetPasswordMail extends AbstractMail
{
    public static function name(){
        return __('ResetPasswordTemplate');
    }

    /**
     * This array lists the possible variable data
     * included in the content of the mail.
     *
     * @return array
     */
    public static function getContentVariables(){
        return [
            'url' => __('Reset password URL'),
        ];
    }

    /**
     * This array lists the possible variable
     * recipients that can receive this mail.
     *
     * @return array
     */
    public static function getRecipientVariables(){
        return [
            'user' => __('User'),
        ];
    }
}
