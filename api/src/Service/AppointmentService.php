<?php

namespace App\Service;

use App\DTO\AppointmentDTO;
use App\Entity\Appointment;
use Doctrine\ORM\EntityManagerInterface;
use App\DTO\Filters\AppointmentFilterDTO;
use App\Repository\AppointmentRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class AppointmentService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private AppointmentRepository $appointmentRepository,
        private ServiceService $serviceService,
        private ResourceService $resourceService,
        private SalonService $salonService,
        private UserService $userService,
        private EntityHydratorService $hydrator,
        private Security $security,
    ) {}

    public function searchAppointments(AppointmentFilterDTO $filters): array
    {
        return $this->appointmentRepository->findByFilters($filters);
    }

    public function getAppointment(int $id): Appointment
    {
        $appointment = $this->appointmentRepository->find($id);

        if ($appointment === null) {
            throw new \InvalidArgumentException('Agendamento não encontrado');
        }

        return $appointment;
    }

    public function createAppointment(AppointmentDTO $appointmentDTO): Appointment
    {
        $appointment = $this->hydrator->hydrate(new Appointment(), $appointmentDTO);

        $salon = $this->salonService->getSalon($appointmentDTO->salonId);
        $appointment->setSalon($salon);

        $service = $this->serviceService->getService($appointmentDTO->serviceId);
        $appointment->setService($service);

        $resource = $this->resourceService->getResource($appointmentDTO->resourceId);
        $appointment->setResource($resource);

        $client = $this->userService->getUser($appointmentDTO->clientId);
        $appointment->setClient($client);

        // Check if the salon is opened
        if (!$this->salonService->isOpen($salon, $appointment->getStartsAt())) {
            throw new \InvalidArgumentException('O salão está fechado nesse horário');
        }

        // Check if the resource is available
        if (!$this->resourceService->isAvailable($resource, $appointment->getStartsAt())) {
            throw new \InvalidArgumentException('O recurso não está disponível nesse horário');
        }

        // Check if the resource is not booked
        $filters = [
            'resourceId' => $resource->getId(),
            'startsAt' => $appointment->getStartsAt()->setTime(0, 0)->format('d/m/Y, H:i'),
            'limit' => 100,
        ];
        $appointmentFilterDTO = new AppointmentFilterDTO($filters);
        $appointments = $this->searchAppointments($appointmentFilterDTO)['data'];

        $appointments = array_filter(
            $appointments,
            function (Appointment $temporaryAppointment) use ($appointment) {

                $startsBeforeBooked = $appointment->getStartsAt() < $temporaryAppointment->getStartsAt();
                $startsAfterBooked = $appointment->getStartsAt() >= $temporaryAppointment->getEndsAt();
                $endsBeforeBooked = $appointment->getEndsAt() <= $temporaryAppointment->getStartsAt();
                $endsAfterBooked = $appointment->getEndsAt() > $temporaryAppointment->getEndsAt();

                if ($startsBeforeBooked && $endsBeforeBooked) {
                    return false;
                }
                if ($startsAfterBooked && $endsAfterBooked) {
                    return false;
                }

                return true;
            }
        );

        if (count($appointments) > 0) {
            throw new \InvalidArgumentException('O recurso já está agendado nesse horário');
        }

        $this->entityManager->persist($appointment);
        $this->entityManager->flush();

        return $appointment;
    }

    public function updateAppointment(Appointment $appointment, AppointmentDTO $appointmentDTO): Appointment
    {
        $this->hydrator->hydrate($appointment, $appointmentDTO);

        if ($appointmentDTO->serviceId) {
            $service = $this->serviceService->getService($appointmentDTO->serviceId);
            $appointment->setService($service);
        }

        if ($appointmentDTO->resourceId) {
            $resource = $this->resourceService->getResource($appointmentDTO->resourceId);
            $appointment->setResource($resource);
        }

        $this->entityManager->flush();

        return $appointment;
    }

    public function deleteAppointment(Appointment $appointment): void
    {
        $this->entityManager->remove($appointment);
        $this->entityManager->flush();
    }
}
