<?php
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


    public function __construct(Mailgun $mg, $domain, $from, $to, $subject, $level = Logger::DEBUG, $bubble = true)
    {
        parent::__construct($level, $bubble);

        $this->mg = $mg;
        $this->domain = $domain;
        $this->from = $from;
        $this->to = is_array($to) ? $to : [$to];
        $this->subject = $subject;
    }

    /**
     * {@inheritdoc}
     */
    protected function send($content, array $records)
    {
        foreach ($this->to as $to) {
            $params = [
                'from' => $this->from,
                'to' => $to,
                'subject' => $this->subject,
                'text' => $content,
            ];

            $this->mg->messages()->send($this->domain, $params);
        }
    }
}