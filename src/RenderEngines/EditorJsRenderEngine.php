<?php

namespace Statikbe\LaravelMailEditor\RenderEngines;

use Illuminate\Mail\Mailable;
use Statikbe\LaravelMailEditor\Interfaces\RenderEngine;

class EditorJsRenderEngine implements RenderEngine
{
    /**
     * @param \Illuminate\Mail\Mailable $mailable
     * @return \Illuminate\Mail\Mailable
     */
    public function __invoke(Mailable $mailable, $design, $body)
    {
        $blocks = $this->buildBlocks($body);

        return $mailable
            ->view($design.'.editor-js')
            ->with([
                'blocks' => $blocks,
                'design' => $design,
            ]);
    }

    private function buildBlocks($body){
        #todo build blocks logic

        return $body['blocks'];
    }
}