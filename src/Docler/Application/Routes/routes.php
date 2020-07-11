<?php

$app->group('/api/v1', function ($app) {
    $app->get('/tasks', ['Docler\v1\Task\Controller\TaskController', 'getTasks']);
    $app->get('/tasks/[{id}]', ['Docler\v1\Task\Controller\TaskController', 'getTask']);
    $app->delete('/tasks/[{id}]', ['Docler\v1\Task\Controller\TaskController', 'delete']);
    $app->put('/tasks/[{id}]', ['Docler\v1\Task\Controller\TaskController', 'update']);
    $app->post('/tasks', ['Docler\v1\Task\Controller\TaskController', 'save']);
});

