<?php declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasPositionAttribute;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 */
class TestSetup extends Model
{
    use HasPositionAttribute;

    /**
     * @var string
     */
    protected $table = 'test_setups';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'type',
        'values',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'values' => 'array',
    ];

    /**
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * @return array
     */
    public function getPositionGroupColumn()
    {
        return ['test_step_id'];
    }
}
