<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\Admin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityLogService
{
    public function record(
        string $action,
        ?Model $entity = null,
        array $metadata = [],
        ?Admin $admin = null,
        string $actorType = 'admin'
    ): ActivityLog {
        $admin ??= Auth::guard('admin')->user();

        return ActivityLog::create([
            'admin_id' => $admin?->getKey(),
            'actor_type' => $actorType,
            'action' => $action,
            'entity_type' => $entity ? get_class($entity) : null,
            'entity_id' => $entity?->getKey(),
            'metadata' => $metadata,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    public function recordForModel(
        string $action,
        Model $entity,
        array $metadata = [],
        ?Admin $admin = null,
        string $actorType = 'admin'
    ): ActivityLog {
        return $this->record($action, $entity, $metadata, $admin, $actorType);
    }
}
