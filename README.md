# monolog-mailgun

[![Build Status](https://travis-ci.org/ttskch/monolog-mailgun.svg?branch=master)](https://travis-ci.org/ttskch/monolog-mailgun)
[![Latest Stable Version](https://poser.pugx.org/ttskch/monolog-mailgun/version)](https://packagist.org/packages/ttskch/monolog-mailgun)
[![Total Downloads](https://poser.pugx.org/ttskch/monolog-mailgun/downloads)](https://packagist.org/packages/ttskch/monolog-mailgun)

Monolog handler for [Mailgun](https://www.mailgun.com/) using [mailgun/mailgun-php](https://github.com/mailgun/mailgun-php).

## Requirements

* php:^7.2
* monolog/monolog:^2.0

## Installation

```bash
$ composer require ttskch/monolog-mailgun
```

## Usage

```php
$mg = \Mailgun\Mailgun::create('api_key');
$domain = 'mg.example.com';
$from = 'Alice <alice@example.com>';
$to = ['bob@foo.bar.com'];
$subject = '[Monolog] Error Report';

$handler = new \Ttskch\Monolog\Handler\MailgunHandler($mg, $domain, $from, $to, $subject, \Monolog\Logger::CRITICAL);
$logger = new \Monolog\Logger('mailgun');
$logger->pushHandler($handler);
$logger->critical('Critical Error!');
```

## Examples of framework integrations

### Symfony4/5

```yaml
# config/packages/mailgun.yaml
services:
    Mailgun\Mailgun:
        class: Mailgun\Mailgun
        factory: ['Mailgun\Mailgun', create]
        arguments: ['%env(MAILGUN_API_KEY)%']
```

```yaml
# config/packages/prod/monolog.yaml
monolog:
    handlers:

        # ...

        email:
            type: fingers_crossed
            action_level: critical
            handler: deduplicated
        deduplicated:
            type:    deduplication # prevent to send twice
            handler: mailgun
        mailgun:
            type: service
            id: Ttskch\Monolog\Handler\MailgunHandler

services:
    Ttskch\Monolog\Handler\MailgunHandler:
        arguments:
            - '@Mailgun\Mailgun'
            - mg.example.com
            - Alice <alice@example.com>
            - [bob@foo.bar.com]
            - '[Monolog] Error Report'
```

```
# .env
MAILGUN_API_KEY=api_key
```

### Other

Feel free to send me a PR🙏
