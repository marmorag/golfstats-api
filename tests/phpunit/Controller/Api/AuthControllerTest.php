<?php
declare(strict_types=1);

namespace App\Tests\phpunit\Controller\Api;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Client;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class AuthControllerTest extends ApiTestCase
{
    private Client $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function testIssuedTokenIsValidJWT(): void
    {
        // guillaume.marmorat@gmail.com
        // password
        $this->client->request('POST', '/api/auth', [
            'json' => [
                'username' => 'guillaume.marmorat@gmail.com',
                'password' => 'password'
            ],
        ]);

        static::assertResponseStatusCodeSame(200);
        static::assertMatchesJsonSchema(ValidationSchema::JWT_TOKEN);
    }
}
