<?php

namespace Tests\Feature;

use App\Models\Message;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SecurityRegressionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call('migrate:fresh', ['--force' => true]);
    }

    public function test_six_wrong_logins_from_one_ip_are_rate_limited_even_with_different_emails(): void
    {
        for ($attempt = 1; $attempt <= 5; $attempt++) {
            $this->postJson('/api/login', [
                'email' => "unknown{$attempt}@example.test",
                'password' => 'incorrect',
            ])->assertUnprocessable();
        }

        $this->postJson('/api/login', [
            'email' => 'another@example.test',
            'password' => 'incorrect',
        ])->assertTooManyRequests();
    }

    public function test_inactive_users_cannot_log_in(): void
    {
        $user = $this->createUser('inactive@example.test', 'employee', 'inactive');

        $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'valid-password',
        ])->assertUnprocessable()
            ->assertJsonValidationErrors('email');
    }

    public function test_manager_cannot_create_an_admin(): void
    {
        $manager = $this->createUser('manager@example.test', 'manager');

        $this->actingAs($manager, 'sanctum')
            ->postJson('/api/employees', [
                'name' => 'New Admin',
                'email' => 'new-admin@example.test',
                'role' => 'admin',
            ])
            ->assertForbidden();
    }

    public function test_html_task_attachment_is_rejected(): void
    {
        $manager = $this->createUser('manager@example.test', 'manager');

        $this->actingAs($manager, 'sanctum')
            ->post('/api/admin/tasks', [
                'title' => 'Upload test',
                'priority' => 'Low',
                'attachment' => UploadedFile::fake()->createWithContent('payload.html', '<h1>not allowed</h1>'),
            ], ['Accept' => 'application/json'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('attachment');
    }

    public function test_manager_can_only_view_team_tasks_and_chats(): void
    {
        $manager = $this->createUser('manager@example.test', 'manager');
        $subordinate = $this->createUser('employee@example.test', 'employee', 'active', $manager->id);
        $outsider = $this->createUser('outsider@example.test', 'employee');

        $teamTask = $this->createTask($manager, $subordinate, 'Team task');
        $outsideTask = $this->createTask($outsider, $outsider, 'Outside task');
        Message::create([
            'task_id' => $teamTask->id,
            'user_id' => $subordinate->id,
            'message' => 'Team message',
        ]);

        $this->actingAs($manager, 'sanctum')
            ->getJson('/api/tasks')
            ->assertOk()
            ->assertJsonFragment(['title' => 'Team task'])
            ->assertJsonMissing(['title' => 'Outside task']);

        $this->getJson("/api/tasks/{$teamTask->id}/messages")
            ->assertOk()
            ->assertJsonFragment(['message' => 'Team message']);

        $this->getJson("/api/tasks/{$outsideTask->id}")
            ->assertForbidden();
        $this->getJson("/api/tasks/{$outsideTask->id}/messages")
            ->assertForbidden();
    }

    public function test_manager_dashboard_is_scoped_to_own_and_subordinate_tasks(): void
    {
        $manager = $this->createUser('manager@example.test', 'manager');
        $subordinate = $this->createUser('employee@example.test', 'employee', 'active', $manager->id);
        $outsider = $this->createUser('outsider@example.test', 'employee');

        $this->createTask($manager, $subordinate, 'Team task');
        $this->createTask($outsider, $outsider, 'Outside task');

        $this->actingAs($manager, 'sanctum')
            ->getJson('/api/dashboard')
            ->assertOk()
            ->assertJsonPath('stats.total', 1)
            ->assertJsonPath('active_workforce', 2);
    }

    public function test_employee_password_updates_require_at_least_eight_characters(): void
    {
        $manager = $this->createUser('manager@example.test', 'manager');
        $employee = $this->createUser('employee@example.test', 'employee', 'active', $manager->id);

        $this->actingAs($manager, 'sanctum')
            ->putJson("/api/employees/{$employee->id}", ['password' => 'short'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('password');
    }

    private function createUser(string $email, string $role, string $status = 'active', ?int $parentId = null): User
    {
        return User::create([
            'name' => ucfirst(strstr($email, '@', true)),
            'email' => $email,
            'password' => Hash::make('valid-password'),
            'role' => $role,
            'status' => $status,
            'parent_id' => $parentId,
        ]);
    }

    private function createTask(User $creator, User $assignee, string $title): Task
    {
        return Task::create([
            'title' => $title,
            'created_by' => $creator->id,
            'assigned_to' => $assignee->id,
            'priority' => 'Low',
        ]);
    }
}
