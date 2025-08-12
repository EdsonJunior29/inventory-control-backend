<?php

namespace App\Infra\Repositories\Status;

use App\Domain\IRepository\IStatusRepository;
use App\Models\Status;

class StatusRepository implements IStatusRepository
{
    public function getStatusById(int $statusId): ?Status
    {
        return Status::select(['id', 'name'])->find($statusId);
    }
}