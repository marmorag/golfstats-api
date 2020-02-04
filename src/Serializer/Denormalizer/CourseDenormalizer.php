<?php

namespace App\Serializer\Denormalizer;

use App\Entity\Course;
use App\Entity\Landmark;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class CourseDenormalizer implements DenormalizerInterface, DenormalizerAwareInterface
{
    use DenormalizerAwareTrait;

    public function supportsDenormalization($data, $type, $format = null): bool
    {
        return $type === Course::class
            && $format === 'json';
    }

    /**
     * @param mixed $data
     * @param string $class
     * @param string|null $format
     * @param array<mixed> $context
     * @return Course
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function denormalize($data, $class, $format = null, array $context = []): Course
    {
        $course = new Course();

        if (isset($data['id']) && $data['id'] !== '') {
            $course->setId((int)$data['id']);
        }

        $course->setName($data['name'] ?? '');

        if (isset($data['landmarks'])) {
            foreach ($data['landmarks'] as $landmark) {
                /** @var Landmark $normalizedLandmark */
                $normalizedLandmark = $this->denormalizer->denormalize($landmark, Landmark::class, $format, $context);
                $course->addLandmark($normalizedLandmark);
            }
        }

        return $course;
    }
}
