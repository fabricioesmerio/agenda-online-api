<?php

namespace App\Http\Controllers;

use App\Services\StatusService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class StatusController extends Controller
{

    public function __construct(private StatusService $service) {}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'color' => 'required|string|max:7',
        ]);

        $tenantId = Auth::user()->tenant_id;
        $validated['tenant_id']= $tenantId;

        return response()->json($this->service->create($validated), 201);
    }

    public function index()
    {
        $tenantId = Auth::user()->tenant_id;
        return response()->json($this->service->getAll($tenantId));
    }

    public function getById(string $id)
    {
        $tenantId = Auth::user()->tenant_id;
        return response()->json($this->service->getOne($tenantId, $id));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'description' => 'string|max:255',
            'color' => 'string|max:7'
        ]);
        $tenantId = Auth::user()->tenant_id;
        return response()->json($this->service->update($id, $validated, $tenantId));
    }
}
