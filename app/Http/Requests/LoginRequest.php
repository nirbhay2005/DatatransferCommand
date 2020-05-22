<?php


namespace App\Http\Requests;


use Luezoid\Laravelcore\Requests\BaseRequest;

class LoginRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'emails' => 'required|emails',
            'password' => 'required|string'
        ];
    }
}
