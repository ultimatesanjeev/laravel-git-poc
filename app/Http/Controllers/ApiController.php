<?php
/**
 * Created by PhpStorm.
 * User: acer
 * Date: 2016-01-17
 * Time: 1:03 PM
 */

namespace App\Http\Controllers;


use Hechat\Constants\AppConstants;
use Hechat\Exceptions\AppException;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Response as HttpConstant;
use Illuminate\Support\Facades\Input;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class ApiController extends Controller
{

    /**
     * @var int Status Code.
     */
    protected $statusCode = 200;

    /**
     * Getter method to return stored status code.
     *
     * @return mixed
     */
    public
    function getStatusCode()
    {
        return $this->statusCode;
    }

    /** this function will set status code for response and return current object
     * @param $statusCode
     * @return $this
     */
    public
    function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    public
    function notFound($message = "Resource not found")
    {
        return response()->json(['error' => $message], HttpConstant::HTTP_NOT_FOUND);
    }

    public
    function getUserByToken()
    {
        if (!$user = JWTAuth::parseToken()->authenticate()) {
            return response()->json(['user_not_found'], HttpConstant::HTTP_FORBIDDEN);
        }
        return $user;

    }

    /** this function will check whether the header has token or not if token found it will return a user corresponding to it, if token is invalid it will throw an exception else it will return user as null
     *
     * @return mixed|null
     * @throws AppException
     */
    public function getLoggedInUser()
    {
        try {
            return JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            if ($e->getStatusCode() != 400) {
                throw new AppException("User not found", $e->getStatusCode());
            }
            return null;
        }
    }

    public function getUserRole($user)
    {
        return $user->roles;
    }

    public function isLoggedInUserIsAdmin($user = null)
    {
        if (!$user)
            $user = $this->getUserByToken();
        $isAdmin = false;
        if ($user) {
            foreach ($this->getUserRole($user) as $role) {
                if ($role->name == AppConstants::ROLE_ADMIN) {
                    $isAdmin = true;
                    break;
                }
            }
        }
        return $isAdmin;
    }

    public function badRequest($message = "Bad Request")
    {
        \Log::info(Input::all());
        return response()->json(['error' => $message], HttpConstant::HTTP_BAD_REQUEST);
    }

    public function respond($item, $headers = [])
    {
        return response()->json($item, $this->getStatusCode(), $headers);
    }

    public
    function setCurrentPage($offset, $pageSize)
    {
        $currentPage = intval(ceil($offset / $pageSize) + 1);
        \Illuminate\Pagination\Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });
    }

    public
    function respondWithPagination(Paginator $paginator, $data)
    {
        $data = array_merge($data, [
            'paginator' => [
                'totalCount' => $paginator->total(),
                'totalPages' => ceil($paginator->total() / $paginator->perPage()),
                'currentPage' => $paginator->currentPage(),
                'limit' => $paginator->perPage()
            ]
        ]);
        return $this->respond($data);
    }

    public function notImplemented()
    {
        return response()->json(['error' => 'Not Implemented'], HttpConstant::HTTP_NOT_IMPLEMENTED);
    }
}
