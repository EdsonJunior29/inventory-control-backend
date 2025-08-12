<?php

namespace App\Domain\IRepository;

use App\Models\Status;

interface IStatusRepository
{
    public function getStatusById(int $statusId): ?Status;
}