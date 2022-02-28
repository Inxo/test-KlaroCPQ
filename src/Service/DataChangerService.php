<?php

namespace App\Service;

use App\Entity\SuperData;
use App\MagicLogic\MagicLogicInterface;
use App\Service\FakeServer\NotModifiedException;
use Psr\Log\LoggerInterface;

class DataChangerService
{
    private DataProcessorInterface $fakeServerApi;
    private LoggerInterface $logger;

    public function __construct(DataProcessorInterface $fakeServerApi, LoggerInterface $logger)
    {
        $this->fakeServerApi = $fakeServerApi;
        $this->logger = $logger;
    }

    /**
     * @param array $data
     * @param MagicLogicInterface $logic
     * @return SuperData
     * @throws FakeServer\ErrorException
     */
    public function process(array $data, MagicLogicInterface $logic): SuperData
    {
        try {
            $changes = $this->fakeServerApi->requestChanges($data);
        } catch (NotModifiedException $e) {
            $this->logger->info($e->getMessage());
            $changes = [];
        }

        $superData = new SuperData();
        $superData->setData($data);

        $logic->doit($superData, $changes);

        return $superData;
    }
}