<?php

namespace App\Services;

use App\Models\Appointment;
use App\Repositories\AppointmentRepository;

class AppointmentService
{
    public function __construct(
        protected AppointmentRepository $repository
    ) {}

    public function create(array $data)
    {
        return $this->repository->create($data);
    }

    public function list(string $userId, string $tenantId, ?string $startDate = null, ?string $endDate = null)
    {
        return $this->repository->allForUser($userId, $tenantId, $startDate, $endDate);
    }

    public function update(Appointment $appointment, array $data): Appointment
    {
        $appointment->update($data);
        return $appointment;
    }

    public function delete(Appointment $appointment): void
    {
        $this->repository->delete($appointment);
    }
}
