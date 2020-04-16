<?php
declare(strict_types=1);

namespace Ttskch\Monolog\Handler;

use Mailgun\Mailgun;
use Monolog\Handler\MailHandler;
use Monolog\Logger;

class MailgunHandler extends MailHandler
{
    /**
     * @var Mailgun
     */
    private $mg;

    /**
     * @var string
     */
    private $domain;

    /**
     * @var string
     */
    private $from;

    /**
     * @var string[]
     */
    private $to;

    /**
     * @var string
     */
    private $subject;

    public function __construct(Mailgun $mg, string $domain, string $from, array $to, string $subject, int $level = Logger::DEBUG, bool $bubble = true)
    {
        parent::__construct($level, $bubble);

        $this->mg = $mg;
        $this->domain = $domain;
        $this->from = $from;
        $this->to = $to;
        $this->subject = $subject;
    }

    /**
     * {@inheritdoc}
     */
    protected function send(string $content, array $records): void
    {
        foreach ($this->to as $to) {
            $params = [
                'from' => $this->from,
                'to' => $to,
                'subject' => $this->subject,
                $this->isHtmlBody($content) ? 'html' : 'text' => $content,
            ];

            $this->mg->messages()->send($this->domain, $params);
        }
    }
}
