<?php

namespace App\DTOs;

abstract class BaseDto
{
    /**
     * Convert DTO to array.
     */
    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
