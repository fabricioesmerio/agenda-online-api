<?php

namespace App\Services;

use App\Models\Status;
use App\Repositories\StatusRepository;

class StatusService
{
    public function __construct(
        protected StatusRepository $repository
    ) {}

    public function create(array $data)
    {
        return $this->repository->create($data);
    }

    public function getAll(string $tenantId)
    {
        return $this->repository->getAll($tenantId);
    }

    public function getOne(string $tenantId, string $id)
    {
        return $this->repository->getOne($tenantId, $id);
    }

    public function update(string $id, array $data, string $tenantId): Status
    {
        $status = $this->repository->getOne($tenantId, $id);

        if (!$status) {
            abort(404, 'Status não encontrado.');
        }

        return $this->repository->update($status, $data);
    }
}
