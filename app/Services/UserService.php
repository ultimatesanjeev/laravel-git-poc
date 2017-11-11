<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 11/24/2016
 * Time: 11:55 PM
 */

namespace App\Services;


use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class UserService
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function create($name, $email, $password)
    {
        return $this->userRepository->create($name, $email, Hash::make($password));
    }

    public function getList($q)
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.github.com/search/users?q=$q+repos:%3E42+followers:%3E1000",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "postman-token: d6f6988d-8ff4-d7a9-b509-426b68a52f47",
                "user-agent: node.js"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            dd("cURL Error #:" . $err);
        } else {
            $d=json_decode($response);
            foreach ($d->items as $key => $data) {
                $d->items[$key]->singleData = $this->globalFun($data->url);
            }
            return $d;
        }
    }

    public function globalFun($url)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "postman-token: b2da3f2f-5795-b9ee-4c05-9f8c39733630",
                "user-agent: node.js"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            return json_decode($response);
        }
    }
}