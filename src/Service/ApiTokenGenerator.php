<?php
declare(strict_types=1);

namespace App\Service;

class ApiTokenGenerator
{
    public static function generate(): string
    {
        return sha1(sprintf('golfstats:%s:%s', uniqid('', true), random_bytes(15)));
    }
}
