<?php
declare(strict_types=1);

namespace Ttskch\Monolog\Handler;

use Mailgun\Api\Message;
use Mailgun\Mailgun;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class MailgunHandlerTest extends TestCase
{
    public function testSendText()
    {
        $body = 'text';

        $SUT = $this->getSUT($body, 'text');

        $reflection = new ReflectionClass($SUT);
        $method = $reflection->getMethod('send');
        $method->setAccessible(true);
        $method->invoke($SUT, $body, []);
    }

    public function testSendHtml()
    {
        $body = '<html>';

        $SUT = $this->getSUT($body, 'html');

        $reflection = new ReflectionClass($SUT);
        $method = $reflection->getMethod('send');
        $method->setAccessible(true);
        $method->invoke($SUT, $body, []);
    }

    private function getSUT(string $body, $contentType = 'text')
    {
        $mg = $this->prophesize(Mailgun::class);

        $message = $this->prophesize(Message::class);
        $message->send('domain', [
            'from' => 'from',
            'to' => 'to1',
            'subject' => 'subject',
            $contentType => $body,
        ])->shouldBeCalledTimes(1);
        $message->send('domain', [
            'from' => 'from',
            'to' => 'to2',
            'subject' => 'subject',
            $contentType => $body,
        ])->shouldBeCalledTimes(1);

        $mg->messages()->willReturn($message);

        return new MailgunHandler($mg->reveal(), 'domain', 'from', ['to1', 'to2'], 'subject');
    }
}
