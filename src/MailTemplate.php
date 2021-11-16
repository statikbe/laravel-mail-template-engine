<?php

namespace Statikbe\LaravelMailEditor;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class MailTemplate extends Model
{
    use HasTranslations;
    use SoftDeletes;

    public $translatable = [
        'subject',
        'body',
        'attachments',
        'sender_name',
        'sender_email'
    ];

    protected $fillable = [
        'mail_class',
        'name',
        'recipients',
        'subject',
        'body',
        'attachments',
        'sender_name',
        'sender_email',
        'cc',
        'bcc',
        'design',
        'render_engine'
    ];

    protected $casts = [
        'recipients' => 'json',
        'cc' => 'json',
        'bcc' => 'json',
    ];

    public function addAttachments(array $attachmentsPerLocale)
    {
        $results = [];
        foreach ($attachmentsPerLocale as $locale => $attachments) {
            /** @var \Illuminate\Http\UploadedFile $attachment */
            foreach ($attachments as $attachment) {
                if ($attachment instanceof \Illuminate\Http\UploadedFile) {
                    $results[$locale][] = $attachment->storeAs('mail/attachments', $attachment->getClientOriginalName());
                } else {
                    //we assume its the path to an existing file
                    $results[$locale][] = $attachment;
                }
            }
        }

        $this->attachments = $results;
        return $this;
    }
}
