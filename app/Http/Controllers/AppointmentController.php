<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppointmentRequest;
use App\Models\Appointment;
use App\Services\AppointmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;

class AppointmentController extends Controller
{
    public function __construct(
        protected AppointmentService $service
    ) {}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        $user = Auth::user();

        $appointment = $this->service->create([
            ...$validated,
            'user_id' => $user->id,
            'tenant_id' => $user->tenant_id,
        ]);

        return response()->json($appointment, 201);
    }

    public function index()
    {
        $user = Auth::user();
        return response()->json(
            $this->service->list($user->id, $user->tenant_id)
        );
    }

    public function update(AppointmentRequest $request, string $id)
    {
        $appointment = Appointment::findOrFail($id);        

        if (Auth::user()->id !== $appointment->user_id) {
            return response()->json(['message' => 'Você não tem permissão para editar este compromisso.'], 403);
        }

        $updated = $this->service->update($appointment, $request->validated());

        return response()->json($updated);
    }
}
