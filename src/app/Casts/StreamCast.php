<?php declare(strict_types=1);

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use function GuzzleHttp\Psr7\stream_for;

class StreamCast implements CastsAttributes
{

    public function get($model, string $key, $value, array $attributes)
    {
        if (is_array($value)) {
            $value = json_encode($value);
        }

        return stream_for($value);
    }

    public function set($model, string $key, $value, array $attributes)
    {
        if (is_array($value)) {
            $value = json_encode($value);
        }

        return $value;
    }
}
