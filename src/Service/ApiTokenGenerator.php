<?php

namespace App\Service;


class ApiTokenGenerator
{

    private $prefix;

    public function __construct()
    {
        $this->prefix = 'mygstatapi-token:';
    }

    public function generate() : string
    {
        return uniqid($this->prefix, true);
    }
}