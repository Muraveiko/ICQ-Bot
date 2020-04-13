# PHP Library for Icq bot API
[![Latest Stable Version](https://poser.pugx.org/antson/icq-bot/v/stable)](https://packagist.org/packages/antson/icq-bot)
[![Total Downloads](https://poser.pugx.org/antson/icq-bot/downloads)](https://packagist.org/packages/antson/icq-bot)
[![License](https://poser.pugx.org/antson/icq-bot/license)](https://packagist.org/packages/antson/icq-bot)

This library provides complete Bot API 1.0 interface and compatible with PHP 5.5+ and 7+

<img src="https://api.icq.net/expressions/get?f=native&k=&t=70001&type=floorLargeBuddyIcon" width="100" height="100" alt="MetaBot logo">


## Installing
Install using composer:
```
composer require antson/icq-bot
```
## Getting Started

```php
$icq = new Antson\IcqBot\Client(YOU_TOOKEN);
$result = $icq->sendText(YOU_UIN,'Hello,word!');
```
**Don't forgot start dialog with bot first.**

## API description
<ul>
    <li><a href="https://icq.com/botapi/">icq.com/botapi/</a></li>
</ul>
