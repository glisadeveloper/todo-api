<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $fillable = [
            'title',
            'description',            
            'todo_id',
            'deadline',
            'done'
        ]; 

    public function todo()
    {
        return $this->belongsTo(Todo::class);
    }
}
