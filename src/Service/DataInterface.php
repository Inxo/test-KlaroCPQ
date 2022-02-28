<?php

namespace App\Service;

interface DataInterface
{
    public function getData(): array;
    public function setDataModify(array $data);
}