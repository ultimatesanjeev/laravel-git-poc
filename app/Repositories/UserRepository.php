<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 11/24/2016
 * Time: 11:56 PM
 */

namespace App\Repositories;


use App\Models\User;

class UserRepository
{
    public function create($name, $email, $password)
    {
        $user = new User();
        $user->name = $name;
        $user->email = $email;
        $user->password = $password;
        $user->save();
        return $user;
    }
}