<?php

namespace Statikbe\LaravelMailEditor;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class MailTemplate extends Model
{
    use HasTranslations;
    use SoftDeletes;

    public $translatable = ['subject', 'body', 'attachments', 'sender_name', 'sender_email'];

    protected $fillable = ['mail_class', 'name', 'recipients', 'subject', 'body', 'attachments', 'sender_name', 'sender_email', 'cc', 'bcc', 'design', 'render_engine'];

    protected $casts = [
        'recipients' => 'json',
        'cc' => 'json',
        'bcc' => 'json',
    ];
}
