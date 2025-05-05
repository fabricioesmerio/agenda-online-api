<?php
namespace App\Repositories;

use App\Models\Appointment;

class AppointmentRepository
{
    public function create(array $data): Appointment
    {
        return Appointment::create($data);
    }

    public function allForUser(string $userId, string $tenantId)
    {
        return Appointment::where('user_id', $userId)
                          ->where('tenant_id', $tenantId)
                          ->get();
    }
}