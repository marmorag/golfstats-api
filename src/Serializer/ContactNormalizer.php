<?php

namespace App\Serializer;


use App\Entity\Contact;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class ContactNormalizer implements DenormalizerInterface
{

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $class, $format = null, array $context = [])
    {
        $contact = new Contact();

        $contact->setName($data['name']);
        $contact->setAddress($data['address']);
        $contact->setCity($data['city']);
        $contact->setPostalCode($data['postalCode']);
        $contact->setTelephoneNumber($data['telephoneNumber']);

        return $contact;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null): bool
    {
        return $type === Contact::class && $format === 'json';
    }
}