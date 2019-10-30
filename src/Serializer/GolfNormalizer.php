<?php

namespace App\Serializer;

use App\Entity\Course;
use App\Entity\Golf;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class GolfNormalizer implements DenormalizerInterface
{
    private $courseDenormalizer;
    private $contactDenormalizer;

    public function __construct()
    {
        $this->courseDenormalizer = new CourseNormalizer();
        $this->contactDenormalizer = new ContactNormalizer();
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null): bool
    {
        return $type === Golf::class
            && $format === 'json';
    }

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $class, $format = null, array $context = [])
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
            $golf->setContact($this->contactDenormalizer->denormalize($data['contact'], 'json'));
        }

        if (isset($data['courses'])) {
            foreach ($data['courses'] as $course) {
                /** @var Course $normalizedCourse */
                $normalizedCourse = $this->courseDenormalizer->denormalize($course, 'json');
                $golf->addCourse($normalizedCourse);
            }
        }

        return $golf;
    }
}
