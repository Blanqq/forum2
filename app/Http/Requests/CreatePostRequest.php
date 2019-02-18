<?php

namespace App\Http\Requests;

use App\Reply;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\SpamFree;
use App\Exceptions\ThrottleException;

class CreatePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return \Gate::denies('create', new Reply);
    }

    public function failedAuthorization()
    {
        throw new ThrottleException('You are replying too frequently');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'body' => ['required', new SpamFree]   //'body' => 'required|spamfree'
        ];
    }
}
