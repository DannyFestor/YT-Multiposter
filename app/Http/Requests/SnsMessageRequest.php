<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SnsMessageRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'content' => ['required', 'min:3', 'max:500'],
        ];
    }
}
