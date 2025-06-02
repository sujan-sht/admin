<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PermissionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'model' => $this->model,
            'can' => $this->can ? 'Yes' : 'No',
            'role_name' => $this->role->name,
            'browse'=>$this->browse ? 'Yes' : 'No',
            'read'=>$this->read ? 'Yes' : 'No',
            'edit'=>$this->edit ? 'Yes' : 'No',
            'add'=>$this->add ? 'Yes' : 'No',
            'delete'=>$this->delete ? 'Yes' : 'No',
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
