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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function apiService()
    {
        return $this->belongsTo(ApiService::class, 'api_service_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function connections()
    {
        return $this->belongsToMany(static::class, 'component_connections', 'source_id', 'target_id');
    }

    /**
     * @return bool
     */
    public function getSimulatedAttribute()
    {
        return $this->apiService()->exists();
    }

    /**
     * @return array
     */
    public function getPositionGroupColumn()
    {
        return ['scenario_id'];
    }
}
