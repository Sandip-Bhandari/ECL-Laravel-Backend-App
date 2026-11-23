<?php

namespace Tests\Feature;

use App\Models\Message;
use App\Models\Task;
use App\Models\TaskAttachment;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
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

    public function test_login_requires_a_password_for_every_role(): void
    {
        $user = $this->createUser('employee@example.test', 'employee');

        $this->postJson('/api/login', ['email' => $user->email])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('password');
    }

    public function test_super_admin_receives_an_admin_token(): void
    {
        $superAdmin = $this->createUser('super-admin@example.test', 'super_admin');

        $response = $this->postJson('/api/login', [
            'email' => $superAdmin->email,
            'password' => 'valid-password',
        ])
            ->assertOk()
            ->assertJsonPath('has_admin_access', true)
            ->assertJsonPath('employee_token', null);

        $this->assertIsString($response->json('admin_token'));
    }

    public function test_employee_cannot_create_an_administrator(): void
    {
        $employee = $this->createUser('employee@example.test', 'employee');

        $this->actingAs($employee, 'sanctum')
            ->postJson('/api/employees', [
                'name' => 'New Admin',
                'email' => 'new-admin@example.test',
                'password' => 'unique-secure-password',
                'role' => 'admin',
            ])
            ->assertForbidden();
    }

    public function test_admin_cannot_create_another_administrator(): void
    {
        $admin = $this->createUser('admin@example.test', 'admin');

        $this->actingAs($admin, 'sanctum')
            ->postJson('/api/employees', [
                'name' => 'New Admin',
                'email' => 'new-admin@example.test',
                'password' => 'unique-secure-password',
                'role' => 'admin',
            ])
            ->assertForbidden();
    }

    public function test_employee_creation_requires_a_unique_password_input(): void
    {
        $manager = $this->createUser('manager@example.test', 'manager');

        $this->actingAs($manager, 'sanctum')
            ->postJson('/api/employees', [
                'name' => 'New Employee',
                'email' => 'new-employee@example.test',
                'role' => 'employee',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('password');
    }

    public function test_employee_creation_does_not_log_the_submitted_password(): void
    {
        Log::spy();
        $manager = $this->createUser('manager@example.test', 'manager');
        $password = 'unique-secure-password';

        $this->actingAs($manager, 'sanctum')
            ->postJson('/api/employees', [
                'name' => 'New Employee',
                'email' => 'new-employee@example.test',
                'password' => $password,
                'role' => 'employee',
            ])
            ->assertCreated();

        Log::shouldHaveReceived('info')
            ->once()
            ->withArgs(fn (...$arguments) => !str_contains(json_encode($arguments), $password));
    }

    public function test_manager_cannot_create_an_admin(): void
    {
        $manager = $this->createUser('manager@example.test', 'manager');

        $this->actingAs($manager, 'sanctum')
            ->postJson('/api/employees', [
                'name' => 'New Admin',
                'email' => 'new-admin@example.test',
                'password' => 'unique-secure-password',
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

    public function test_task_attachments_are_private_and_limited_to_task_participants(): void
    {
        Storage::fake('local');

        $manager = $this->createUser('manager@example.test', 'manager');
        $employee = $this->createUser('employee@example.test', 'employee', 'active', $manager->id);
        $outsider = $this->createUser('outsider@example.test', 'employee');
        $task = $this->createTask($manager, $employee, 'Private attachment task');
        $path = "tasks/{$task->id}/report.pdf";
        Storage::disk('local')->put($path, 'private document');

        $attachment = TaskAttachment::create([
            'task_id' => $task->id,
            'uploaded_by' => $manager->id,
            'file_name' => 'report.pdf',
            'file_path' => $path,
            'mime_type' => 'application/pdf',
            'file_size' => 16,
        ]);

        $this->actingAs($manager, 'sanctum')
            ->get("/api/tasks/{$task->id}/attachments/{$attachment->id}")
            ->assertOk()
            ->assertHeader('content-disposition');

        $this->actingAs($outsider, 'sanctum')
            ->getJson("/api/tasks/{$task->id}/attachments/{$attachment->id}")
            ->assertForbidden();
    }

    public function test_tasks_cannot_be_assigned_to_inactive_users(): void
    {
        $manager = $this->createUser('manager@example.test', 'manager');
        $inactiveEmployee = $this->createUser('inactive@example.test', 'employee', 'inactive', $manager->id);

        $this->actingAs($manager, 'sanctum')
            ->postJson('/api/admin/tasks', [
                'title' => 'Inactive assignment',
                'assigned_to' => $inactiveEmployee->id,
                'priority' => 'Low',
            ])
            ->assertUnprocessable()
            ->assertJsonPath('message', 'Tasks can only be assigned to active users.');
    }

    public function test_uploaded_task_attachment_is_stored_on_the_private_disk(): void
    {
        Storage::fake('local');
        Storage::fake('public');

        $manager = $this->createUser('manager@example.test', 'manager');
        $employee = $this->createUser('employee@example.test', 'employee', 'active', $manager->id);

        $response = $this->actingAs($manager, 'sanctum')
            ->post('/api/admin/tasks', [
                'title' => 'Private upload',
                'assigned_to' => $employee->id,
                'priority' => 'Low',
                'attachment' => UploadedFile::fake()->create('report.pdf', 20, 'application/pdf'),
            ], ['Accept' => 'application/json'])
            ->assertCreated();

        $attachment = TaskAttachment::firstOrFail();
        Storage::disk('local')->assertExists($attachment->file_path);
        Storage::disk('public')->assertMissing($attachment->file_path);
        $response->assertJsonPath(
            'task.attachments.0.url',
            route('tasks.attachments.show', ['task' => $attachment->task_id, 'attachment' => $attachment->id])
        );
    }

    public function test_attachment_migration_command_moves_legacy_files_to_private_storage(): void
    {
        Storage::fake('public');
        Storage::fake('local');

        $manager = $this->createUser('manager@example.test', 'manager');
        $employee = $this->createUser('employee@example.test', 'employee', 'active', $manager->id);
        $task = $this->createTask($manager, $employee, 'Legacy attachment task');
        $path = "tasks/{$task->id}/legacy.pdf";
        Storage::disk('public')->put($path, 'legacy private content');

        TaskAttachment::create([
            'task_id' => $task->id,
            'uploaded_by' => $manager->id,
            'file_name' => 'legacy.pdf',
            'file_path' => $path,
            'mime_type' => 'application/pdf',
            'file_size' => 22,
        ]);

        $this->artisan('task-attachments:secure')->assertExitCode(0);

        Storage::disk('local')->assertExists($path);
        Storage::disk('public')->assertMissing($path);
    }

    public function test_running_database_seeder_does_not_remove_existing_task_data(): void
    {
        $creator = $this->createUser('creator@example.test', 'admin');
        $assignee = $this->createUser('assignee@example.test', 'employee');
        $task = $this->createTask($creator, $assignee, 'Preserved by seed');

        $this->seed(DatabaseSeeder::class);

        $this->assertDatabaseHas('users', ['id' => $creator->id]);
        $this->assertDatabaseHas('users', ['id' => $assignee->id]);
        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'title' => 'Preserved by seed']);
    }

    public function test_deleting_a_user_deactivates_them_without_deleting_reports_or_messages(): void
    {
        $admin = $this->createUser('admin@example.test', 'super_admin');
        $manager = $this->createUser('manager@example.test', 'manager');
        $employee = $this->createUser('employee@example.test', 'employee', 'active', $manager->id);
        $task = $this->createTask($manager, $employee, 'Preserved task');
        $message = Message::create([
            'task_id' => $task->id,
            'user_id' => $employee->id,
            'message' => 'Preserved message',
        ]);
        $manager->createToken('test-token');

        $this->actingAs($admin, 'sanctum')
            ->deleteJson("/api/employees/{$manager->id}")
            ->assertOk()
            ->assertJsonPath('message', 'Staff account deactivated.');

        $this->assertDatabaseHas('users', ['id' => $manager->id, 'status' => 'inactive']);
        $this->assertDatabaseHas('users', ['id' => $employee->id, 'parent_id' => $manager->id]);
        $this->assertDatabaseHas('messages', ['id' => $message->id]);
        $this->assertSame(0, $manager->tokens()->count());
    }

    public function test_database_parent_deletion_does_not_cascade_to_reporting_users(): void
    {
        $manager = $this->createUser('manager@example.test', 'manager');
        $employee = $this->createUser('employee@example.test', 'employee', 'active', $manager->id);

        $manager->delete();

        $this->assertDatabaseHas('users', ['id' => $employee->id, 'parent_id' => null]);
    }

    public function test_employee_cannot_access_admin_dashboard_or_department_tree(): void
    {
        $employee = $this->createUser('employee@example.test', 'employee');

        $this->actingAs($employee, 'sanctum')
            ->getJson('/api/dashboard')
            ->assertForbidden();

        $this->getJson('/api/departments/tree')
            ->assertForbidden();

        $this->getJson('/api/employees')
            ->assertForbidden();
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

    public function test_employee_password_updates_require_at_least_twelve_characters(): void
    {
        $manager = $this->createUser('manager@example.test', 'manager');
        $employee = $this->createUser('employee@example.test', 'employee', 'active', $manager->id);

        $this->actingAs($manager, 'sanctum')
            ->putJson("/api/employees/{$employee->id}", ['password' => '12345678901'])
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
