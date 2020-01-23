Feature: API Authentication
  As an user
  I must be able to authenticate on the API

  Scenario: I can't access a resource when I'm not authenticated
    When I send a GET request to "/api/users"
    Then the response status code should be 401
    And the response should be in JSON
    And the response should be equal to:
    """
    {"message":"The request did not include an authentication token or the authentication token was expired."}
    """

  Scenario: I can login on API and get access token
    When I send a POST request to "/api/auth" with parameters:
        | key      | value                        |
        | login    | guillaume.marmorat@gmail.com |
        | password | password                     |
    Then the response status code should be 200
    And the response should be in JSON
    And the response should contain "token"

  Scenario: I cannot login on API when credentials are invalid : bad password
    When I send a POST request to "/api/auth" with parameters:
      | key      | value                        |
      | login    | guillaume.marmorat@gmail.com |
      | password | secretPassword               |
    Then the response status code should be 403
    And the response should be in JSON
    And the response should not contain "token"

  Scenario: I cannot login on API when credentials are invalid : bad login
    When I send a POST request to "/api/auth" with parameters:
      | key      | value                  |
      | login    | i-dont-exist@gmail.com |
      | password | password               |
    Then the response status code should be 404
    And the response should be in JSON
    And the response should not contain "token"