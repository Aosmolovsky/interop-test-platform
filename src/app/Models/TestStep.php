<?php declare(strict_types=1);

namespace App\Models;

use App\Casts\RequestCast;
use App\Casts\ResponseCast;
use App\Models\Concerns\HasPosition;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 */
class TestStep extends Model
{
    use HasPosition;

    /**
     * @var string
     */
    protected $table = 'test_steps';

    /**
     * @var array
     */
    protected $fillable = [
        'api_scheme_id',
        'forward',
        'backward',
        'request_example',
        'response_example',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'request_example' => RequestCast::class,
        'response_example' => ResponseCast::class,
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function testCase()
    {
        return $this->belongsTo(TestCase::class, 'test_case_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function source()
    {
        return $this->belongsTo(Component::class, 'source_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function target()
    {
        return $this->belongsTo(Component::class, 'target_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function apiScheme()
    {
        return $this->belongsTo(ApiScheme::class, 'api_scheme_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function testScripts()
    {
        return $this->hasMany(TestScript::class, 'test_step_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function testSetups()
    {
        return $this->hasMany(TestSetup::class, 'test_step_id');
    }

    /**
     * @return array
     */
    public function getPositionGroupColumn()
    {
        return ['test_case_id'];
    }
}
