<?php

namespace App\Services;

use App\Models\Appointment;
use App\Repositories\AppointmentRepository;
use Illuminate\Http\Request;

class AppointmentService
{
    public function __construct(
        protected AppointmentRepository $repository
    ) {}

    public function create(array $data)
    {
        return $this->repository->create($data);
    }

    public function list(Request $request, string $userId, string $tenantId)
    {
        return $this->repository->allForUser($request, $userId, $tenantId);
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
