<?php

namespace App\Repositories;

use App\Models\ActivityLog;
use App\Repositories\Contracts\ActivityLogRepositoryInterface;

class ActivityLogRepository implements ActivityLogRepositoryInterface
{
    public function getAll(array $filters = [])
    {
        $query = ActivityLog::with('user')->latest();

        if (! empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (! empty($filters['action'])) {
            $query->where('action', $filters['action']);
        }

        if (! empty($filters['from'])) {
            $query->whereDate('created_at', '>=', $filters['from']);
        }

        if (! empty($filters['to'])) {
            $query->whereDate('created_at', '<=', $filters['to']);
        }

        return $query->get();
    }

    public function create(array $data)
    {
        return ActivityLog::create($data);
    }

    public function latest(int $limit)
    {
        return ActivityLog::with('user')->latest()->limit($limit)->get();
    }

    public function count()
    {
        return ActivityLog::count();
    }
}
