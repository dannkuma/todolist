<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ToDoList;

class TodoListController extends Controller
{
    public function index() {
        $todo_lists = ToDoList::all();

        return view('todo_lists.index', compact('todo_lists'));
    }
}
