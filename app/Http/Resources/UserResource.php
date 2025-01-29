<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use ElfSundae\Laravel\Hashid\Facades\Hashid;

class UserResource extends JsonResource
{
	public function toArray(Request $request): array
	{
		return [
			'id' => $this->id,
			'hash_id' => Hashid::encode($this->id),
			'name' => $this->name,
			'role' => $this->role,
			'createdAt' => $this->created_at?->toISOString(),
			'updatedAt' => $this->updated_at?->toISOString(),
		];
	}
}
