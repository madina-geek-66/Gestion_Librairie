<?php

namespace App\Http\Requests\Gestionnaire;

use Illuminate\Foundation\Http\FormRequest;

class LivreFormRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'titre' => ['required', 'string', 'min:5'],
            'auteur_id' => ['required', 'string', 'min:5'],
            'description' => ['required', 'string', 'min:20'],
            'prix' => ['required', 'numeric', 'min:4'],
            'qte_stock' => ['required', 'numeric'],
            'categorie_id' => ['required', 'string'],
            'image' => ['nullable', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ];
    }
}
