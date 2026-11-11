<?php

namespace App\Models\Concerns;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

/**
 * Journalise création, modification et suppression.
 * Le modèle doit implémenter auditVenueId().
 */
trait Auditable
{
    protected static function bootAuditable(): void
    {
        static::created(fn (Model $m) => static::writeAudit($m, 'created', $m->getAttributes()));
        static::updated(fn (Model $m) => static::writeAudit($m, 'updated', $m->getChanges()));
        static::deleted(fn (Model $m) => static::writeAudit($m, 'deleted', $m->getOriginal()));
    }

    protected static function writeAudit(Model $model, string $action, array $data): void
    {
        $data = Arr::except($data, ['created_at', 'updated_at', 'remember_token', 'password']);

        if ($action === 'updated' && empty($data)) {
            return;
        }

        AuditLog::create([
            'venue_id' => $model->auditVenueId(),
            'user_id' => Auth::id(),
            'action' => $action,
            'auditable_type' => $model->getMorphClass(),
            'auditable_id' => $model->getKey(),
            'changes' => $data,
            'ip_address' => request()?->ip(),
        ]);
    }
}
