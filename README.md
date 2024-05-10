# Official OxiSMS PHP Wrapper

![MIT License](https://img.shields.io/badge/license-MIT-007EC7.svg?style=flat-square)
![Current Version](https://img.shields.io/badge/version-1.0.0-green.svg)

## Overview

This repository contains the official PHP wrapper for the OxiSMS API.
To get started, create an account and request your free credits on [this page](https://account.oxemis.com/)

This library is a wrapper for the [OxiSMS API](https://api.oxisms.com) but you don't have to know how to use the API to get started with this library.

## What is OxiSMS ?

OxiMailing is a solution designed to enable you to **send your SMS quickly and easily**.

We offer worldwide coverage (see [details](https://www.oxemis.com/en/sms/worldwide-coverage)) and sender personalization when authorized by recipient operators. 

All our SMS messages are sent via **Premium routes**, guaranteeing the best possible deliverability.

We're a small team, but we're recognized for the quality of our support and expertise.

## Table of contents

- [Compatibility](#compatibility)
- [Installation](#installation)
- [Authentication](#authentication)
- [Getting information about your account](#getting-information-about-your-account)
- [Sending your first sms](#sending-your-first-sms)
- [How to send customized messages ?](#how-to-send-customized-messages)
- [Using a custom sender](#using-a-custom-sender)
- [How many characters can I put in my message ?](#how-many-characters-can-i-put-in-my-message-)
- [How to get the number of credits that will be used for a sending ?](#how-to-get-the-number-of-credits-that-will-be-used-for-a-sending-)
- [How to specify a strategy for my sending ?](#how-to-specify-a-strategy-for-my-sending-)
- [Other features](#other-features)
- [Contribute](#contribute)

## Compatibility

This library requires **PHP v7.4** or higher.

## Installation

Use the below code to install the wrapper:

`composer require oxemis/oxisms`

## Authentication

This library is a wrapper to the [OxiSMS API](https://api.oxisms.com).
You can request an API KEY in your [OxiSMS Account](https://account.oxemis.com). Free credits are offered.

You should export your API_LOGIN and API_PASSWORD in order to use them in this library :

```bash
export OXISMS_API_LOGIN='your API login'
export OXISMS_API_PWD='your API password'
```

Initialize your **OxiSms** Client:

```php
require_once 'vendor/autoload.php';
use \Oxemis\OxiSms\OxiSmsClient;

// getenv will allow us to get the OXISMS_API_LOGIN/OXISMS_API_PWD variables we created before:

$apilogin = getenv('OXISMS_API_LOGIN');
$apipwd = getenv('OXISMS_API_PWD');

$oxisms = new OxiSmsClient($apilogin, $apipwd);

// or, without using environment variables:

$apilogin = 'your API login';
$apipwd = 'your API password';

$oxisms = new OxiSmsClient($apilogin, $apipwd);
```

## Getting information about your account
You will find all the information about your OxiSMS account with the "**UserAPI**" object.
Informations returned are documented in the class.

```php
require_once "vendor/autoload.php";
use Oxemis\OxiSms\OxiSmsClient;

$client = new OxiSmsClient(API_LOGIN,API_PWD);
$user = $client->userAPI->getUser();

echo "Name :" . $user->getCompanyName() . "\n" .
     "Remaining credits : " . $user->getCredits() . "\n";
```

## Sending your first SMS
In order to send a mail, you must instantiate a `Message` object and, send it, via the `$client->sendAPI->Send()` method.

Here's a simple sample of how to send a SMS :

```php
require_once 'vendor/autoload.php';
use Oxemis\OxiSms\OxiSmsClient;  
use Oxemis\OxiSms\Objects\Message;

// Create the Client
$apilogin = 'your API login';
$apipwd = 'your API password';
$client = new OxiSmsClient($apilogin, $apipwd);

// Define the message
$message = new Message();  
$message
->addRecipientPhoneNumber("+33666666666") 
->setMessage("Hi there ! This is my first SMS sent with the awesome oxisms-php library !");

// Send the message
$client->sendAPI->send($message);
```

You can also **schedule a sending** by using the `$message->setScheduledDateTime($selectedDateAndTime)` method. 

## How to send customized messages?
With this library you can send customized messages based on templating.
Basically, every content between `{{` and `}}` will be replaced by the corresponding **recipient metadata**.

Here is a simple sample :

```php
require_once 'vendor/autoload.php';
use Oxemis\OxiSms\Objects\Recipient;
use Oxemis\OxiSms\Objects\Message;
use Oxemis\OxiSms\OxiSmsClient;

// First of all, we need recipients with meta data
$myFirstRecipient = new Recipient();
$myFirstRecipient->setPhoneNumber("+33666666666");
$myFirstRecipient->setMetaData(["Name" => "Joe", "ID" => 1]);

$mySecondRecipient = new Recipient();
$mySecondRecipient->setPhoneNumber("+3377777777");
$mySecondRecipient->setMetaData(["Name" => "Jane", "ID" => 2]);

// We create the message with {{custom parts}}
$m = new Message();  
$m->addRecipient($myFirstRecipient) 
->addRecipient($mySecondRecipient) 
->setMessage("Hi {{Name}} ! This is your ID : {{ID}}");

// Then we send the two messages in one call !
$client = new OxiSmsClient(API_LOGIN, API_PWD);
$client->sendAPI->send($m);
```

> Two messages will be sent. The first one will be "`Hi Joe ! This is your ID : 1`", the second one : "`Hi Jane ! This is your ID : 2`".

## Using a custom sender

By default, our SMS are sent with a short code (36xxx in France for example) or a specific phone number.
But, as we send SMS using **Premium routes**, you can specify a custom sender in your messages.

> Please note that some operators refuse to receive messages with custom senders. In these cases, we'll replace the 
> sender with a short code. You can check the [coverage](https://www.oxemis.com/en/sms/worldwide-coverage) about this.

> Custom senders like **phone numbers are _not allowed_** to prevent spoofing ! 

The sender **MUST** respect these requirements :

- **2 to 11** characters
- Only ascii **A-Z 0-9 and spaces**

And please not that, **if you use the `commercial` strategy** and a **custom sender**, an unsubscribe method will be added to your message (`STOP SMS 36111` for example).
**This can increase the length of the message**.

Ok for you ? So let's set the sender in our previous sample with the `setSender` method:

```php
require_once 'vendor/autoload.php';
use Oxemis\OxiSms\OxiSmsClient;  
use Oxemis\OxiSms\Objects\Message;

// Define the message
$message = new Message();  
$message
->setSender("OxiSMS")
->addRecipientPhoneNumber("+33666666666") 
->setMessage("Hi there ! This is my first SMS sent with the awesome oxisms-php library !");

// Send the message
$client = new OxiSmsClient(API_LOGIN, API_PWD);
$client->sendAPI->send($message);
```

## How many characters can I put in my message ?

Not an easy answer !
SMS are basically **limited to 160 characters**.

**BUT**, if your message contains **characters that are not in the [GSM alphabet](https://en.wikipedia.org/wiki/GSM_03.38#GSM_8_bit_data_encoding)** (*e.g. emojis or some accented characters like `ê`*) we'll have to send you messages in **unicode** mode. In this case SMS are limited to **70 characters**.

Not enough ? No problem ! Our platform is able to manage **long sms** so that you can send messages **composed by 8 SMS** (it's totaly transparent for your recipients, the parts are concatened).
**But in this case SMS are limited to 153 characters (GSM) or 67 characters (Unicode)**.

So, TLDR; :

- If your message contains **only** [GSM characters](https://en.wikipedia.org/wiki/GSM_03.38#GSM_8_bit_data_encoding) the max length of 1 SMS is 160 characters.
- If your message contains **special chars (like emojis)**, the max length of 1 SMS is 70 characters.
- If your message is **GSM and you exceed the 160 chars limit**, the number of SMS used to send the message is : **number of chars / 157** (max 8).
- If your message is **Unicode and you exceed the 70 chars limit**, the number of SMS used to send the message is : **number of chars / 67** (max 8).


## How to get the number of credits that will be used for a sending ?
As we've seen above, calculate the cost of a sending is not as simple as counting the number of recipients 😁

If you want to get the future cost of a sending, use the `$client->sendAPI->getCostOfMessage($m)` method !

```php
// Compute the cost of the message (without sending it !)
$s = $client->sendAPI->getCostOfMessage($message );

// You'll get a Sending structure (without Ids, because messages are not really sent)
echo "This sending will consume " . $s->getTotalCost() . " credit(s).";
```

You can also set a `MaxCreditsPerSending` on your message.
If that cost is exceeded, OxiSMS will refuse to send your message.

This example will throw an API Exception :

```php
$message = new \Oxemis\OxiSms\Objects\Message();  
$message
->setSender("OxiSMS")
->addRecipientPhoneNumber("+33666666666")
->addRecipientPhoneNumber("+33777777777")
->setMaxCreditsPerSending(1)
->setMessage("Hi there ! This is my first SMS sent with the awesome oxisms-php library !");

// Will throw an OxiSmsException : 
// Code : 406
// Message : Total credits needed to send the message (2) exceeds the MaxCreditsPerSending parameter (1).
$client->sendAPI->send($message);
```

## How to specify a strategy for my sending ?
Strategy is very important for your message.

There are two different stragtegies available :


| Strategy                | Meaning                                                          | Restrictions                                                                                                                                                                                                                                                       |
|-------------------------|------------------------------------------------------------------|--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| **Commecial** (default) | Used for **marketing** messages.                                 | All sendings in 'commercial' strategy are not allowed in the evening after 9:00 p.m., in the morning before 8:00 a.m. as well as on Sundays and public holidays. **They will not be rejected**, they will be automatically postponed to the next available period. |
| **Notification**        | Used for **notifications** (password reset, two factors auth...) | No restriction                                                                                                                                                                                                                                                     |

> **Please be very careful selecting the strategy.** Sending marketing messages with "Notification" strategy will probably lock your account cause of "complaints" from your recipients !

To specify your strategy, set it in the `Message` :

```php
$message = new \Oxemis\OxiSms\Objects\Message();
$message
->setStrategy(Message::STRATEGY_NOTIFICATION)
->addRecipientPhoneNumber("+33666666666") 
->setMessage("Here is your code to authenticate : " . $twoFactorsCode);
```

## Other features

You'll find a lot of other features by exploring the `*API` objects in the APIClient.
Here is a non-exhaustive list of these objects (each one is documented with PHPDoc).

- `blacklistsAPI` : get / set your blacklists (lists of unsubscribed recipients)
- `bouncesAPI` : get / set your bounces (list of invalid phone numbers)
- `sendAPI` : send now or schedule your messages
- `userAPI` : everything about your account

Each object is documented with PHPDoc. Other features will be added in the future.
You can also make direct call to the API (and even contribute to this project !).
Please take a look at the [API Reference](https://api.oxisms.com) 😀

## Contribute

Feel free to ask anything, and contribute to this project.
Need help ? 👉 support@oxemis.com

