<?php

namespace Statikbe\LaravelMailEditor;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

abstract class AbstractMail
{
    protected static $name;

    protected $queue;

    /**
     * Provide the fillable variables used in the content of this mail.
     *
     * @return array
     */
    abstract public static function getContentVariables();

    /**
     * Provide the fillable variables used as the recipients of this mail.
     *
     * @return array
     */
    abstract public static function getRecipientVariables();

    /**
     *
     * @param $contentVars
     * @param $recipientVars
     * @param array $attachments
     */
    public function sendMail($contentVars, $recipientVars, $attachments = [])
    {
        $this->dispatchMailTemplates($contentVars, $recipientVars, $attachments);
    }

    public function renderMail($contentVars, $recipientVars, $attachments = [])
    {
        return implode('<br><br>', $this->dispatchMailTemplates($contentVars, $recipientVars, $attachments, true));
    }

    public function dispatchMailTemplates($contentVars, $recipientVars, $attachments = [], $render = false)
    {
        $mails = [];
        $mailTemplates = $this->getMailTemplates();
        $recipientVarsFormatted = [];
        foreach ($mailTemplates as $template) {
            $templateRecipients = $template->recipients ?? [];
            $cc = $template->cc ?? [];
            $bcc = $template->bcc ?? [];
            $allRecipients = array_unique(array_merge($templateRecipients, $cc, $bcc), SORT_REGULAR);
            //Format all templates recipientVariables into clean array (recipients, cc & bcc)
            foreach ($allRecipients as $recipient) {
                if (strpos($recipient, '@') == false) {
                    $recipientDataArray = $recipientVars[$recipient] ?? [];
                    $recipientDataArrayFormatted = $this->formatRecipientArray($recipientDataArray);
                    $recipientVarsFormatted[$template->id][$recipient] = $recipientDataArrayFormatted;
                }
            }

            //Send template mail to recipients
            foreach ($templateRecipients as $templateRecipient) {
                $recipientArray = $recipientVarsFormatted[$template->id][$templateRecipient] ?? null;
                if ($recipientArray){
                    foreach ($recipientArray as $recipient) {
                        $recipientMail = $recipient['mail'];
                        $locale = $recipient['locale'];
                        $attachmentArray = $this->getAttachmentArray($attachments, $locale, $templateRecipient);

                        $mails[] = $this->buildMail($recipientMail, $contentVars, $recipientVarsFormatted, $template, $locale, $attachmentArray, $render);
                    }
                } else {
                    //Using raw email value from template recipient array
                    $locale = app()->getLocale();
                    $attachmentArray = $this->getAttachmentArray($attachments, $locale, $templateRecipient);
                    $mails[] = $this->buildMail($templateRecipient, $contentVars, $recipientVarsFormatted, $template, $locale, $attachmentArray, $render);
                }
            }
        }
        return $mails;
    }

    private function buildMail($recipientMail, $contentVars, $recipientVars, $template, $locale, $attachments, $render = false)
    {
        $baseMail = config('mail-template-engine.base_mail');
        $mail = (new $baseMail($recipientMail, $contentVars, $recipientVars, $template, $locale, $attachments));

        if ($render) {
            return $mail->render();
        }

        if ($this->queue) {
            $mail->onQueue($this->queue);
        }
        if (config('mail-template-engine.debug_mail')) {
            $recipientMail = config('mail-template-engine.debug_mail');
        }

        //Mail::to($recipientMail)->send($mail);
        Mail::to($recipientMail)->queue($mail);
    }

    /**
     * Formats all possible inputs to following array format:
     *  [
     *      [
     *          'mail' => 'robbe@statik.be',
     *          'locale' => 'nl'
     *      ],
     *  ]
     * */
    private function formatRecipientArray($recipientData)
    {
        $recipientArray = $this->validateRecipientArray($recipientData);

        if (is_array($recipientArray)) {
            if (array_key_exists('locale', $recipientArray) && array_key_exists('mail', $recipientArray)) {
                // Array of elements should be mapped in an array
                $recipientArray = [$recipientArray];
            }
        }

        return $recipientArray;
    }

    private function validateRecipientArray($recipientData, $locale = null)
    {
        if (!$recipientData || empty($recipientData)) {
            return [];
        }

        $recipientDataArrayFormatted = [];
        if ($recipientData instanceof Collection) {
            foreach ($recipientData as $recipient) {
                $recipientDataArrayFormatted[] = $this->validateRecipientArray($recipient);
            }

            return $recipientDataArrayFormatted;
        }

        if (is_array($recipientData)) {
            if (array_key_exists('locale', $recipientData)) {
                $locale = $recipientData['locale'];
            }

            if (array_key_exists('mail', $recipientData)) {
                return $this->validateRecipientArray($recipientData['mail'], $locale);
            }

            if (array_key_exists('email', $recipientData)) {
                return $this->validateRecipientArray($recipientData['email'], $locale);
            }

            foreach ($recipientData as $recipient) {
                $recipientDataArrayFormatted[] = $this->validateRecipientArray($recipient, $locale);
            }

            return $recipientDataArrayFormatted;
        }

        if (is_object($recipientData)) {
            if (!$recipientData->email && !$recipientData->mail) {
                throw new \Exception('Recipient object is missing an email property or value');
            }

            return $this->createRecipientData($recipientData->email ?? $recipientData->mail, $locale ?? $recipientData->locale);
        }

        if (is_string($recipientData)) {
            return $this->createRecipientData($recipientData, $locale);
        }

        return $recipientDataArrayFormatted;
    }

    private function createRecipientData($mail, $locale)
    {
        return [
            'mail' => $mail,
            'locale' => $locale ?? app()->getLocale(),
        ];
    }

    public static function name()
    {
        return static::$name ?: static::parsedClassName();
    }

    private static function parsedClassName()
    {
        return Str::title(Str::snake(class_basename(get_called_class()), ' '));
    }

    /**
     * @return mixed
     */
    public function getQueue()
    {
        return $this->queue;
    }

    /**
     * @param mixed $queue
     */
    public function setQueue($queue): void
    {
        $this->queue = $queue;
    }

    private function getMailTemplates()
    {
        $templateKey = array_search(static::class, config('mail-template-engine.mails'));

        return MailTemplate::where('mail_class', $templateKey)->get();
    }

    private function getAttachmentArray(array $attachments, $locale, $templateRecipient)
    {
        return $attachments[$locale][$templateRecipient] ?? $attachments[$templateRecipient][$locale] ?? $attachments[$locale] ?? $attachments[$templateRecipient] ?? $attachments;
    }
}
