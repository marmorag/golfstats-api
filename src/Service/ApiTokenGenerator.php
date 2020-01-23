<?php

namespace App\Service;

class ApiTokenGenerator
{
    private string $prefix = 'mygstatapi-token:';

    public function generate() : string
    {
        return uniqid($this->prefix, true);
    }
}
