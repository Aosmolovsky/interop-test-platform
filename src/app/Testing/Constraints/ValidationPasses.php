<?php

namespace App\Testing\Constraints;

use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Constraint\Constraint;

class ValidationPasses extends Constraint
{
    /**
     * @var array
     */
    protected $rules;
    /**
     * @var array
     */
    protected $messages;

    /**
     * @param array $rules
     * @param array $messages
     */
    public function __construct(array $rules, array $messages = [])
    {
        $this->rules = $rules;
        $this->messages = $messages;
    }

    /**
     * @param array $data
     * @return bool
     */
    public function matches($data): bool
    {
        return Validator::make($data, $this->rules, $this->messages)
            ->passes();
    }

    /**
     * @param  string $data
     * @return string
     */
    public function failureDescription($data): string
    {
        return $this->toString();
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return 'validation passes';
    }
}
