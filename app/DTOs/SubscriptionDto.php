<?php

namespace App\DTOs;

class SubscriptionDto extends BaseDto
{
    public function __construct(
        public int $user_id,
        public int $subscription_plan_id,
        public ?string $starts_at = null,
        public ?string $ends_at = null,
        public ?string $status = 'active'
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            user_id: (int) $data['user_id'],
            subscription_plan_id: (int) $data['subscription_plan_id'],
            starts_at: $data['starts_at'] ?? null,
            ends_at: $data['ends_at'] ?? null,
            status: $data['status'] ?? 'active'
        );
    }
}
