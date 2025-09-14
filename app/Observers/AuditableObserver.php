<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuditableObserver
{
    protected function log(Model $model, string $action, ?array $before, ?array $after): void {
        DB::table('audit_logs')->insert([
            'user_id' => optional(Auth::user())->id,
            'table_name' => $model->getTable(),
            'record_id' => $model->getKey() ?? 0,
            'action' => $action,
            'before_json' => $before ? json_encode($before) : null,
            'after_json' => $after ? json_encode($after) : null,
            'created_at' => now(), 'updated_at' => now(),
        ]);
    }
    public function created(Model $model): void { $this->log($model,'created',null,$model->getAttributes()); }
    public function updated(Model $model): void { $this->log($model,'updated',$model->getOriginal(),$model->getAttributes()); }
    public function deleted(Model $model): void { $this->log($model,'deleted',$model->getOriginal(),null); }
}
