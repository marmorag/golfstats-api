<?php
declare(strict_types=1);

namespace App\Serializer\Denormalizer;

use App\Entity\Hole;
use App\Entity\Landmark;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class LandmarkDenormalizer implements DenormalizerInterface, DenormalizerAwareInterface
{
    use DenormalizerAwareTrait;

    public function supportsDenormalization($data, $type, $format = null): bool
    {
        return $type === Landmark::class && in_array($format, ['json', 'application/json'], true);
    }

    /**
     * @param mixed $data
     * @param string $type
     * @param string|null $format
     * @param array<mixed> $context
     * @return Landmark
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function denormalize($data, $type, $format = null, array $context = []): Landmark
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
                $holes->add($this->denormalizer->denormalize($hole, Hole::class, $format, $context));
            }

            $landmark->setHoles($holes);
        }

        return $landmark;
    }
}
