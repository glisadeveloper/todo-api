<?php

namespace App\Http\Controllers;

use App\Helpers\ReturnResponse;
use App\Models\Task;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Store a new task.
     * Add or reject task insertion if user exists or is already deleted
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {

    	$request->validate(
            [   
                'title' => 'required',
                'description' => 'required',  
                'todo_id' => 'required',  
                'deadline' => 'required', 
            ]
        );

        $user_id = Auth::user()->id;

        $user = User::where('id', $user_id)->first();
        if($user == null){
            return ReturnResponse::returnJson('todo-api', ["message" => 'User with id '.$user_id.' does not exists !'], false, 403); 
        }

        $task = Task::create([
            'todo_id' => $request->todo_id,
            'title' => $request->title,
            'description' => $request->description,
            'deadline' => $request->deadline,
            'done' => false,
        ]);

        return ReturnResponse::returnJson('todo-api', $task->toArray(), true, 201);
    }

    /**
     * Lists all the tasks.
     * Filter tasks by deadline.
     * Filter tasks by done.
 
     * @return Response
     */
    public function getAll(Request $request)
    {
       	if($request->has('done')){
    		$done = ($request->query('done') == 'complete') ? true : false;
			$todo_tasks = Todo::with(['tasks' => function($q) use ($done) {
				 					$q->where('done', $done);
						            $q->orderBy('deadline', 'ASC');
						        }])
            				  ->where('user_id', Auth::user()->id)
            				  ->orderBy('created_at', 'ASC')
            				  ->get(); 

        }elseif($request->has('deadline')){
    		$date = $request->query('deadline');
			$todo_tasks = Todo::with(['tasks' => function($q) use ($date) {
						            $q->whereRaw('DATE_FORMAT(deadline, \'%Y-%m-%d\') = "' . $date . '"');
						            $q->orderBy('deadline', 'ASC');
						        }])
            				  ->where('user_id', Auth::user()->id)
            				  ->orderBy('created_at', 'ASC')
            				  ->get();         				
		}else{
        	$todo_tasks = Todo::with('tasks')
            				  ->where('user_id', Auth::user()->id)
            				  ->orderBy('created_at', 'ASC')
            				  ->get();
        }

        return ReturnResponse::returnJson('todo-api', ['user-list' => $todo_tasks], true, 200);
    }

    /**
     * Mark a task complete.
     * It is allowed to complete only one's own task
     * @param  Request  $request
     * @return Response
     */
    public function complete(Request $request, $id)
    {

    	$task = Task::with('todo')
            ->where('id', $id)
            ->first();

        if(!$task){
        	return ReturnResponse::returnJson('todo-api', ["message" => 'Task item does not exist'], false, 404);  
        }elseif($task['todo']['user_id'] != Auth::user()->id){
        	return ReturnResponse::returnJson('task', ["message" => 'You can only update your tasks !'], false, 403);  
        }

        $task->done = true;
        $task->save();

        return ReturnResponse::returnJson('todo-api', ['message' => 'Task has been completed.'], true, 200);
    }

    /**
     * Deletes a task.
     * It is allowed to delete only one's own task
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function delete(Request $request, $id)
    {
         $task = Task::with('todo')
            ->where('id', $id)
            ->first();

        if(!$task){
        	return ReturnResponse::returnJson('task', ["message" => 'Task item does not exist'], false, 404);  
        }elseif($task['todo']['user_id'] != Auth::user()->id){
        	return ReturnResponse::returnJson('task', ["message" => 'You can only delete your tasks !'], false, 401);  
        }

        $task->delete();

        return ReturnResponse::returnJson('task', ['message' => 'The task was successfully deleted !'], true, 204);
    }    
}
