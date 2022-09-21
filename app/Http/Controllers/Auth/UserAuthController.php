<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use App\Models\User;
use App\Helpers\ReturnResponse;

class UserAuthController extends Controller
{
    /**
     * Registers a user
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        // introduce the validation rules
        $validation_rules = [
            'first_name' => 'required|max:100',
            'last_name' => 'required|max:100',
            'email' => 'required|email|unique:users',
            'password' => 'required'
        ];

        // validate user's data
        $data = $request->validate($validation_rules);

        // encrypt password
        $data['password'] = bcrypt($request->password);

        // create user
        $user = User::create($data);

        // create token
        $token = $user->createToken('API Token')->accessToken;

        // return user and its token
        return ReturnResponse::returnJson('todo', ['user' => $user, 'token' => $token], 'success', 200);  
    }   


    /**
     * Logs in a user
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        // introduce login data
        $data = $request->validate(
            [
                'email' => 'email|required',
                'password' => 'required'
            ]
        );

        // try to log in
        if (!auth()->attempt($data)) {
            return ReturnResponse::returnJson('todo', ['message' => 'Incorrect Details. Please try again'], false, 404);  
        }

        // introduce the access token
        $token = auth()->user()->createToken('API Token')->accessToken;

        return ReturnResponse::returnJson('todo',  [
                'user' => auth()->user(),
                'token' => $token
            ], true, 200);  
    }

    /**
     * Retrieves user's data
     *
     * @param Request $request
     * @return mixed
     */
    public function me(Request $request)
    {
        $user = $request->user();
        return ReturnResponse::returnJson('todo',  [
                'user' => $user
            ], true, 200); 
    }

    /**
     * Logs out user
     *
     * @param Request $request
     * @return mixed
     */
    public function logOut(Request $request)
    {
        $user = $request->user();

        if ($user) {
            $user->token()->revoke();
             return ReturnResponse::returnJson('todo',  [
                ['message' => 'You have successfully logged out']
            ], true, 200); 
        }else{
        	return ReturnResponse::returnJson('todo',  [
                ['message' => 'Not authenticated.']
            ], true, 401); 
        }
    }

    /**
     * Delete user data
     *
     * @param Request $request
     * @return mixed
     */
    public function delete(Request $request, $id)
    {
        $user = User::where('id', $id)->first();  
        if ($user !== null) {      
            $user->delete();             
            return ReturnResponse::returnJson('todo', ["message" => "delete user success"], true, 200);  
        }else{
            return ReturnResponse::returnJson('todo', ["message" => "User not found"], false, 404);
        }
    }
}
