<?php

namespace App\Service;

use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Validation\RequiredConstraintsViolated;

use Lcobucci\JWT\Validation\Constraint;
use Lcobucci\JWT\Validation\Constraint\ValidAt;
use Lcobucci\Clock\SystemClock;

class JwtService
{
    private Configuration $config;

    public function __construct()
    {
        // Génération de la configuration avec la clé secrète
        $this->config = Configuration::forSymmetricSigner(
            new \Lcobucci\JWT\Signer\Hmac\Sha256(),
            InMemory::plainText('your_secret_key')
        );

        // Set validation constraints
        $this->config->setValidationConstraints(
            new ValidAt(SystemClock::fromUTC()) // Ensure token is not expired
        );
    }

    public function generateToken(int $userId): string
    {
        $now   = new \DateTimeImmutable();
        $token = $this->config->builder()
            ->issuedBy('your-app')
            ->permittedFor('your-client')
            ->identifiedBy(bin2hex(random_bytes(16)))
            ->issuedAt($now)
            ->canOnlyBeUsedAfter($now)
            ->expiresAt($now->modify('+1 hour'))
            ->withClaim('uid', $userId) // Using 1 for tests
            ->getToken($this->config->signer(), $this->config->signingKey());

        return $token->toString();
    }

    public function validateToken(string $tokenString): ?Token
    {
        try {
            $token = $this->config->parser()->parse($tokenString);

            $constraints = $this->config->validationConstraints();
            if (!$this->config->validator()->validate($token, ...$constraints)) {
                return null; // Token invalide
            }

            return $token;
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            return null;
        }
    }
}