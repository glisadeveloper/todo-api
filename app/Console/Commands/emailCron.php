<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Todo;
use App\Models\User; 
use Illuminate\Support\Carbon;

class emailCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $done = true;
        $todo_tasks = Todo::with(['tasks' => function($q) use ($done) {
                                    $q->where('done', $done);
                                    $q->orderBy('deadline', 'ASC');
                                }])
                              ->orderBy('created_at', 'ASC')
                              ->get(); 

        $task_done = [];          

        foreach ($todo_tasks as $key => $task) {
            if(count($task['tasks']) > 0){ 
                $user = User::where('id', $task['user_id'])->first();  
                $userTimeZone = \Carbon\Carbon::now()->setTimezone($user->timezone)->format('H:i');   

                foreach ($task['tasks'] as $key => $deadline) {                 
                        $now = Carbon::now();
                        $startDate = Carbon::parse($deadline['created_at'])->format('d.m.Y h:m:sa');
                        $endDate = Carbon::parse($deadline['deadline'])->format('d.m.Y h:m:sa');
                        if ($now->between($startDate, $endDate) && $userTimeZone == '00:00') { // at midnight
                           $task_done[$task['title']] = array('email' => $user->email, 'title' => $task['title'], 'total' => count($task['tasks']));
                        }                   
                }               
            }
        }    

        //send email for solved task ( done )
        foreach ($task_done as $key => $solved) {
                $details = [
                    'title' => 'Daily report of completed tasks',
                    'name' => $user->first_name,
                    'todoList' => $solved,
                    'body' => 'You are done today:' 

                ];             
                \Mail::to($solved['email'])->send(new \App\Mail\TaskMail($details));
        }       

    }
}
