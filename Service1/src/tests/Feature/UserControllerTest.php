<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Queue;




class UserControllerTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_example()
    {
        $this->assertTrue(true);
    }

    /** @test */
    public function it_creates_a_new_user_and_dispatches_notification_job()
    {
        Queue::fake();

        $userData = [
            'email' => 'jane.doe4@gmail.com',
            'first_name' => 'Jane',
            'last_name' => 'Doe',
        ];

        $response = $this->json('POST', '/api/user', $userData);

        // Assert the response is correct
        $response
            ->assertStatus(200)
            ->assertJson([
                'message' => 'User created successfully',
                'user' => [
                    'email' => 'jane.doe4@gmail.com',
                    'first_name' => 'Jane',
                    'last_name' => 'Doe',
                ],
            ]);

        // Assert the user was created in the database
        $this->assertDatabaseHas('users', [
            'email' => 'jane.doe4@gmail.com',
            'first_name' => 'Jane',
            'last_name' => 'Doe',
        ]);

        // Assert the job was dispatched
        Queue::assertPushed(\App\Jobs\NotificationJob::class, function ($job) use ($userData) {
            return $job->getData() === $userData;
        });
        
    }

}
