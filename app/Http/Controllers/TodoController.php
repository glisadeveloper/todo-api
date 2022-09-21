<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Todo;
use Illuminate\Http\Request;
use App\Helpers\ReturnResponse;

class TodoController extends Controller
{
    /**
     * @param Request $request
     * @return \App\Helpers\ReturnResponse
     */
    public function store(Request $request){
        
        $request->validate(
            [   
                'title' => 'required',
                'description' => 'required',             
                'user_id' => 'required'
            ]
        );

        $user_id = $request->input('user_id');

        $user = User::where('id', $user_id)->first();
        if($user == null){
            return ReturnResponse::returnJson('todo', ["message" => 'User with id '.$user_id.' does not exist !'], false, 404); 
        }

        $data = [
                    'title' => $request->input('title'),                          
                    'description' => $request->input('description'),
                    'user_id' => $request->input('user_id')
                ];

        $todo = new Todo($data); 
        $todo->save();

        return ReturnResponse::returnJson('todo', $todo, true, 200);  
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
                'description' => 'required',             
                'user_id' => 'required'
            ]
        );

        $data = [
                'title' => $request->input('title'),                          
                'description' => $request->input('description'),
                'user_id' => $request->input('user_id')
                ];

        if(Todo::where('id', $id)->first()){ 
            $todo = Todo::where('id', $id)->update($data);     
            return ReturnResponse::returnJson('todo', $data, true, 200);          
        }else{
            return ReturnResponse::returnJson('todo', ["message" => 'Todo item does not exist'], false, 404);  
        }    
    }

    /**
     * @param $id
     * @return \App\Helpers\ReturnResponse
     */
    public function getSingle($id){
        $todo = Todo::where('id', $id)->first(['id','title', 'description', 'user_id']);
        if($todo){
            return ReturnResponse::returnJson('todo', $todo, true, 200);  
        }
        return ReturnResponse::returnJson('todo', ["message" => "Todo item not found"], false, 404);  
    }

    /**
     * @return \App\Helpers\ReturnResponse
     */
    public function getAll(){
        $todos = Todo::all(['id','title', 'description', 'user_id']);
        return ReturnResponse::returnJson('todo', $todos, true, 200);  
    }

    /**
     * @param $id
     * @return \App\Helpers\ReturnResponse
     */
    public function delete($id){
        $todo = Todo::where('id', $id)->first();  
        if ($todo !== null) {      
            $todo->delete();             
            return ReturnResponse::returnJson('todo', ["message" => "delete todo success"], true, 200);  
        }else{
            return ReturnResponse::returnJson('todo', ["message" => "Todo item not found"], false, 404);
        }
    }
}
