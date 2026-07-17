<?php

namespace App\DTOs;

class LeadDto extends BaseDto
{
    public function __construct(
        public string $name,
        public ?string $email,
        public ?string $phone,
        public ?string $source, // website, manual, whatsapp, portal
        public ?string $status, // new, contacted, qualified, proposal, won, lost
        public int $agent_id,
        public ?int $property_id = null
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            email: $data['email'] ?? null,
            phone: $data['phone'] ?? null,
            source: $data['source'] ?? 'website',
            status: $data['status'] ?? 'new',
            agent_id: (int) $data['agent_id'],
            property_id: isset($data['property_id']) ? (int) $data['property_id'] : null
        );
    }
}
