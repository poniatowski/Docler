<?php

namespace Docler\v1\Task\Controller;

use Slim\Http\Response;
use Slim\Http\Request;
use Docler\v1\Task\Model\Task as TaskModel;

class TaskController
{
    private TaskModel $taskModel;

    public function __construct(TaskModel $taskModel)
    {
        $this->taskModel = $taskModel;
    }

    public function getTasks(Request $request, Response $response): Response
    {
        $tasks = $this->taskModel->all();

        if ($tasks === null) {
            return $response->withStatus(404)
                ->withJson('Tasks not found');
        }

        return $response->withStatus(200)
            ->withJson($tasks);
    }

    public function getTask(Request $request, Response $response, int $id): Response
    {
        $task = $this->taskModel->find($id);

        if ($task === null) {
            return $response->withStatus(404)
                ->withJson(sprintf('Task not found with ID %d', $id));
        }

        return $response->withStatus(200)
            ->withJson($task);
    }

    public function save(Request $request, Response $response): Response
    {
        $task = $request->getParam('task');

        $id = $this->taskModel->save(
            $task['title'],
            $task['description'],
            $task['priority'],
        );

        return $response->withStatus(200)
            ->withRedirect('/api/v1/tasks/' . $id, 303);
    }

    public function delete(Request $request, Response $response, int $id): Response
    {
        $task = $this->taskModel->find($id);

        if ($task === null) {
            return $response->withStatus(404)
                ->withJson(sprintf('Task not found with ID %d', $id));
        }

        $this->taskModel->delete($id);

        return $response->withStatus(200)
            ->withJson(sprintf('Task %d has been successfully deleted', $id));
    }

    public function update(Request $request, Response $response, int $id): Response
    {
        $task = $this->taskModel->find($id);

        if ($task === null) {
            return $response->withStatus(404)
                ->withJson(sprintf('Task not found with ID %d', $id));
        }

        $task = $request->getParam('task');

        $this->taskModel->update(
            $id,
            $task['title'],
            $task['description'],
            (int)$task['priority']
        );

        return $response->withStatus(200)
            ->withRedirect('/api/v1/tasks/' . $id, 303);
    }
}
