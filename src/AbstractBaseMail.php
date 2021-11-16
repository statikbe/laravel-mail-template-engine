<?php

namespace Statikbe\LaravelMailEditor;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

abstract class AbstractBaseMail extends Mailable
{
    /**
     * @var \Statikbe\LaravelMailEditor\MailTemplate
     */
    public $template;

    public $locale;

    /**
     * @var string
     */
    protected $recipientMail;

    /**
     * @var array
     */
    protected $contentVars;

    /**
     * @var array
     */
    protected $recipientVars;

    /**
     * @var array
     */
    protected $mailAttachments;

    /**
     * @var string
     */
    protected $debugMail;

    public function __construct($recipientMail, array $contentVars, array $recipientVars, MailTemplate $template, $locale, $mailAttachments)
    {
        $this->recipientMail = $recipientMail;
        $this->contentVars = $contentVars;
        $this->recipientVars = $recipientVars;
        $this->template = $template;
        $this->locale = $locale;
        $this->mailAttachments = $mailAttachments;

        $this->generateDebugMail();
    }

    abstract public function build();

    protected function generateText($table, $content, MailTemplate $template, $locale = null)
    {
        $content = $content[$locale] ?? $content;

        $body = $this->getTranslation($template, $table, $locale);

        foreach ($content as $index => $var) {
            $searchWord = sprintf('[[%s]]', $index);
            $body = str_ireplace($searchWord, $var, $body, $count);
        }

        return $body;
    }

    protected function generateBody($content, MailTemplate $template, $locale = null)
    {
        $body = $this->generateText('body', $content, $template, $locale);

        if (empty($body)) {
            Log::alert("The body for template \"{$this->template->name}\" (id: {$this->template->id}) is empty. Using locale {$this->locale}.");
        }

        return $body;
    }

    protected function generateSubject($content, MailTemplate $template, $locale = null)
    {
        $subject = $this->generateText('subject', $content, $template, $locale);

        if (empty($subject)) {
            Log::alert("The subject for template \"{$this->template->name}\" (id: {$this->template->id}) is empty. Using locale {$this->locale}.");
        }

        if (!empty($this->debugMail)) {
            $subject .= ' (Debug mail to '.$this->recipientMail.')';
        }

        return $subject;
    }

    protected function getTranslation($template, $column, $locale)
    {
        return $template->getTranslation($column, $locale);
    }

    protected function getCc()
    {
        if ($this->debugMail) {
            return $this->debugMail;
        }

        $ccArray = [];
        foreach ((array) $this->template->cc as $cc) {
            $this->getMailsFromCopy($cc);
        }

        return $ccArray;
    }

    protected function getBcc()
    {
        if ($this->debugMail) {
            return $this->debugMail;
        }

        $bccArray = [];
        foreach ((array) $this->template->bcc as $bcc) {
            $this->getMailsFromCopy($bcc);
        }

        return $bccArray;
    }

    protected function applyRenderEngine($engine, $design, $body)
    {
        $renderEngines = config('mail-template-engine.render_engines');
        /** @var \Statikbe\LaravelMailEditor\Interfaces\MailRenderEngine $renderEngine */
        $renderEngine = resolve($renderEngines[$engine]);

        return $renderEngine($this, $design, $body);
    }

    protected function generateDebugMail()
    {
        $debugEmail = config('mail-template-engine.debug_mail');

        $this->debugMail = $debugEmail;
    }

    protected function getMailsFromCopy($copy)
    {
        $copy = $this->recipientVars[$this->template->id][$copy] ?? $copy;
        if ($copy && !is_array($copy) && strpos($copy, '@') !== false) {
            return [$copy];
        }

        $mailArray = [];

        foreach ($copy as $mail) {
            $mailArray[] = $mail['mail'];
        }

        return $mailArray;
    }

    /**
     * @param Mailable $mail
     */
    protected function attachMailAttachments(&$mail)
    {
        //Attachment added to the mail class
        foreach ($this->mailAttachments as $attachment) {
            if (is_array($attachment)) {
                [$path, $details] = array_values($attachment);
                $mail->attach($path, $details);
                continue;
            }

            $mail->attach($attachment);
        }

        //Attachment added to the mail template
        $attachments = $this->template->getTranslation('attachments', $this->locale);
        if (is_array($attachments)) {
            foreach ($attachments ?? [] as $attachment) {
                $mail->attachFromStorage($attachment);
            }
        }
    }
}
