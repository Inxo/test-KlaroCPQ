<?php

namespace App\MagicLogic;

use App\Service\DataInterface;

class JustChangesMagic implements MagicLogicInterface
{
    public function doIt(DataInterface $data, $changes): DataInterface
    {
        $data->setDataModify($changes);

        return $data;
    }
}