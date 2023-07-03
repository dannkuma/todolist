<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\validator;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $condition = '';
        $validationRules = [
            'condition'=>'nullable'
        ];

        if($request->condition === 'completion') {
            $condition = 'completion';
            $validationRules['condition'] = 'required|in:completion';
        }

        if($request->condition === 'incomplete') {
            $condition = 'incomplete';
            $validationRules['condition'] = 'required|in:incomplete';
        }

        if($request->condition === 'all') {
            $condition = 'all';
            $validationRules['condition'] = 'required|in:all';
        }

        $validatedData = $request->validate($validationRules);


        if($condition === 'completion') {
            $condition = Task::where('status', true)->get();
            $sort_condition = 'completion';
        }elseif($condition === 'incomplete') {
            $condition = Task::where('status', false)->get();
            $sort_condition = 'incomplete';
        }else {
            $condition = Task::all();
            $sort_condition = null;
        }

        return view('tasks.index', compact('condition', 'sort_condition'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'task_name' => 'required|max:100'
        ];

        $messages = [
            'required' => '必須項目です', 'max' => '100文字以下にしてください'
        ];

        Validator::make($request->all(), $rules, $messages)->validate();

        Task::create([
            'name' => $request->task_name
        ]);

        return redirect('/tasks');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $task = Task::find($id);
        return view('tasks.edit', compact('task'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if($request->status === null) {
            $rules = [
                'task_name' => 'required|max:100'
            ];
    
            $messages = [
                'required' => '必須項目です', 'max' => '100文字以下にしてください'
            ];
    
            Validator::make($request->all(), $rules, $messages)->validate();
    
            $task = Task::find($id);
            $task->name = $request->task_name;
            $task->save();

        }

        if($request->task_name === null) {
            $task = Task::find($id);
            $task->status = true;
            $task->save();
        }

        return redirect('/tasks');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Task::find($id)->delete();

        return redirect('/tasks');
    }
}
