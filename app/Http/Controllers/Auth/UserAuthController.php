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
     * @return Response
     */
    public function register(Request $request)
    {
        // introduce the validation rules
        $validation_rules = [
            'first_name' => 'required|max:100',
            'last_name' => 'required|max:100',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'timezone' => 'required'
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
        return ReturnResponse::returnJson('todo-api', ['user' => $user, 'token' => $token], true, 200);  
    }   


    /**
     * Logs in a user
     *
     * @param Request $request
     * @return Response
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
            return ReturnResponse::returnJson('todo-api', ['message' => 'Incorrect Details. Please try again'], false, 404);  
        }

        // introduce the access token
        $token = auth()->user()->createToken('API Token')->accessToken;

        return ReturnResponse::returnJson('todo-api',  [
                'user' => auth()->user(),
                'token' => $token
            ], true, 200);  
    }

    /**
     * Retrieves user's data
     *
     * @param Request $request
     * @return Response
     */
    public function me(Request $request)
    {
        $user = $request->user();
        return ReturnResponse::returnJson('todo-api',  [
                'user' => $user
            ], true, 200); 
    }


    /**
     * User's data update
     *
     * @param Request $request
     * @return Response
     */
    public function update(Request $request)    {

        // introduce the validation rules
        $validation_rules = [
            'first_name' => 'required|max:100',
            'last_name' => 'required|max:100',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'timezone' => 'required'
        ];

        $data = [
                'first_name' => $request->first_name,                          
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'timezone' => $request->timezone
                ];

        if($request->user()){ 
            $user = User::where('id', auth()->user()->id)->update($data);     
            return ReturnResponse::returnJson('todo-api', ["message" => 'You have successfully changed your informations'], true, 200);          
        }else{
            return ReturnResponse::returnJson('todo-api', ["message" => 'Changed informations is allowed only for authorized users !'], false, 401);
        }    
    }


    /**
     * User's data update timezone
     *
     * @param Request $request
     * @return Response
     */
    public function updateTimeZone(Request $request)    {

        // introduce the validation rules
        $validation_rules = [
            'timezone' => 'required'
        ];

        $data = [
                'timezone' => $request->timezone
                ];

        if($request->user()){ 
            $user = User::where('id', auth()->user()->id)->update($data);     
            return ReturnResponse::returnJson('todo-api', ["message" => 'You have successfully changed your timezone'], true, 200);          
        }else{
            return ReturnResponse::returnJson('todo-api', ["message" => 'Changed informations is allowed only for authorized users !'], false, 401);
        }    
    }


    /**
     * Logs out user
     *
     * @param Request $request
     * @return Response
     */
    public function logOut(Request $request)
    {
        $user = $request->user();

        if ($user) {
            $user->token()->revoke();
             return ReturnResponse::returnJson('todo-api',  [
                ['message' => 'You have successfully logged out']
            ], true, 200); 
        }else{
        	return ReturnResponse::returnJson('todo-api',  [
                ['message' => 'Not authenticated.']
            ], true, 401); 
        }
    }

    /**
     * Delete user data
     *
     * @param Request $request
     * @return Response
     */
    public function delete(Request $request, $id)
    {
        // each user can delete only himself
        $user = User::where('id', $id)->first();  
        if ($user !== null && $request->user()->id == $id) {      
            $user->delete();             
            return ReturnResponse::returnJson('todo-api', ["message" => "You have successfully deleted all data related to you !"], true, 200);  
        }else{
            return ReturnResponse::returnJson('todo-api', ["message" => "Data deletion is allowed only for you, pleae check your user id !"], false, 403);
        }
    }
}
