<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Todo;
use Illuminate\Http\Request;
use App\Helpers\ReturnResponse;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{
    /**
     * Add or reject insertion for todo list if user exists or is already deleted
     * @param Request $request
     * @return \App\Helpers\ReturnResponse
     */
    public function store(Request $request){
        
        $request->validate(
            [   
                'title' => 'required',
                'description' => 'required',  
            ]
        );

        $user_id = Auth::user()->id;

        $user = User::where('id', $user_id)->first();
        if($user == null){
            return ReturnResponse::returnJson('todo-api', ["message" => 'User with id '.$user_id.' does not exists !'], false, 403); 
        }

        $data = [
                    'title' =>       $request->input('title'),                          
                    'description' => $request->input('description'),
                    'user_id' =>     $user_id
                ];

        $todo = new Todo($data); 
        $todo->save();

        return ReturnResponse::returnJson('todo-api', ["message" => 'You have successfully added a new list'], true, 201);  
    }

    /**
     * @param $id
     * @param Request $request
     * @return \App\Helpers\ReturnResponse
     */
    public function update($id, Request $request){

        $request->validate(
            [   
                'title' => 'required',
                'description' => 'required' 
            ]
        );

        $user_id = Auth::user()->id;

        $data = [
                'title' => $request->input('title'),                          
                'description' => $request->input('description')
                ];

        if(Todo::where('id', $id)->where('user_id', $user_id)->first()){ 
            $todo = Todo::where('id', $id)->update($data);     
            return ReturnResponse::returnJson('todo-api', ["message" => 'You have successfully updated list'], true, 200);          
        }else{
            return ReturnResponse::returnJson('todo-api', ["message" => 'Todo item does not exist'], false, 404);  
        }    
    }

    /**
     * Get single list based on id 
     * @param $id
     * @return \App\Helpers\ReturnResponse
     */
    public function getSingle($id){
        $user_id = Auth::user()->id;
        $todo = Todo::where('id', $id)->where('user_id', $user_id)->first(['id','title', 'description']);
        if($todo){
            return ReturnResponse::returnJson('todo-api', $todo, true, 200);  
        }
        return ReturnResponse::returnJson('todo-api', ["message" => "Todo item not found"], false, 404);  
    }

    /**
     * Lists all the lists.
     * Filter lists by date.
     * Filter lists by title.
     * @return \App\Helpers\ReturnResponse
     */
    public function getAll(Request $request){

        if($request->has('date')){
            $date = $request->query('date');
            $todos = Todo::whereRaw('DATE_FORMAT(created_at, \'%Y-%m-%d\') = "' . $date . '"')
                           ->where('user_id', Auth::user()->id)
                           ->orderBy('created_at', 'ASC')
                           ->paginate(10);
        }elseif($request->has('title')){
            $title = $request->query('title');
            $todos = Todo::whereRaw('title = "' . $title . '"')                               
                           ->where('user_id', Auth::user()->id)
                           ->orderBy('created_at', 'ASC')
                           ->paginate(10);  
        }else{             
            $todos = Todo::all();
        }
        return ReturnResponse::returnJson('todo-api', $todos, true, 200);  
    }

    /**
     * Delete list based on id ( It is allowed to only one's own )
     * @param $id
     * @return \App\Helpers\ReturnResponse
     */
    public function delete($id){
        $user_id = Auth::user()->id;
        $todo = Todo::where('id', $id)->where('user_id', $user_id)->first();  
        if ($todo !== null) {      
            $todo->delete();             
            return ReturnResponse::returnJson('todo-api', ["message" => "delete todo success"], true, 200);  
        }else{
            return ReturnResponse::returnJson('todo-api', ["message" => "Todo item not found"], false, 404);
        }
    }
}
