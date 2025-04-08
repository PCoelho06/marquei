<?php

namespace App\Controller;

use App\DTO\AppointmentDTO;
use App\Entity\Appointment;
use App\Service\FilterService;
use App\Service\AppointmentService;
use App\DTO\Filters\AppointmentFilterDTO;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;

#[Route('/api/appointments', name: 'appointments_')]
class AppointmentController extends AbstractController
{
    public function __construct(
        private AppointmentService $appointmentService,
        private FilterService $filterService
    ) {}

    #[Route('/', name: 'search', methods: ['GET'])]
    public function search(Request $request): JsonResponse
    {
        try {
            $filters = $this->filterService->createDtoFromRequest(AppointmentFilterDTO::class, $request->query->all());
            $data = $this->appointmentService->searchAppointments($filters);
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
                'appointments' => array_map(fn(Appointment $appointment) => $appointment->toArraySimple(), $data['data']),
                'settings' => $data['settings'],
            ],
        ], JsonResponse::HTTP_OK);
    }

    #[Route('/{id}', name: 'get', methods: ['GET'])]
    public function get(Appointment $appointment): JsonResponse
    {
        return $this->json([
            'status' => 'success',
            'data' => [
                'appointment' => $appointment->toArray(),
            ],
        ], JsonResponse::HTTP_OK);
    }

    #[Route('/', name: 'create', methods: ['POST'])]
    public function create(#[MapRequestPayload(validationGroups: ['create'])] AppointmentDTO $appointmentDTO): JsonResponse
    {
        try {
            $appointment = $this->appointmentService->createAppointment($appointmentDTO);
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
                'appointment' => $appointment->toArray(),
            ],
        ], JsonResponse::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    public function update(Appointment $appointment, #[MapRequestPayload()] AppointmentDTO $appointmentDTO): JsonResponse
    {
        try {
            $updatedAppointment = $this->appointmentService->updateAppointment($appointment, $appointmentDTO);
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
                'appointment' => $updatedAppointment->toArray(),
            ],
        ], JsonResponse::HTTP_OK);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Appointment $appointment): JsonResponse
    {
        try {
            $this->appointmentService->deleteAppointment($appointment);
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
                'message' => 'Agendamento removido com sucesso',
            ],
        ], JsonResponse::HTTP_OK);
    }
}
