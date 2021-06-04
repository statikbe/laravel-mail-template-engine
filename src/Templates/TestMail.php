<?php

namespace Statikbe\LaravelMailEditor\Templates;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Statikbe\LaravelMailEditor\AbstractMail;

class TestMail extends AbstractMail
{
    public static function name(){
        return __('TestMailTemplate');
    }

    public static function getContentVariables(){
        return [
            'test' => __('Test variable'),
        ];
    }

    public static function getRecipientVariables(){
        return [
            'user' => __('User')
        ];
    }

    //Todo: AttachmentVariables same as the ones above
}
