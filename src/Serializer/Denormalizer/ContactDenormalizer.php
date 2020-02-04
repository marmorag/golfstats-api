<?php

namespace App\Serializer\Denormalizer;

use App\Entity\Contact;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class ContactDenormalizer implements DenormalizerInterface
{
    public function supportsDenormalization($data, $type, $format = null): bool
    {
        return $type === Contact::class && $format === 'json';
    }

    public function denormalize($data, $class, $format = null, array $context = []): Contact
    {
        $contact = new Contact();

        $contact->setName($data['name']);
        $contact->setAddress($data['address']);
        $contact->setCity($data['city']);
        $contact->setPostalCode($data['postalCode']);
        $contact->setTelephoneNumber($data['telephoneNumber']);

        return $contact;
    }
}
