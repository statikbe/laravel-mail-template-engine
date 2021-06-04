<?php

namespace Statikbe\LaravelMailEditor;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class MailTemplate extends Model
{
    use HasTranslations;

    public $translatable = ['subject', 'body', 'attachments', 'sender_name', 'sender_email'];

    protected $fillable = ['mail_type', 'name', 'recipients', 'subject', 'body', 'attachments', 'sender_name', 'sender_email', 'cc', 'bcc', 'design', 'render_engine'];

    protected $casts = [
        'recipients' => 'json',
        'cc' => 'json',
        'bcc' => 'json',
    ];
}
