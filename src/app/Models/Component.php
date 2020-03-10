<?php declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasPosition;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 */
class Component extends Model
{
    use HasPosition;

    /**
     * @var string
     */
    protected $table = 'components';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'api_service_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function scenario()
    {
        return $this->belongsTo(Scenario::class, 'scenario_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function apiService()
    {
        return $this->hasOne(ApiService::class, 'api_service_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function paths()
    {
        return $this->belongsToMany(static::class, 'component_paths', 'source_id', 'target_id')
            ->using(ComponentPath::class)
            ->withPivot('simulated');
    }

    /**
     * @return array
     */
    public function getPositionGroupColumn()
    {
        return ['scenario_id'];
    }
}
