<?php

declare(strict_types=1);


namespace App\Tests\Behat;


use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\MinkExtension\Context\RawMinkContext;

class AuthenticationContext extends RawMinkContext
{

    private $credentials;

    /**
     * @When /^I send a "([^"]*)" request at "([^"]*)"$/
     *
     * @param string $method
     * @param string $url
     */
    public function iSendARequestAt($method, $url): void
    {
        throw new PendingException();
    }

    /**
     * @Then /^The response code should be: (\d+)$/
     *
     * @param int $code
     */
    public function theResponseCodeShouldBe($code): void
    {
        throw new PendingException();
    }

    /**
     * @Given /^The response body should be "([^"]*)" "([^"]*)"$/
     *
     * @param string $key
     * @param string $content
     */
    public function theResponseBodyShouldBe($key, $content): void
    {
        throw new PendingException();
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
     * @Given /^I have the following credentials:$/
     *
     * @param PyStringNode $credentials
     */
    public function iHaveTheFollowingCredentials(PyStringNode $credentials): void
    {
        $this->credentials = $credentials;
    }
}
