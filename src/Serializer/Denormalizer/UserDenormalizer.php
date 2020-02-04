<?php
declare(strict_types=1);

namespace App\Serializer\Denormalizer;

use App\Entity\User;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\Serializer\Exception\BadMethodCallException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Exception\ExtraAttributesException;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\LogicException;
use Symfony\Component\Serializer\Exception\RuntimeException;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class UserDenormalizer implements DenormalizerInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    public const SUPPORTED_PROPERTY = [
        'id',
        'email',
        'roles'
    ];

    /**
     * @param mixed $data
     * @param string $type
     * @param string|null $format
     * @param array<mixed> $context
     * @return User
     */
    public function denormalize($data, $type, $format = null, array $context = []): User
    {
        if ($type !== User::class) {
            throw new LogicException('denormalizer is not compatible with type '.$type);
        }

        $user = new User();

        foreach ($data as $key => $value) {
            if (in_array($key, self::SUPPORTED_PROPERTY, true)) {
                if (is_array($value)) {
                    $this->useAdder($user, $key, $value);
                } else {
                    $this->useSetter($user, $key, $value);
                }
            } else {
                throw new ExtraAttributesException([$key => $value]);
            }
        }

        return $user;
    }

    public function supportsDenormalization($data, $type, $format = null): bool
    {
        return $type === User::class;
    }

    /**
     * @param User $user
     * @param string $key
     * @param mixed $value
     */
    private function useSetter(User $user, string $key, $value): void
    {
        $setter = 'set'.ucfirst($key);
        if (method_exists($user, $setter)) {
            try {
                $user->$setter($value);
            } catch (\Exception $exception) {
                throw new UnexpectedValueException($exception->getMessage());
            }
        } else {
            throw new RuntimeException(sprintf('unable to find setter %s on object type %s', $setter, User::class));
        }
    }

    /**
     * @param User $user
     * @param string $key
     * @param array<mixed> $value
     */
    private function useAdder(User $user, string $key, array $value): void
    {
        $adder = 'add'.ucfirst($key);
        if (method_exists($user, $adder)) {
            try {
                foreach ($value as $itemValue) {
                    $user->$adder($itemValue);
                }
            } catch (\Exception $exception) {
                throw new UnexpectedValueException($exception->getMessage());
            }
        } else {
            throw new RuntimeException(sprintf('unable to find setter %s on object type %s', $adder, User::class));
        }
    }
}
