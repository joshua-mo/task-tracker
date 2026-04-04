<?php

namespace App\Task\Routes;

use App\Auth\Middleware\RequiresLogin;
use Neoan\Request\Request;
use App\Task\Models\Task;
use Neoan\Routing\Attributes\Post;
use Neoan\Routing\Interfaces\Routable;
use App\Subtask\Models\Subtask;
use App\Task\Requests\CreateTaskRequest;

#[Post('/api/tasks', RequiresLogin::class)]
class CreateTask implements Routable
{
    public function __invoke(CreateTaskRequest $request): Task
    {
        $input = Request::getInputs();

        // ?int wird im RequestGuard zu 0 gecastet, deshalb userId ohne Guard
        $userId = $input["userId"] ?? null;

        $task = new Task();
        $task->title = $request->title;
        $task->description = $request->description;
        $task->userId = $userId === null || $userId === ''
            ? null
            : (int) $userId;
        $task->isDone = false;

        $task->store();

        foreach ($request->subtasks as $subtaskTitle) {
            $subtask = new Subtask();
            $subtask->title = $subtaskTitle;
            $subtask->taskId = $task->id;
            $subtask->isDone = false;
            $subtask->store();
        }

        $task = Task::get($task->id);

        return $task;



    }
}
