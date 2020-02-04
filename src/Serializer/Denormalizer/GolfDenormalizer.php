<?php

namespace App\Serializer\Denormalizer;

use App\Entity\Contact;
use App\Entity\Course;
use App\Entity\Golf;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class GolfDenormalizer implements DenormalizerInterface, DenormalizerAwareInterface
{
    use DenormalizerAwareTrait;

    public function supportsDenormalization($data, $type, $format = null): bool
    {
        return $type === Golf::class && $format === 'json';
    }

    /**
     * @param mixed $data
     * @param string $class
     * @param string|null $format
     * @param array<mixed> $context
     * @return Golf
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function denormalize($data, $class, $format = null, array $context = []): Golf
    {
        if ($format !== 'json') {
            throw new InvalidArgumentException('Only JSON format is supported by this denormalizer.');
        }

        $golf = new Golf();

        if (isset($data['id']) && $data['id'] !== '') {
            $golf->setId($data['id']);
        }
        $golf->setName($data['name'] ?? '');

        if (isset($data['contact'])) {
            /** @var Contact $contact */
            $contact = $this->denormalizer->denormalize($data['contact'], 'json');
            $golf->setContact($contact);
        }

        if (isset($data['courses'])) {
            foreach ($data['courses'] as $course) {
                /** @var Course $normalizedCourse */
                $normalizedCourse = $this->denormalizer->denormalize($course, 'json');
                $golf->addCourse($normalizedCourse);
            }
        }

        return $golf;
    }
}
