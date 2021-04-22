<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->route()->getName()) {
            case "users.update":
                return [
                    'name' => 'required|max:12|min:4',
                    'alias' => 'required',
                    'enable' => 'required|in:y,n',
                    'password' => 'min:6|regex:/^[A-Za-z0-9\u4e00_-]+$/|confirmed',
                    'password_confirmation' => 'required_with:password|min:6|regex:/^[A-Za-z0-9\u4e00_-]+$/',
                ];
            case "users.store":
                return [
                    'name' => 'required|max:12|min:4|unique:users,name',
                    'alias' => 'required',
                    'enable' => 'required|in:y,n',
                    'password' => 'required|min:6|regex:/^[A-Za-z0-9\u4e00_-]+$/|confirmed',
                    'password_confirmation' => 'required|min:6|regex:/^[A-Za-z0-9\u4e00_-]+$/',
                ];
        }
    }
}
