<?php

namespace App\Repositories;

use App\Models\Appointment;

class AppointmentRepository
{
    public function create(array $data): Appointment
    {
        return Appointment::create($data);
    }

    public function allForUser(string $userId, string $tenantId, ?string $startDate = null, ?string $endDate = null)
    {
        $query = Appointment::where('user_id', $userId)
            ->where('tenant_id', $tenantId);

        if ($startDate && $endDate) {
            $query->whereBetween('start_time', [$startDate, $endDate]);
        }

        return $query->orderBy('start_time')->get();
    }

    public function delete(Appointment $appointment): void
    {
        $appointment->delete();
    }
}
