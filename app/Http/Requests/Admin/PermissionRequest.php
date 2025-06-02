<?php

namespace App\Http\Requests\Admin;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Http\FormRequest;

class PermissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        if (Route::is('permissions.makeModulePermission')) {
           return [
                'browse' => 'sometimes|boolean',
                'read' => 'sometimes|boolean',
                'edit' => 'sometimes|boolean',
                'add' => 'sometimes|boolean',
                'delete' => 'sometimes|boolean',
                'name' => 'sometimes|max:255',
                'can' => 'sometimes|boolean',
                'role_id' => 'required|numeric',
                'model' => 'sometimes|max:255',
            ];
        } else {
            $id = $this->permission->id ?? '';
            return [
                'name' => 'required|max:150|unique:permissions,name,' . $id,
                'role_id' => 'required',
                'can' => 'nullable'
            ];
        }
    }
}
