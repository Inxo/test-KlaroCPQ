<?php

namespace App\Controller;

use App\MagicLogic\MagicLogicInterface;
use App\Repository\SuperDataRepository;
use App\Service\DataChangerService;
use App\Service\FakeServer\ErrorException;
use App\Service\FakeServer\FakeServerApi;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('app/index.html.twig');
    }

    #[Route('/api/', name: 'fetch', methods: 'GET')]
    public function fetch(SuperDataRepository $superDataRepository): JsonResponse
    {
        $items = $superDataRepository->findAll();
        return $this->json($items);
    }

    #[Route('/api/', name: 'send', methods: 'POST')]
    public function send(Request $request, FakeServerApi $fakeServerApi, SuperDataRepository $superDataRepository, MagicLogicInterface $logic): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return $this->json(['error' => 'Wrong data'], 400);
        }

        $service = new DataChangerService($fakeServerApi, $this->logger);

        try {
            $superData = $service->process($data, $logic);
        } catch (ErrorException $e) {
            $this->logger->error($e->getMessage());
            return $this->json(['error' => $e->getMessage()], 400);
        }

        $superDataRepository->add($superData);

        return $this->json($superData);
    }

}