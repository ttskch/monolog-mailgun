# monolog-mailgun

[![Latest Stable Version](https://poser.pugx.org/ttskch/monolog-mailgun/version)](https://packagist.org/packages/ttskch/monolog-mailgun)
[![Total Downloads](https://poser.pugx.org/ttskch/monolog-mailgun/downloads)](https://packagist.org/packages/ttskch/monolog-mailgun)

Monolog handler for [Mailgun](https://www.mailgun.com/) using [mailgun/mailgun-php](https://github.com/mailgun/mailgun-php).

## Installation

```bash
$ composer require ttskch/monolog-mailgun:@dev
```

## Usage

```php
$mg = \Mailgun\Mailgun::create('api_key');
$domain = 'mg.example.com';
$from = 'Alice <alice@example.com>';
$to = 'bob@foo.bar.com';
$subject = '[Monolog] Error Report';

$handler = new \Ttskch\Monolog\Handler\MailgunHandler($mg, $domain, $from, $to, $subject);
$logger = new \Monolog\Logger('mailgun');
$logger->pushHandler($handler);
$logger->addCritical('Critical Error!');
```

## Examples of framework integrations

### Symfony4

```yaml
# config/packages/prod/monolog.yaml
monolog:
    handlers:
        email:
            type: fingers_crossed
            action_level: critical
            handler: deduplicated
        deduplicated:
            type: deduplication
            handler: mailgun
        mailgun:
            type: service
            id: mailgun_handler

services:
    mailgun:
        class: Mailgun\Mailgun
        factory: [Mailgun\Mailgun, create]
        arguments: ['%env(MAILGUN_API_KEY)%']
    mailgun_handler:
        class: Ttskch\Monolog\Handler\MailgunHandler
        arguments:
            - mailgun
            - mg.example.com
            - Alice <alice@example.com>
            - bob@foo.bar.com
            - '[Monolog] Error Report'
```

```
# .env
MAILGUN_API_KEY=api_key
```

### Other

Feel free to send me a PR🙏
