<?php

namespace App\Interfaces;

interface RequestInterface
{
    public function getUrl();
    public function getMethod();
}