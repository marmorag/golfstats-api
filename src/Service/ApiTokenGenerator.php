<?php

namespace App\Service;

class ApiTokenGenerator
{
    private $prefix = 'mygstatapi-token:';

    public function generate() : string
    {
        return uniqid($this->prefix, true);
    }
}
