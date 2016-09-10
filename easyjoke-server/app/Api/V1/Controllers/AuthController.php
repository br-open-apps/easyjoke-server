<?php

namespace App\Api\V1\Controllers;

use App\Api\Exception\CouldNotRestPasswordException;
use App\Api\Exception\EmailNotFoundException;
use App\Api\V1\Requests\UserRequest;
use Dingo\Api\Facade\API;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Dingo\Api\Routing\Helpers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use Tymon\JWTAuth\Exceptions\JWTException;
use Dingo\Api\Exception\ValidationHttpException;

/**
 * Authentication
 *
 * @Resource("Authentication", uri="/auth")
 */
class AuthController extends Controller
{
    use Helpers;

    public function me(Request $request)
    {
        return JWTAuth::parseToken()->authenticate();
    }

    /**
     * Register user
     *
     * Register a new user
     *
     * @Post("/signup")
     * @Versions({"v1"})
     * @Transaction({
     *      @Request({"name": "User Name", "login": "user", "email": "user@comapany.com", "password": "xx15Ab", "password_confirmation": "xx15Ab"}),
     *      @Response(200, body={"token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9....."}),
     *      @Response(422, body={"message": "422 Unprocessable Entity", "errors": {"login": {"The login has already been taken."}, "email": {"The email field is required."}},"status_code": 422})
     * })
     */
    public function signup(UserRequest $request)
    {
        $hasToReleaseToken = Config::get('boilerplate.signup_token_release');

        User::unguard();
        $user = User::create([
            'name' => $request->get('name'),
            'login' => $request->get('login'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password')),
        ]);
        User::reguard();

        if(!$user->id) {
            return $this->response->error('could_not_create_user', 500);
        }

        if($hasToReleaseToken) {
            return $this->login($request);
        }

        return $this->response->created();
    }

    /**
     * Login user
     *
     * Login of a user in the system with 'email' and 'password'.
     *
     * @Post("/login")
     * @Versions({"v1"})
     * @Transaction({
     *      @Request({"email": "user@comapany.com", "password": "xx15Ab"}),
     *      @Response(200, body={"token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9....."}),
     *      @Response(422, body={"message": "422 Unprocessable Entity", "errors": {"email": {"The email field is required."}, "password": {"The password field is required."}},"status_code": 422})
     * })
     */
    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);

        $validator = Validator::make($credentials, [
            'email' => 'required',
            'password' => 'required',
        ]);

        if($validator->fails()) {
            throw new ValidationHttpException($validator->errors()->all());
        }

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return $this->response->errorUnauthorized();
            }
        } catch (JWTException $e) {
            return $this->response->error('could_not_create_token', 500);
        }

        return response()->json(compact('token'));
    }

    public function validateToken()
    {
        // Our routes file should have already authenticated this token, so we just return success here
        return API::response()->array(['status' => 'success'])->statusCode(200);
    }

    /**
     * Password recovery
     *
     * Recover password of an account
     *
     * @Post("/recovery")
     * @Versions({"v1"})
     * @Transaction({
     *      @Request({"email": "user@comapany.com"}),
     *      @Response(200, body={"token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9....."}),
     *      @Response(422, body={"message": "422 Unprocessable Entity", "errors": {"email": {"The email field is required."}},"status_code": 422}),
     *      @Response(404, body={"message": "Not Found", "errors": {"email": {"The email field is required."}},"status_code": 422})
     * })
     */
    public function recovery(Request $request)
    {
        $validator = Validator::make($request->only('email'), [
            'email' => 'required'
        ]);

        if($validator->fails()) {
            throw new ValidationHttpException($validator->errors()->all());
        }

        $response = Password::sendResetLink($request->only('email'), function (Message $message) {
            $message->subject(Config::get('boilerplate.recovery_email_subject'));
        });

        switch ($response) {
            case Password::RESET_LINK_SENT:
                return API::response()->array(['status' => 'success'])->statusCode(200);
            case Password::INVALID_USER:
                throw new EmailNotFoundException();
        }
    }

    /**
     * Reset password
     *
     * Reset password of an account
     *
     * @Post("/recovery")
     * @Versions({"v1"})
     * @Transaction({
     *      @Request({"email": "user@comapany.com"}),
     *      @Response(200, body={"status": "success"}),
     *      @Response(422, body={"message": "422 Unprocessable Entity", "errors": {{"The token field is required."},{"The email field is required."},{"The password field is required."}},"status_code": 422}),
     *      @Response(500, body={"message": "It is not possible to reset the password","status_code": 500})
     * })
     */
    public function reset(Request $request)
    {
        $credentials = $request->only(
            'email', 'password', 'password_confirmation', 'token'
        );

        $validator = Validator::make($credentials, [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
        ]);

        if($validator->fails()) {
            throw new ValidationHttpException($validator->errors()->all());
        }

        $response = Password::reset($credentials, function ($user, $password) {
            $user->password = bcrypt($password);
            $user->save();
        });

        switch ($response) {
            case Password::PASSWORD_RESET:
                if(Config::get('boilerplate.reset_token_release')) {
                    return $this->login($request);
                }
                return API::response()->array(['status' => 'success'])->statusCode(200);

            default:
                throw new CouldNotRestPasswordException();
        }
    }
}