<?php

namespace App\Service;


use App\Serializer\GolfNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;

class ObjectSerializerGetter
{

    private static $serializer;

    /**
     * @return Serializer
     */
    public static function getSerializer(): Serializer
    {
        if (!isset(self::$serializer)) {
            $dtnConfig = array(
                'datetime_format' => $_ENV['DATETIME_FORMAT']
            );
            $dtnTimeZone = new \DateTimeZone($_ENV['DATETIME_TIMEZ']);

            self::$serializer = new Serializer(
                [new GolfNormalizer(), new DateTimeNormalizer($dtnConfig, $dtnTimeZone), new GetSetMethodNormalizer()],
                [new JsonEncoder()]
            );
        }
        return  self::$serializer;
    }

}