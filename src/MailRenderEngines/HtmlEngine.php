<?php

namespace Statikbe\LaravelMailEditor\MailRenderEngines;

use Illuminate\Mail\Mailable;
use Statikbe\LaravelMailEditor\Interfaces\MailRenderEngine;

class HtmlEngine implements MailRenderEngine
{
    /**
     * @param \Illuminate\Mail\Mailable $mailable
     * @return \Illuminate\Mail\Mailable
     */
    public function __invoke(Mailable $mailable, $design, $body)
    {
        return $mailable->view($design.'.html')
        ->with([
            'content' => $body,
            'design' => $design,
        ]);
    }
}