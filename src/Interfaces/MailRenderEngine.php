<?php

namespace Statikbe\LaravelMailEditor\Interfaces;

use Illuminate\Mail\Mailable;

interface MailRenderEngine
{
    /**
     * Here you can define how and what the mailable should render
     *
     * @param \Illuminate\Mail\Mailable $mailable
     * @return \Illuminate\Mail\Mailable
     */
    public function __invoke(Mailable $mailable, $design, $body);
}