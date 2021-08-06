<?php

namespace Statikbe\LaravelMailEditor;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class BaseMail extends Mailable
{
    use Queueable;

    private $recipientMail;

    /**
     * @var array
     */
    private $contentVars;

    /**
     * @var array
     */
    private $recipientVars;

    /**
     * @var \Statikbe\LaravelMailEditor\MailTemplate
     */
    private $template;

    /**
     * @var string
     */
    public $locale;

    private $mailAttachments;

    public function __construct($recipientMail, array $contentVars, array $recipientVars, MailTemplate $template, $locale, $mailAttachments)
    {
        $this->recipientMail = $recipientMail;
        $this->contentVars = $contentVars;
        $this->recipientVars = $recipientVars;
        $this->template = $template;
        $this->locale = $locale;
        $this->mailAttachments = $mailAttachments;
    }

    public function build() {
        $body = $this->generateBody($this->contentVars, $this->template, $this->locale);
        $subject = $this->generateSubject($this->contentVars, $this->template, $this->locale);

        if(config('app.debug')) {
            $subject .= ' (Debug mail to '.$this->recipientMail.')';
        }

        $senderEmail = $this->getTranslation($this->template, 'sender_email', $this->locale) ?? config('mail.from.address');
        $senderName = $this->getTranslation($this->template, 'sender_name', $this->locale);

        $debugEmail = config('mail-template-engine.debug_mail');
        $cc = $this->getCc($this->template, $this->recipientVars);
        if(config('app.debug') && !empty($cc)) {
            $cc = $debugEmail;
        }
        $bcc = $this->getBcc($this->template, $this->recipientVars);
        if(config('app.debug') && !empty($bcc)) {
            $bcc = $debugEmail;
        }
        $design = $this->template->design;
        $engine = $this->template->render_engine;

        $mail = $this
            ->from($senderEmail, $senderName)
            ->subject($subject)
            ->applyRenderEngine($engine, $design, $body)
            ->cc($cc)
            ->bcc($bcc);

        foreach($this->mailAttachments as $attachment) {
            if (is_array($attachment)){
                [$path, $details] = array_values($attachment);
                $mail->attach($path , $details);
                continue;
            }

            $mail->attach($attachment);
        }

        return $mail;
    }


    public function generateText($table, $content, MailTemplate $template, $locale = null)
    {
        $content = $content[$locale] ?? $content;

        $body = $this->getTranslation($template, $table, $locale);

        foreach ($content as $index => $var) {
            $searchWord = sprintf('[[%s]]', $index);
            $body = str_ireplace($searchWord, $var, $body, $count);
        }

        return $body;
    }

    public function generateBody($content, MailTemplate $template, $locale = null)
    {
        return $this->generateText('body', $content, $template, $locale);
    }

    public function generateSubject($content, MailTemplate $template, $locale = null)
    {
        return $this->generateText('subject', $content, $template, $locale);
    }

    private function getTranslation($template, $column, $locale)
    {
        return $template->getTranslation($column, $locale);
    }

    private function getCc($template, $recipientVars)
    {
        $ccArray = [];
        foreach ((array) $template->cc as $cc) {
            $cc = $recipientVars[$template->id][$cc] ?? $cc;
            if ($cc && !is_array($cc) && strpos($cc, '@') !== false) {
                $ccArray[] = $cc;
            }else{
                foreach($cc as $c) {
                    $ccArray[] = $c['mail'];
                }
            }
        }

        return $ccArray;
    }

    private function getBcc($template, $recipientVars)
    {
        $bccArray = [];
        foreach ((array) $template->bcc as $bcc) {
            $bcc = $recipientVars[$template->id][$bcc] ?? $bcc;
            if ($bcc && !is_array($bcc) && strpos($bcc, '@') !== false) {
                $bccArray[] = $bcc;
            }else{
                foreach($bcc as $c) {
                    $bccArray[] = $c['mail'];
                }
            }
        }

        return $bccArray;
    }

    private function applyRenderEngine($engine, $design, $body){
        $renderEngines = config('mail-template-engine.render_engines');
        /** @var \Statikbe\LaravelMailEditor\Interfaces\RenderEngine $renderEngine */
        $renderEngine = new $renderEngines[$engine];

        return $renderEngine($this, $design, $body);
    }
}
