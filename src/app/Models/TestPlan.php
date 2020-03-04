<?php declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @mixin \Eloquent
 */
class TestPlan extends Pivot
{
    use HasUuid;

    /**
     * @var string
     */
    protected $table = 'test_plans';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $with = [
        'case',
        'session',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function case()
    {
        return $this->belongsTo(TestCase::class, 'case_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function steps()
    {
        return $this->hasManyThrough(TestStep::class, TestCase::class, 'id', 'case_id', 'case_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function session()
    {
        return $this->belongsTo(TestSession::class, 'session_id');
    }

    /**
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'uuid';
    }

    /**
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value)
    {
        return $this->where($this->getRouteKeyName(), $value)->whereHas('session')->firstOrFail() ?? abort(404);
    }
}
