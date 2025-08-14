<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuizResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray( $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'admin' => new UserResource($this->whenLoaded('admin')),
            'questions_count' => $this->questions()->count(),
            'duration' => $this->duration_minutes
        ];
    }
}
