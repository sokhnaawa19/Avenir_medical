<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use App\Models\Concerns\Sortable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Une agence : déjà ouverte, en cours d'ouverture, ou prévue.
 * Sert à montrer le développement de l'entreprise au Sénégal
 * et dans la sous-région.
 */
class Agency extends Model
{
    public const STATUT_OUVERTE = 'ouverte';

    public const STATUT_EN_COURS = 'en cours';

    public const STATUT_PROJET = 'projet';

    use HasTranslations;
    use Sortable;

    /** Champs proposés à la traduction. */
    protected array $translatable = ['name', 'description'];

    protected $fillable = [
        'name', 'region', 'country', 'address', 'phone',
        'status', 'opening_year', 'description', 'image', 'position', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'position' => 'integer',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function statuses(): array
    {
        return [
            self::STATUT_OUVERTE => 'Agence ouverte',
            self::STATUT_EN_COURS => 'Ouverture en cours',
            self::STATUT_PROJET => 'Projet à venir',
        ];
    }

    public function statusLabel(): string
    {
        return self::statuses()[$this->status] ?? $this->status;
    }

    public function statusColor(): string
    {
        return match ($this->status) {
            self::STATUT_OUVERTE => 'green',
            self::STATUT_EN_COURS => 'orange',
            default => 'blue',
        };
    }

    public function scopeOpened(Builder $query): Builder
    {
        return $query->where('status', self::STATUT_OUVERTE);
    }

    public function isInSenegal(): bool
    {
        return mb_strtolower(trim((string) $this->country)) === 'sénégal';
    }
}
