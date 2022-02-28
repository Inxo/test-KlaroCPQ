<?php

namespace App\MagicLogic;

use App\Service\DataInterface;

interface MagicLogicInterface
{
    public function doIt(DataInterface $data, array $changes);

}