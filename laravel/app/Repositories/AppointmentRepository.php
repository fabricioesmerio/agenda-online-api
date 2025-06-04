<?php

namespace App\Repositories;

use App\Filters\AppointmentFilter;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentRepository
{
    public function create(array $data): Appointment
    {
        return Appointment::create($data);
    }

    public function allForUser(Request $request, string $userId, string $tenantId)
    {
        $query = Appointment::query()
            ->where('user_id', $userId)
            ->where('tenant_id', $tenantId);

        $filter = new AppointmentFilter($request);
        $query = $filter->apply($query);

        return $query->orderBy('start_time')->get();
    }

    public function delete(Appointment $appointment): void
    {
        $appointment->delete();
    }
}
