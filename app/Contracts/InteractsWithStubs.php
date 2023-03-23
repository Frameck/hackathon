<?php

namespace App\Contracts;

interface InteractsWithStubs
{
    public function setType();

    public function setNamespace();

    public function setStubName();
}
