<?php
declare(strict_types=1);

namespace App\Tests\phpunit\Controller\Api;

class ValidationSchema
{
    public const TOKEN = [
        '$schema' => 'https://json-schema.org/draft-04/schema#',
        'type' => 'object',
        'properties' => [
            'token' => [
                'type' => 'string',
                'pattern' => '.*\..*\..*'
            ],
        ],
    ];
}
