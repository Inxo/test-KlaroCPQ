<?php

namespace App\EntityListener;

use App\Entity\SuperData;

class SuperDataEntityListener
{
    public function prePersist(SuperData $superData)
    {
        $superData->setTimestamp();
    }
}