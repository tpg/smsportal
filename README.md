# SMSPortal client for Laravel

This library provides a complete implementation of the SMSPortal Rest API for Laravel. The library provides a generic client you can use to send SMSs and a Laravel Notifications Channel which can be used to easily send SMSs to specific entities.

Installation through Composer:

```shell
composer require thepublicgood/smsportal
```

Once installed, publish the config file with:

```shell
php ./artisan vendor:publish --provider="TPG\\SMSPortal\\SMSPortalServiceProvider"
```

This will place a simple `smsportal.php` file in your config directory. However, if you don't want to publish the provided config file, you can also alter the provided `services.php` config file with:

```php
return [

    //...
    
    'smsportal' => [
        'id' => env('SMSPORTAL_ID'),
        'secret' => env('SMSPORTAL_SECRET'),
    ],

];
```

Configure your SMSPortal API ID and Secret in either the `services.php` or `smsportal.php` config file. 

## Basic usage
The library provides a Laravel facade to easily send arbitrary messages to one or more mobile numbers:

```php
SMSPortal::to('27823456789')->message('Hello, World!')->send();
```

You can also send the same message to multiple numbers by passing in an array:

```php
SMSPortal::to(['27823456789', '278209876543'])->message('Hello, all of you!')->send();
```

## Advanced usage
If you need more control over who received what, the library has registered the `SMSPortalClient` interface into the Laravel container. You can create `Message` objects and pass them to the `sendBulk` method:

```php
use TPG\SMSPortal\Contracts\SMSPortalClient;
use TPG\SMSPortal\Message;

public function sms(SMSPortalClient $client)
{
    $message1 = new Message('27823456789', 'Message number 1');
    $message2 = new Message('27829876543', 'Message number 2');
    
    $response = $client->sendBulk([$message1, $message2]);
}
```

You can also use the `send` message to send a single message:

```php
$response = $client->send($message);
```

## SMSPortal groups
If you've set up groups on contacts through SMSPortal, you can send a single message to the groups your choice by using the `sendGroup` method:

```php
use TPG\SMSPortal\Contracts\SMSPortalClient;
use TPG\SMSPortal\Message;

public function sms(SMSPortalClient $client)
{
    $response = $client->sendGroup('Hello, all of you groups!', ['group1', 'group2']);
}
```

## Laravel Notifications
If you want to use SMSPortal through Laravel Notifications, create a new Notification class and add `SMSPortalChannel::class` to the array returned by the `via` method:

```php
use TPG\SMSPortal\SMSPortalChannel;

public function via($notifiable): array
{
    return [
        SMSPortalChannel::class,
    ]
}
```

Then define a `toSMSPortal` method on the notification and return a `Message` object:

```php
public function toSMSPortal($notifiable): Message
{
    return new Message($notifiable->mobile, 'Hello, '.$notifiable->name);
}
```

## Sending options
The library also provides a way to pass SMSPortal sending options. You can create a new instance of `SendOptions` and pass in any option you need. Pass this as the last parameter of the `send`, `sendBulk` or `sendGroup` methods:

```php
use TPG\SMSPortal\Contracts\SMSPortalClient;
use TPG\SMSPortal\Message;

public function sms(SMSPortalClient $client)
{
    $options = new SendOptions(
        allowContentTrimming: true,
        shortenUrls: true,
    );

    $response = $client->send($message, $options);
}
```
