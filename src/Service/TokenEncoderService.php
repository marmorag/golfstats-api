<?php
declare(strict_types=1);

namespace App\Service;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\ValidationData;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTEncodeFailureException;

class TokenEncoderService implements JWTEncoderInterface
{
    public const TOKEN_ISSUER = 'golfstats:api';

    private Parser $parser;
    private string $tokenIdentifier;

    public function __construct(string $tokenIdentifier)
    {
        $this->parser = new Parser();
        $this->tokenIdentifier = $tokenIdentifier;
    }

    /**
     * @param array<string, mixed> $data
     * @return string the encoded token string
     *
     * @throws JWTEncodeFailureException If an error occurred while trying to create
     *                                   the token (invalid crypto key, invalid payload...)
     */
    public function encode(array $data): string
    {
        if (!array_key_exists('user', $data)) {
            throw new JWTEncodeFailureException('Missing key', 'missing "user" key');
        }

        $time = time();
        $token = (new Builder())
            ->issuedBy(self::TOKEN_ISSUER)
            ->identifiedBy($this->tokenIdentifier, true)
            ->withClaim('user', $data['user'])
            ->issuedAt($time)
            ->expiresAt($time + 3600)
            ->getToken();

        return (string) $token;
    }

    /**
     * @param string $stringToken
     * @return Token
     *
     * @throws JWTDecodeFailureException If an error occurred while trying to load the token
     *                                   (invalid signature, invalid crypto key, expired token...)
     */
    public function decode($stringToken): Token
    {
        $token = $this->parser->parse($stringToken);

        $validationData = new ValidationData();
        $validationData->setIssuer(self::TOKEN_ISSUER);
        $validationData->setId($this->tokenIdentifier);

        if (!$token->validate($validationData)) {
            throw new JWTDecodeFailureException('Invalid token', 'unable to validate token');
        }

        return $token;
    }
}
