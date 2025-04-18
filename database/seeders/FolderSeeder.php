<?php

namespace Database\Seeders;

use App\Models\Folder;
use App\Models\User;
use Illuminate\Database\Seeder;

class FolderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('email', 'demo@example.com')->first();
        
        if (!$user) {
            return;
        }
        
        $folders = [
            [
                'name' => 'Inbox',
                'type' => 'inbox',
                'sort_order' => 0,
            ],
            [
                'name' => 'Sent',
                'type' => 'sent',
                'sort_order' => 1,
            ],
            [
                'name' => 'Drafts',
                'type' => 'draft',
                'sort_order' => 2,
            ],
            [
                'name' => 'Trash',
                'type' => 'trash',
                'sort_order' => 3,
            ],
            [
                'name' => 'Work',
                'type' => 'custom',
                'sort_order' => 4,
            ],
            [
                'name' => 'Personal',
                'type' => 'custom',
                'sort_order' => 5,
            ],
        ];
        
        foreach ($folders as $folder) {
            Folder::create([
                'user_id' => $user->id,
                'name' => $folder['name'],
                'type' => $folder['type'],
                'sort_order' => $folder['sort_order'],
            ]);
        }
    }
}