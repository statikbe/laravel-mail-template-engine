<?php

namespace Statikbe\LaravelMailEditor\RenderEngines;

use Illuminate\Mail\Mailable;
use Statikbe\LaravelMailEditor\Interfaces\RenderEngine;

class HtmlRenderEngine implements RenderEngine
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