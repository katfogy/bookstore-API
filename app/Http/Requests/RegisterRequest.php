<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name'=>'required|min:5|max:150',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:5|max:25',

        ];
    }

    public function messages(): array{
        return[
            'name.required'=>'Please Enter your Name',
            'name.min'=>'Name must be atleast 5 chars long',
            'name.max'=>'Name must not be more than 150 chars long',
            'email.required'=>'Please Enter your Email Address',
            'email.email'=>'Please Enter a valid Email Address',
            'email.unique'=>'Please Email Already taken, please try with different email address',
            'password.required'=>'Please Enter your Password',
            'password.min'=>'Password must be atleast 5 chars long',
            'password.max'=>'Name must not be more than 25 chars long',
        ];
    }
}
