<?php declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasUuid;
use Eloquent;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Eloquent
 */
class Specification extends Model
{
    use HasUuid;

    /**
     * @var string
     */
    protected $table = 'specifications';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'server',
        'schema',
        'description',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'schema' => 'array',
    ];
}
