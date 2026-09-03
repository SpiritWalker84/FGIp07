<?php

namespace Database\Seeders;

use App\Modules\Task\Models\Task;
use App\Modules\User\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@taskhub.local'],
            [
                'name' => 'Админ',
                'password' => Hash::make('password'),
                'role' => User::ROLE_ADMIN,
            ]
        );

        $manager = User::updateOrCreate(
            ['email' => 'manager@taskhub.local'],
            [
                'name' => 'Менеджер',
                'password' => Hash::make('password'),
                'role' => User::ROLE_MANAGER,
            ]
        );

        $user = User::updateOrCreate(
            ['email' => 'user@taskhub.local'],
            [
                'name' => 'Сотрудник',
                'password' => Hash::make('password'),
                'role' => User::ROLE_USER,
            ]
        );

        $tasks = [
            ['title' => 'Подготовить отчёт', 'status' => Task::STATUS_NEW, 'creator_id' => $admin->id, 'assignee_id' => $user->id],
            ['title' => 'Согласовать договор', 'status' => Task::STATUS_IN_PROGRESS, 'creator_id' => $manager->id, 'assignee_id' => $manager->id],
            ['title' => 'Обновить прайс', 'status' => Task::STATUS_DONE, 'creator_id' => $admin->id, 'assignee_id' => $user->id],
            ['title' => 'Проверить заявки', 'status' => Task::STATUS_NEW, 'creator_id' => $user->id, 'assignee_id' => $manager->id],
            ['title' => 'Созвон с клиентом', 'status' => Task::STATUS_IN_PROGRESS, 'creator_id' => $manager->id, 'assignee_id' => $user->id],
        ];

        foreach ($tasks as $data) {
            Task::updateOrCreate(
                ['title' => $data['title'], 'creator_id' => $data['creator_id']],
                array_merge($data, ['description' => 'Демо-задача для тестирования MVP.'])
            );
        }
    }
}
