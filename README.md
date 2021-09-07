# Laravel Mail Template Engine

The Laravel Mail Template Engine makes it possible to create mail templates for your transactional mails. 
These mail templates can hold content and recipient variables which can be filled when triggering the mail. 

This package works together with our [Laravel Nova Mail Editor](https://github.com/statikbe/laravel-nova-mail-editor) that provides a UI to let content managers edit translations.

## How it works
The packages combines 3 different sets of data to send a transactional mail. 
1. Firstly there is the Mail class: here you define a name, content variables and recipient variables.
2. Next up you store your mail templates in your database, you can do this in Nova using [Laravel Nova Mail Editor](https://github.com/statikbe/laravel-nova-mail-editor). Or you write your own interface to save the templates.
3. Finally, you call the mail class and fill in its variables (content and recipient). 
At this moment the engine will look for all templates using this class, starts filling them with the provided variables and send them to the selected recipients.
 
## Installation

1. Install using composer:
```
composer require statikbe/laravel-mail-template-engine
```

2. Publish the mail template migration and config
```
php artisan vendor:publish --tag=mail-template-engine
```

3. Run the mail template migration
```
php artisan migrate
```

## How to use

### The mail class
This class will decide what content variables are available in your mail template and will be used to decide when a mail is send. 

You can generate a mail with the following command 
```shell script
php artisan make:mail-class SomeEventMail
```

After creating a Mail class, add them to the config `mail-template-engine.php` mails.

An example of a Mail class can be found at `src/Mails/ResetPassword.php`.
```php
<?php

namespace Statikbe\LaravelMailEditor\Mails;

use Statikbe\LaravelMailEditor\AbstractMail;

class ResetPasswordMail extends AbstractMail
{
    public static function name(){
        return __('ResetPasswordTemplate');
    }

    public static function getContentVariables(){
        return [
            'url' => __('Reset password URL'),
        ];
    }

    public static function getRecipientVariables(){
        return [
            'user' => __('User'),
        ];
    }
}
```

### The Mail Templates
Mail templates are stored in the database. This uses the model `Statikbe\LaravelMailEditor\MailTemplate` and can be created like any other model. 
A mail template can be localized, we use Spatie's translatable package for this to work. More information can be found on their page: [Laravel Translatable](https://github.com/spatie/laravel-translatable).

You can use the keys from your corresponding mail_class to place variable data inside the mail template. 

The `MailTemplate` model consists off:

| Name        | Type   | Description | Example
| ----------- | ------ | ------------- | -------
| mail_class  | string | A mail class from config `mail-template-engine.mails` | verify-email
| name        | string | Just a descriptive naming of the mail for an index page | Email verification mail
| subject     | json (translatable) | The subject, fillable with `ContentVariables` from the corresponding Mail Class | {"en": "Welcome [[name]], please verify your email"}
| body        | json (translatable) | The body, fillable with `ContentVariables` from the corresponding Mail Class | {"en": "Welcome [[name]], please verify your email using the following url [[url]]."}
| sender_name | json (translatable) | The sender by name | {"en": "Statik"}
| sender_email | json (translatable) | The sender by email | {"en": "info@statik.be"}
| recipients | json | The recipients of the mail, an array filled with both `RecipientVariables` from the Mail Class and/or plain emails | ["user","general@email.com"]
| cc | json | The cc of the mail, an array filled with both `RecipientVariables` from the Mail Class and/or plain emails | ["user","general@email.com"]
| bcc | json | The bcc of the mail, an array filled with both `RecipientVariables` from the Mail Class and/or plain emails | ["user","general@email.com"]
| attachments | json (translatable) | work in progress | 
| design | string | A design from config `mail-template-engine.designs` |  statikbe::mail.designs.default
| render_engine | string | A render engine from config `mail-template-engine.render_engines` | html
| created_at | timestamp
| updated_at | timestamp
| deleted_at | timestamp 

If your application is using Nova you can use [Nova Mail Editor](https://github.com/statikbe/laravel-nova-mail-editor): a tool for editing and creating mail templates.
 
### Calling the mail class 
In order to send mails you fill and send Mail classes. The filling references to providing the correct data for the variables of the class. 
The class will now look for any mail template in the database using this Mail class, build and send the mail.  

An example:
```php
use Statikbe\LaravelMailEditor\Mails\ResetPasswordMail;

$contentVars = [
    'nl' => [
        'url' => $verificationUrl,
    ],
];
$recipientVars = [
    'user' => [
        'mail' => $user->email,
        'locale' => 'en',
    ],
];

$mail = new ResetPasswordMail();
$mail->sendMail($contentVars, $recipientVars);
```

## Styling
The default design provided by the package comes from [here](https://github.com/leemunroe/responsive-html-email-template).
You can publish the views and customize all you want! 
```
php artisan vendor:publish --provider="Statikbe\LaravelMailEditor\MailEditorServiceProvider" --tag=views
```
You can provide your own designs by adding them to the `designs` array in the config. Designs are view directories where you store the views for your render engines, F.E. the default HTML engine expects a view called `html.blade.php`. 

## Configuration

You can publish the configuration by running this command:
```
php artisan vendor:publish --tag=mail-template-engine
```

The following configuration fields are available:

TODO document configuration


## License
The MIT License (MIT). Please see [license file](LICENSE.md) for more information.
