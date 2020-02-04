<?php
declare(strict_types=1);

namespace App\Serializer\Denormalizer;

use App\Entity\Hole;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class HoleDenormalizer implements DenormalizerInterface
{
    public function supportsDenormalization($data, $type, $format = null): bool
    {
        return $type === Hole::class && in_array($format, ['json', 'application/json'], true);
    }

    public function denormalize($data, $type, $format = null, array $context = [])
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
