<?php

declare(strict_types=1);

namespace Freyr\TDD\Infrastructure;

class EmailNotifier
{
    public function send(string $to, string $subject, string $body): void
    {
        error_log("MAIL[$to] $subject :: $body");
    }
}