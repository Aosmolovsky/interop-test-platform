<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestCase extends Model
{
    const BEHAVIOR_NEGATIVE = 'negative';
    const BEHAVIOR_POSITIVE = 'positive';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'behavior',
        'description',
        'preconditions',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function suite()
    {
        return $this->belongsTo(TestSuite::class);
    }
}
