Feature: API Authentication
  As an user
  I must be able to authenticate on the API

  Scenario: I can't access a ressource when I'm not authenticated
    When I send a "GET" request at "/api/users"
    Then The response code should be: 401

  Scenario: I can login on API and get access token
    Given I have the following credentials "guillaume.marmorat@gmail.com" "password"
    When I send a "POST" request at "/authenticate"
    Then The response code should be: 200
    And I should be authenticated

  Scenario: I cannot login on API when credentials are invalid
    Given I have the following credentials "i-dont-exist@example.com" "test"
    When I send a "POST" request at "/authenticate"
    Then The response code should be: 403
    And I should not be authenticated

  Scenario: I can logout from the application
    Given I have the following credentials "guillaume.marmorat@gmail.com" "password"
    When I send a "POST" request at "/authenticate"
    Then The response code should be: 200
    And I should be authenticated
    Then I send a "GET" request at "/logout"
    And I should not be authenticated