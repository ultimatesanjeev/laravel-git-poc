<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class UserController extends ApiController
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $q = Input::get('q');
        $data = $this->userService->getList($q);
        //return $this->respond(['data' => $data]);
        return view('welcome')->with('data', $data);
    }

    public function getFollowers($name)
    {
        $url = "https://api.github.com/users/$name/followers";
        $page = Input::get('page', 1);

        $url = $url . "?per_page=10&page=" . $page;

        $data = $this->userService->globalFun($url);

        //return $this->respond(['data' => $data, "currentPage" => $page]);
        return view('followers')->with(["data"=>$data,"currentPage" => $page,"name"=>$name]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $name = $request->json('name');
        $email = $request->json('email');
        $password = $request->json('password');
        $data = $this->userService->create($name, $email, $password);
        return $this->respond(['data' => $data]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
