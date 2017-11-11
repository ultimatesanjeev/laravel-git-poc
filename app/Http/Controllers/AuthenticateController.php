<?php

namespace App\Http\Controllers;

use App;
use Carbon\Carbon;
use App\Constants\AppConstants;
use App\Constants\FileType;
use Illuminate\Http\Request;
use App\Http\Requests;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthenticateController extends ApiController
{
    private $userService;

    public function __construct()
    {

        $this->middleware('jwt.auth', ['except' => ['authenticate']]);
    }

    public function authenticate(Request $request)
    {
        $email = $request->json('email') ? $request->json('email') : '';
        $password = $request->json('password', 'hechat@123');
        $firstName = $request->json('firstName', null);
        $socialId = $request->json('socialId', null);
        $socialName = $request->json('socialName', null);
        $source = $request->json('source', null);
        $credentials = ['email' => $email, 'password' => $password];
        if (is_numeric($email)) {
            $credentials = ['mobile' => $email, 'password' => $password];
        }


        \Log::info('User authenticated : email-id : ' . $email . ' at ' . Carbon::now()->toDateTimeString());
        // verify the credentials and create a token for the user
        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Invalid Credentials'], 401);



        }
        //$credentials1 = ['mobile' => $email, 'password' => $password];


        $data = ['token' => $token];
        $user = JWTAuth::toUser($token);
        $user->lastLoggedIn = Carbon::now();
        $data["id"] = $user->id;

        if ($source) {
            $user->source = $source;
            if ($source == AppConstants::SOURCE_USER_LOGGED_IN_VIA_MOBILE && !$user->firstLoggedInViaMobile) {
                $user->firstLoggedInViaMobile = Carbon::now();
            } else if ($source == AppConstants::SOURCE_USER_LOGGED_IN_VIA_SMS && !$user->firstLoggedInViaSms) {
                $user->firstLoggedInViaSms = Carbon::now();
            } else if ($source == AppConstants::SOURCE_USER_LOGGED_IN_VIA_WEB && !$user->firstLoggedInViaWeb) {
                $user->firstLoggedInViaWeb = Carbon::now();
            }
        }
        $user->save();

        /*$stayUpdatedProductService = App::make("Ilf\\Services\\StayUpdatedProductService");
        $data['isSubscriptionActive'] = $stayUpdatedProductService->isSubscriptionActive($user->id);
        $this->userService->unRegisterUserDevice($user->id);*/
        return response()->json(['data' => $data]);
    }

    public function getAuthenticatedUser()
    {
        // the token is valid and we have found the user via the sub claim
        $data = $this->getUserByToken();
        $amazonService = new AmazonS3Service();
        $file = $data->File;
        if ($file && !empty($file->type)) {
            $fileUrl = $amazonService->getObjectUrl($file->s3Key, FileType::RANDOM_BUCKET);
        } else {
            $fileUrl = null;
        }
        $data->fileUrl = $fileUrl;
        $stayUpdatedProductService = App::make("Ilf\\Services\\StayUpdatedProductService");
        $data->jIsSubscriptionActive = filter_var($stayUpdatedProductService->isSubscriptionActive($data->id), FILTER_VALIDATE_BOOLEAN);
        $data->jIsActive = filter_var($data->isActive, FILTER_VALIDATE_BOOLEAN);
        $data->referralMessage = "Hi, I am using ILF StayUpdated app. This is a wonderful app to get the latest judgments. Use my referral code - $data->referralCode  Click to download(android) - https://goo.gl/8HBHYK";
        return response()->json(['data' => $data]);
    }


}
