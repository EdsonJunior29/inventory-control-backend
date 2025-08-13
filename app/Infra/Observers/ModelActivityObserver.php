<?php

namespace App\Infra\Observers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ModelActivityObserver
{
    public function created($model)
    {
        $attributes = $model->getAttributes();
        unset($attributes['created_at'], $attributes['updated_at']);

        $this->log($model, 'created', $attributes);
    }

    public function updated($model)
    {
        $changes = $model->getChanges();
        unset($changes['updated_at']);

        if (!empty($changes)) {
            $this->log($model, 'updated', $changes);
        }
    }

    public function deleted($model)
    {
        $this->log($model, 'deleted');
    }

    private function log($model, string $action, array $changes = null)
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'model' => get_class($model),
            'model_id' => $model->getKey(),
            'action' => $action,
            'changes' => $changes ? json_encode($changes) : null,
            'created_at' => now(),
        ]);
    }
}