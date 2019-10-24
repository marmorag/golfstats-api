Feature: API Authentication
  As an user
  I must be able to authenticate on the API

  Scenario: I can't access a ressource when I'm not authenticated
    When I request "GET /api/users"
    Then The response code should be: 401
    And The response body should be "hydra:description" "Full authentication is required to access this resource."

  Scenario: I can login on API and get access token
    Given I have the following credentials:
      """
      {
        "username": "guillaume.marmorat@gmail.com",
        "password" : "password"
      }
      """
    When I request "POST /api/authenticate"
    Then The response code should be: 401
    And I should be authenticated

  Scenario: I can logout from the application
    When I request "GET /logout"
    Then I should not be authenticated

  Scenario Outline: I can't login if my credentials are invalid
    When I am on "/login"
    And I fill in "email" with "<email>"
    And I fill in "password" with "<password>"
    And I press "loginBtn"
    Then I should be on "/login"
    And the response should contain "Invalid credentials"

    Examples:
      | email                    | password        |
      | i-dont-exist@example.com | test            |
      | user1@example.com        | not my password |