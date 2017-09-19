<?php

namespace Tests\Feature;

use App\Task;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class TaskTest extends TestCase
{
    use WithoutMiddleware;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testTaskCreate()
    {
        $data = factory(Task::class)->raw();
        // Create new task and check response
        $response = $this->post('/api/tasks', $data);

        $response->assertSuccessful();

        $task = json_decode($response->getContent());

        $data = factory(Task::class)->raw(['title' => 'running']);
        // Update the task created
        $this->put('/api/tasks/' . $task->id, $data)
            ->assertJsonFragment(['title' => 'running']);

        $this->get('/api/tasks/' . $task->id)->assertSuccessful()->assertJsonFragment(['title' => 'running']);

        // Delete task
        $this->delete('/api/tasks/' . $task->id)->assertStatus(204);
    }
}
