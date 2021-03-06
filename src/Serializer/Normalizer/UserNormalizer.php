<?php
declare(strict_types=1);

namespace App\Serializer\Normalizer;

use App\Entity\User;
use Symfony\Component\Serializer\Exception\CircularReferenceException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\LogicException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class UserNormalizer implements NormalizerInterface
{
    /**
     * @param mixed $user
     * @param string|null $format
     * @param array<mixed> $context
     * @return array<string, string>
     */
    public function normalize($user, $format = null, array $context = []): array
    {
        return [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles()
        ];
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof User;
    }
}
