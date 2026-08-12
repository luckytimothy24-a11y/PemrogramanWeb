<?php

namespace App\Services;

use App\Repositories\Contracts\ActivityLogRepositoryInterface;

class ActivityLogService
{
    protected $activityLogRepository;

    public function __construct(ActivityLogRepositoryInterface $activityLogRepository)
    {
        $this->activityLogRepository = $activityLogRepository;
    }

    public function log(string $action, string $description): void
    {
        $this->activityLogRepository->create([
            'user_id' => auth()->id(),
            'action' => $action,
            'description' => $description,
        ]);
    }

    public function getAll(array $filters = [])
    {
        return $this->activityLogRepository->getAll($filters);
    }

    public function latest(int $limit = 10)
    {
        return $this->activityLogRepository->latest($limit);
    }
}
