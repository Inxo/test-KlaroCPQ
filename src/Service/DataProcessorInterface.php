<?php

namespace App\Service;

use App\Service\FakeServer\ErrorException;
use App\Service\FakeServer\NotModifiedException;

interface DataProcessorInterface
{
    /**
     * @param array $data
     * @throws NotModifiedException
     * @throws ErrorException
     * @return array
     */
    public function requestChanges(array $data): array;
}