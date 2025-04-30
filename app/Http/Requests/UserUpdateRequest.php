<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // L'utilisateur peut modifier son propre profil
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'email' => [
                'sometimes',
                'required', 
                'email',
                Rule::unique('users')->ignore($this->user()->id)
            ],
            'current_password' => 'sometimes|required|current_password',
            'password' => 'sometimes|nullable|min:8|confirmed',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Le nom est obligatoire',
            'email.required' => 'L\'email est obligatoire',
            'email.email' => 'Veuillez fournir un email valide',
            'email.unique' => 'Cet email est déjà utilisé',
            'current_password.required' => 'Le mot de passe actuel est obligatoire',
            'current_password.current_password' => 'Le mot de passe actuel est incorrect',
            'password.min' => 'Le nouveau mot de passe doit contenir au moins 8 caractères',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas',
        ];
    }
}