<?php

namespace Database\Seeders;

use App\Models\Todo;
use App\Models\User;
use App\Models\Project;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TodoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 使用現有的使用者或建立新的
        $user = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => '測試使用者',
                'password' => bcrypt('password'),
            ]
        );

        // 建立測試專案
        $projects = Project::factory(3)->create([
            'user_id' => $user->id,
        ]);

        // 為每個專案建立測試待辦事項
        foreach ($projects as $project) {
            Todo::factory(5)->create([
                'user_id' => $user->id,
                'project_id' => $project->id,
            ]);
        }
    }
}

