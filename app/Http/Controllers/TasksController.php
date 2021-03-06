<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Task;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [];
        
        // 認証済みの場合
        if (\Auth::check())
        {
            // 認証済みユーザ（閲覧者）を取得
            $user = \Auth::user();
            
            // ユーザと、そのユーザのタスク一覧を取得
            $tasks = $user->tasks()->get();
            
            $data = [
                'tasks' => $tasks,
            ];
        }
        // タスク一覧のビューで表示
        return view('tasks.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // フォーム入力項目のためにインスタンス作成
        $task = new Task;
        
        // タスク作成のビューを表示
        return view('tasks.create', [
            'task' => $task,    
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // バリデーション
        $request->validate ([
            'status'  => 'required|max:10',
            'content' => 'required',
        ]);
        
        // 認証済みユーザ（閲覧者）のタスクとして作成（リクエストされた値を元に作成）
        $request->user()->tasks()->create([
            'status'  => $request->status,
            'content' => $request->content,
        ]);
        
        // トップページへリダイレクトさせる
        return redirect('/');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // idの値でタスクを検索して取得
        $task = Task::findOrFail($id);
        
        // 認証済みユーザ（閲覧者）がそのタスクの所有者である場合は詳細を表示
        if (\Auth::id() === $task->user_id)
        {
            // タスク詳細のビューで表示
            return view('tasks.show', [
               'task' => $task,     
            ]);
        }
        return redirect('/');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // idの値でタスクを検索して取得
        $task = Task::findOrFail($id);
       
        // 認証済みユーザ（閲覧者）がそのタスクの所有者である場合は投稿を編集
        if (\Auth::id() === $task->user_id)
        {
            // タスク編集のビューで表示
            return view('tasks.edit', [
               'task' => $task, 
            ]);
        }
        return redirect('/');
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
        // バリデーション
        $request->validate ([
            'status'  => 'required|max:10',
            'content' => 'required',
        ]);
        
        // idの値でタスクを検索して取得
        $task = Task::findOrFail($id);
        
        // 認証済みユーザ（閲覧者）のタスクとして更新（リクエストされた値をもとに更新）
        // $request->user()->tasks()->update([
        //   'status'  => $request->status,
        //   'content' => $request->content,
        
        $task->status  = $request->status;
        $task->content = $request->content;
        $task->save();
        
        // ]);
        
        // トップページへリダイレクトさせる
        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // idの値でタスクを検索して取得
        $task = \App\Task::findOrFail($id);
        
        // 認証済みユーザ（閲覧者）がそのタスクの所有者である場合は投稿を削除
        if (\Auth::id() === $task->user_id)
        {
            $task->delete();
        }
        
        // トップページへリダイレクト
        return redirect('/');
    }
}
