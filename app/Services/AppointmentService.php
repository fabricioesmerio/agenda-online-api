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

    public function list(string $userId, string $tenantId)
    {
        return $this->repository->allForUser($userId, $tenantId);
    }

    public function update(Appointment $appointment, array $data): Appointment
    {
        $appointment->update($data);
        return $appointment;
    }
}
