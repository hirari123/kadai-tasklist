@extends('layouts.app')

@section('content')

    @if (Auth::check())
        <h1>タスク一覧</h1>
        
        @if (count($tasks) > 0)
        
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>ステータス</th>
                        <th>タスク</th>
                        <th>ユーザid</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tasks as $task)
                        <tr>
                            <td>{!! link_to_route('tasks.show', $task->id, ['task' => $task->id]) !!}</td>
                            <td>{{ $task->status }}</td>
                            <td>{{ $task->content }}</td>
                            <td>{{ $task->user_id }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            {!! link_to_route('tasks.create', '新規タスクの投稿', [], ['class' => 'btn btn-primary']) !!}
        
        @endif
    @endif
@endsection