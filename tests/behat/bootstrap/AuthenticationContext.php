<?php

declare(strict_types=1);


namespace App\Tests\Behat;


use Behat\Behat\Tester\Exception\PendingException;
use Behat\MinkExtension\Context\RawMinkContext;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Webmozart\Assert\Assert;

class AuthenticationContext extends RawMinkContext
{

    private $credentials;
    /**
     * @var ResponseInterface
     */
    private $response;
    private $client;
    private $baseUri = 'http://localhost:8000';

    public function __construct()
    {
        $this->client = HttpClient::create([
            'headers' => [
                'Accept' => 'application/ld+json',
                'Content-Type' => 'application/json'
            ]
        ]);
    }

    /**
     * @When /^I send a "([^"]*)" request at "([^"]*)"$/
     *
     * @param string $method
     * @param string $url
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function iSendARequestAt($method, $url): void
    {
        try {
            if (isset($this->credentials)) {
                $this->response = $this->client->request($method, $this->baseUri.$url, [
                    'body' => $this->credentials
                ]);
            } else {
                $this->response = $this->client->request($method, $this->baseUri.$url);
            }
        } catch (ClientException $exception) {
            // normal in some case
        }
    }

    /**
     * @Then /^The response code should be: (\d+)$/
     *
     * @param int $code
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function theResponseCodeShouldBe($code): void
    {
        Assert::eq($this->response->getStatusCode(), $code);
    }

    /**
     * @Given /^The response body should be "([^"]*)" "([^"]*)"$/
     *
     * @param string $key
     * @param string $content
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function theResponseBodyShouldBe($key, $content): void
    {
        $jsonBody = $this->response->toArray();
        Assert::keyExists($jsonBody, $key);
        Assert::eq($jsonBody[$key], $content);
    }

    /**
     * @Given /^I should not be authenticated$/
     */
    public function iShouldNotBeAuthenticated(): void
    {
        throw new PendingException();
    }

    /**
     * @Given /^I should be authenticated$/
     */
    public function iShouldBeAuthenticated(): void
    {
        throw new PendingException();
    }

    /**
     * @Given /^I have the following credentials "([^"]*)" "([^"]*)"$/
     * @param string $username
     * @param string $password
     */
    public function iHaveTheFollowingCredentials1($username, $password): void
    {
        $this->credentials = [
          'username' => $username,
          'password' => $password
        ];
    }
}
