<?php
namespace App\Repositories;

use App\Models\Status;
use Illuminate\Support\Str;

class StatusRepository
{
    public function create(array $data) : Status 
    {
        $data['id'] = Str::uuid();
        return Status::create($data);
    }
    
    public function update(Status $status, array $data) : Status 
    {
        $status->update($data);
        return $status;
    }

    public function getAll(string $tenantId)
    {
        $query = Status::query()
        ->where('tenant_id', $tenantId);

        return $query->orderBy('description')->get();
    }
    
    public function getOne(string $tenantId, string $id): ?Status
    {
        $query = Status::query()
        ->where('id', $id)
        ->where('tenant_id', $tenantId);

        return $query->orderBy('description')->first();
    }
}