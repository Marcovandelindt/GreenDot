<?php

namespace App\Http\Requests\Onboarding;

use Illuminate\Foundation\Http\FormRequest;

class StoreOnboardingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->mergeIfMissing(['accepts_all_requests' => '0']);
    }

    public function rules(): array
    {
        return [
            'bio'                    => ['nullable', 'string', 'max:160'],
            'country'                => ['nullable', 'string', 'max:80'],
            'region'                 => ['nullable', 'string', 'max:80'],
            'accepts_all_requests'   => ['boolean'],
            'language_ids'           => ['nullable', 'array'],
            'language_ids.*'         => ['integer', 'exists:languages,id'],
            'favorite_game_ids'      => ['nullable', 'array', 'max:5'],
            'favorite_game_ids.*'    => ['integer', 'exists:games,id'],
            'current_game_id'        => ['nullable', 'integer', 'exists:games,id'],
        ];
    }
}
