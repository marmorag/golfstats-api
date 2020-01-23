<?php

namespace App\Serializer;

use App\Entity\Course;
use App\Entity\Hole;
use App\Entity\Landmark;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class CourseNormalizer implements DenormalizerInterface
{

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null): bool
    {
        return $type === Course::class
            && $format === 'json';
    }

    /**
     * @param array<string, mixed> $data
     * @param string $class
     * @param string|null $format
     * @param array<mixed> $context
     * @return Course
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
                $normalizedLandmark = $this->denormalizeLandmark($landmark);
                $course->addLandmark($normalizedLandmark);
            }
        }

        return $course;
    }

    // TODO use normalizer for each landmark and hole object

    /**
     * @param mixed $data
     * @return Landmark
     */
    private function denormalizeLandmark($data): Landmark
    {
        $landmark = new Landmark();

        if (isset($data['id']) && $data['id'] !== '') {
            $landmark->setId((int)$data['id']);
        }

        $landmark->setName($data['name'] ?? '');
        $landmark->setSssLady($data['sssLady'] ?? 0.0);
        $landmark->setSssMen($data['sssMen'] ?? 0.0);
        $landmark->setSlopeLady($data['slopeLady'] ?? 0.0);
        $landmark->setSlopeMen($data['slopeMen'] ?? 0.0);

        if (isset($data['holes'])) {
            $holes = new ArrayCollection();
            foreach ($data['holes'] as $hole) {
                $holes->add($this->denormalizeHole($hole));
            }

            $landmark->setHoles($holes);
        }

        return $landmark;
    }

    /**
     * @param mixed $data
     * @return Hole
     */
    private function denormalizeHole($data): Hole
    {
        $hole = new Hole();

        if (isset($data['id']) && $data['id'] !== '') {
            $hole->setId((int)$data['id']);
        }

        $hole->setLength($data['length'] ?? 0);
        $hole->setPar($data['par'] ?? 0);
        $hole->setNumber($data['number'] ?? 0);
        $hole->setHandicap($data['handicap'] ?? 0);

        return $hole;
    }
}
