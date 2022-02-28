<?php

namespace App\MagicLogic;

use App\Service\DataInterface;

class MergeMagic implements MagicLogicInterface
{
    public function doIt(DataInterface $data, $changes): DataInterface
    {
        $data->setDataModify(array_replace($data->getData(),$changes));

        return $data;
    }
}