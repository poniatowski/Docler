<?php

namespace Test\Docler\v1\Task\Controller;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    private static Client $client;

    public static function setUpBeforeClass(): void
    {
        self::$client = new Client(['base_uri' => 'http://localhost:8080']);
    }

    /**
     * @group API
     */
    public function testGetAllTasks(): void
    {
        $request = self::$client->request('GET', '/api/v1/tasks');

        if ($request->getStatusCode() === 200) {
            $response = $request->getBody()->getContents();

            $this->assertJson($response);

            $response = json_decode($response, true);

            $this->assertIsArray($response);

            $this->assertEquals(1, $response[0]['id']);
            $this->assertEquals('Test B', $response[0]['title']);
            $this->assertEquals('Description B. Description. Description. Description.', $response[0]['description']);
            $this->assertEquals(999, $response[0]['priority']);
        }
    }

    /**
     * @group API
     */
    public function testGetSingleTask(): void
    {
        $request = self::$client->request('GET', '/api/v1/tasks/1');

        if ($request->getStatusCode() === 200) {
            $response = $request->getBody()->getContents();

            $this->assertJson($response);

            $response = json_decode($response, true);

            $this->assertIsArray($response);

            $this->assertEquals(1, $response['id']);
            $this->assertEquals('Test B', $response['title']);
            $this->assertEquals('Description B. Description. Description. Description.', $response['description']);
            $this->assertEquals(999, $response['priority']);
        }
    }

    /**
     * @group API
     */
    public function testSaveTask(): void
    {
        $task['task'] = [
            "title"       => "Task",
            "description" => "Description. Description. Description. Description.",
            "priority"    => 999
        ];
        $request = self::$client->request('POST', '/api/v1/tasks', [
            'json' => $task,
            'allow_redirects' => true
        ]);

        if ($request->getStatusCode() === 200) {
            $response = $request->getBody()->getContents();

            $response = json_decode($response, true);

            $this->assertIsArray($response);

            $this->assertEquals('Task', $response['title']);
            $this->assertEquals('Description. Description. Description. Description.', $response['description']);
            $this->assertEquals(999, $response['priority']);
        }
    }

    /**
     * @group API
     */
    public function testGetSingleTaskMock()
    {
        $task['task'] = [
            "title"       => "Task",
            "description" => "Description. Description. Description. Description.",
            "priority"    => 999
        ];

        $mock = new MockHandler([
            new Response(200, ['X-Foo' => 'Bar'], json_encode($task)),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);
        $response = $client->request('GET', '/api/v1/tasks');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(json_encode($task), $response->getBody());
    }

    /**
     * @group API
     */
    public function testDeleteTaskMock()
    {
        $body = 'Task 1 has been successfully deleted';
        $mock = new MockHandler([
            new Response(200, ['X-Foo' => 'Bar'], $body),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);
        $response = $client->request('DELETE', '/api/v1/tasks/1');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($body, $response->getBody());
    }

    /**
     * @group API
     */
    public function testUpdateTaskMock()
    {
        $body['task'] = [
            "title"       => "Task",
            "description" => "Description. Description. Description. Description.",
            "priority"    => 999
        ];

        $mock = new MockHandler([
            new Response(200, ['X-Foo' => 'Bar'], json_encode($body)),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);
        $response = $client->request('PUT', '/api/v1/tasks/1');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(json_encode($body), $response->getBody());
    }

    /**
     * @group API
     */
    public function testGetAllTaskOnNonTaskFoundMock()
    {
        $body = 'Tasks not found';

        $mock = new MockHandler([
            new Response(200, ['X-Foo' => 'Bar'], $body),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);
        $response = $client->request('PUT', '/api/v1/tasks/1');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($body, $response->getBody());
    }
}
