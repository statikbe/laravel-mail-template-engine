<?php

namespace Statikbe\LaravelMailEditor;

use Illuminate\Bus\Queueable;

class BaseMail extends AbstractBaseMail
{
    use Queueable;

    public function build() {
        $body = $this->generateBody($this->contentVars, $this->template, $this->locale);
        $subject = $this->generateSubject($this->contentVars, $this->template, $this->locale);

        $senderEmail = $this->getTranslation($this->template, 'sender_email', $this->locale) ?? config('mail.from.address');
        $senderName = $this->getTranslation($this->template, 'sender_name', $this->locale) ?? config('mail.from.name');

        $cc = $this->getCc();
        $bcc = $this->getBcc();
        $design = $this->template->design;
        $engine = $this->template->render_engine;

        $mail = $this
            ->from($senderEmail, $senderName)
            ->subject($subject)
            ->applyRenderEngine($engine, $design, $body)
            ->cc($cc)
            ->bcc($bcc);

        $this->attachMailAttachments($mail);

        return $mail;
    }
}
