<?php

namespace Statikbe\LaravelMailEditor;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AbstractMail
{
    protected static $name;

    private $queue;

    /**
     *
     * @param $contentVars
     * @param $recipientVars
     * @param array $attachments
     */
    public function sendMail($contentVars, $recipientVars, $attachments = [])
    {
        $mailTemplates = $this->getMailTemplates();
        $recipientVarsFormatted = [];
        foreach ($mailTemplates as $template) {
            $templateRecipients = $template->recipients ?? [];
            $cc = $template->cc ?? [];
            $bcc = $template->bcc ?? [];
            $allRecipients = array_unique(array_merge($templateRecipients,$cc,$bcc), SORT_REGULAR);
            //Format all templates recipientVariables into clean array (recipients, cc & bcc)
            foreach ($allRecipients as $recipient) {
                if(strpos($recipient, '@') == false) {
                    $recipientDataArray = $recipientVars[$recipient] ?? [];
                    $recipientDataArrayFormatted = $this->formatData($recipientDataArray);
                    $recipientVarsFormatted[$template->id][$recipient] = $recipientDataArrayFormatted;
                }
            }
            //Send template mail to recipients
            foreach ($templateRecipients as $templateRecipient) {
                $recipientArray = $recipientVarsFormatted[$template->id][$templateRecipient];
                foreach($recipientArray as $recipient) {
                    $this->buildMail($recipient['mail'], $contentVars, $recipientVarsFormatted, null, $template, $recipient['locale'], $attachments[$templateRecipient] ?? []);
                }
            }
        }
    }

    private function buildMail($recipientMail, $contentVars, $recipientVars, $recipientData, $template, $locale, $attachments)
    {
        $mail = (new BaseMail($recipientMail, $contentVars, $recipientVars, $recipientData, $template, $locale, $attachments));
        if ($this->queue){
            $mail->onQueue($this->queue);
        }
        if(config('app.debug')) {
            $recipientMail = config('mail-template-engine.debug_mail');
        }

        //Mail::to($recipientMail)->send($mail);
        Mail::to($recipientMail)->queue($mail);
    }

    /*
     * Formats all possible inputs to following array format:
     * [
     * ['mail' => 'robbe@statik.be',
     * 'locale' => 'nl'],
     * ]
     * */
    private function formatData($recipientDataArray)
    {
        $recipientDataArrayFormatted = [];
        if(!$recipientDataArray || empty($recipientDataArray)) {
            return [];
        }elseif(is_array($recipientDataArray)) {
            if(array_key_exists('locale',$recipientDataArray) && array_key_exists('mail',$recipientDataArray)) {
                if(is_array($recipientDataArray['mail'])) {
                    foreach($recipientDataArray['mail'] as $mail) {
                        $recipientDataArrayFormatted[] = ['mail' => $mail, 'locale' => $recipientDataArray['locale']];

                    }
                }else{
                    $recipientDataArrayFormatted[] = ['mail' => $recipientDataArray['mail'], 'locale' => $recipientDataArray['locale']];
                }
            }else{
                foreach($recipientDataArray as $recipientDataRecord) {
                    if(array_key_exists('mail',$recipientDataRecord)) {
                        if(is_array($recipientDataRecord['mail'])) {
                            foreach($recipientDataRecord['mail'] as $mail) {
                                $recipientDataArrayFormatted[] = ['mail' => $mail, 'locale' => $recipientDataRecord['locale']];
                            }
                        }else{
                            $recipientDataArrayFormatted[] = $recipientDataRecord;
                        }
                    }elseif(is_object($recipientDataRecord[0])) {
                        foreach ($recipientDataRecord as $recipient) {
                            $recipientDataArrayFormatted[] = ['mail' => $recipient->email , 'locale' => $recipient->locale ?? app()->getLocale()];
                        }
                    }elseif(is_object($recipientDataRecord)){
                        $recipientDataArrayFormatted[] = ['mail' => $recipientDataRecord->email , 'locale' => $recipientDataRecord->locale ?? app()->getLocale()];

                    }
                }
            }
        }

        elseif($recipientDataArray instanceof Collection) {
            foreach($recipientDataArray as $recipientData) {
                $recipientDataArrayFormatted[] = ['mail' => $recipientData->email , 'locale' => $recipientData->locale ?? app()->getLocale()];
            }
        }

        elseif(key_exists('mail', $recipientDataArray)) {
            if(is_array($recipientDataArray['mail'])) {
                foreach($recipientDataArray['mail'] as $mail) {
                    $recipientDataArrayFormatted[] = ['mail' => $mail , 'locale' => $recipientDataArray['locale'] ?? app()->getLocale()];
                }
            }else {
                $recipientDataArrayFormatted[] = $recipientDataArray;
            }
        }

        return $recipientDataArrayFormatted;
    }

    public static function name()
    {
        return static::$name ?: static::parsedClassName();
    }

    /**
     * Little something I borrowed from Nova.
     *
     * @return string
     */
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

    private function getMailTemplates(){
        $templateKey = array_search(static::class, config('mail-template-engine.templates'));

        return MailTemplate::where('mail_type', $templateKey)->get();
    }
}
