<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nickname' => 'min:3|max:30|required|unique:users,nickname',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8'
        ];
    }

    public function messages()
    {
        return [
            'min' => 'Длина данного поля должна быть больше :min символов',
            'max' => 'Длина данного поля должна быть меньше :max символов',
            'required' => 'Поле должно быть заполнено',
            'nickname.unique' => 'Такой пользователь уже существует',
            'email.unique' => 'Такой Email уже используется',
            'email' => 'Введите email в правильном формате',
        ];
    }
}
