<?php

declare(strict_types=1);

namespace App\Auth\Test\Unit\Service;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Token;
use App\Auth\Service\SignupConfirmationSender;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email as MimeEmail;
use Twig\Environment;

/**
 * @covers \App\Auth\Service\signupConfirmationSender
 *
 * @internal
 */
final class SignupConfirmationSenderTest extends TestCase
{
    public function testSuccess(): void
    {
        $to = new Email('user@app.test');
        $name = 'Test Test';
        $token = new Token(Uuid::uuid4()->toString(), new DateTimeImmutable());
        $confirmUrl = 'http://test/signup/confirm?token=' . $token->getValue();

        $twig = $this->createMock(Environment::class);
        $twig->expects(self::once())->method('render')->with(
            self::equalTo('signup.html.twig'),
            self::equalTo(['token' => $token, 'name' => $name]),
        )->willReturn($body = '<a href="' . $confirmUrl . '">' . $confirmUrl . '</a>');

        $mailer = $this->createMock(MailerInterface::class);
        $mailer->expects(self::once())->method('send')
            ->willReturnCallback(static function (MimeEmail $message) use ($to, $body): void {
                self::assertEquals($to->getValue(), $message->getTo()[0]->getAddress());
                self::assertEquals('Complete your registration', $message->getSubject());
                self::assertEquals($body, $message->getHtmlBody());
            });

        $sender = new SignupConfirmationSender($mailer, $twig);

        $sender->send($to, $token, $name);
    }
}
