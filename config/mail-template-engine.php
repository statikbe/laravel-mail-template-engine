<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Debug mail
    |--------------------------------------------------------------------------
    |
    | This mail address will be used to send mails to when in debug mode.
    |
    */
    'debug_mail' => env('DEBUG_MAIL', 'default@example.com'),

    /*
    |--------------------------------------------------------------------------
    | Mail Templates
    |--------------------------------------------------------------------------
    |
    | Here you can add all the provided mail templates that can be selected for
    | customization.
    |
    | The key of this array is saved to the mail-template, this way you are not
    | limited by the namespace of the template.
    |
    | F.E. 'test-mail' => App\Mail\TestMail::class,
    |
    */
    'templates' => [
        'verify-email' => Statikbe\LaravelMailEditor\Templates\VerifyEmail::class,
        'reset-password' => Statikbe\LaravelMailEditor\Templates\ResetPassword::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Mail Designs
    |--------------------------------------------------------------------------
    |
    | Mail templates can have different designs, these are views that are
    | filled with $content.
    |
    | Supported:    - package based view notation (statikbe::mail.designs.default)
    |               - default include notation (mails.designs.default)
    |
    */
    'designs' => [
        'statikbe::mail.designs.default' => 'default',
    ],

    /*
    |--------------------------------------------------------------------------
    | Render engines
    |--------------------------------------------------------------------------
    |
    | The possible render engines your system provides. These engines receive
    | the mailable, design and body and define how the mail is rendered.
    |
    | The key of this array is saved to the mail-template, this way you are not
    | limited by the namespace of the template.
    |
    | Provided: - Html engine for regular HTML wysiwyg
    |           - Editor-js engine for Editor.js wysiwyg json data
    |
    */
    'render_engines' => [
        'html' => Statikbe\LaravelMailEditor\RenderEngines\HtmlRenderEngine::class,
        //'editor-js' => Statikbe\LaravelMailEditor\RenderEngines\EditorJsRenderEngine::class,
    ],
];