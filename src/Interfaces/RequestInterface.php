<?php

namespace App\Interfaces;

interface RequestInterface
{
    public function getUri();
    public function getMethod();
}