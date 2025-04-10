<?php

namespace App\Controller;

use App\DTO\ClientDTO;
use App\Entity\User;
use App\Service\ClientService;
use App\Service\FilterService;
use App\DTO\Filters\ClientFilterDTO;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;

#[Route('/api/clients', name: 'clients_')]
class ClientController extends AbstractController
{
    public function __construct(
        private ClientService $clientService,
        private FilterService $filterService,
        private LoggerInterface $logger,
    ) {}

    #[Route('/', name: 'create', methods: ['POST'])]
    public function create(#[MapRequestPayload()] ClientDTO $clientDTO): JsonResponse
    {
        try {
            $this->clientService->createClient($clientDTO);
        } catch (\InvalidArgumentException $e) {
            return $this->json([
                'status' => 'error',
                'data' => [
                    'message' => $e->getMessage(),
                ],
            ], JsonResponse::HTTP_BAD_REQUEST);
        }
        return $this->json([
            'status' => 'success',
            'data' => [
                'message' => 'Client created successfully.',
            ],
        ], JsonResponse::HTTP_CREATED);
    }

    #[Route('/', name: 'search', methods: ['GET'])]
    public function search(Request $request): JsonResponse
    {
        try {
            $filters = $this->filterService->createDtoFromRequest(ClientFilterDTO::class, $request->query->all());
            $data = $this->clientService->searchClients($filters);
        } catch (\InvalidArgumentException $e) {
            return $this->json([
                'status' => 'error',
                'data' => [
                    'message' => $e->getMessage(),
                ],
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        return $this->json([
            'status' => 'success',
            'data' => [
                'clients' => array_map(fn(User $client) => $client->toArrayClient(), $data['data']),
                'settings' => $data['settings'],
            ],
        ], JsonResponse::HTTP_OK);
    }
}
