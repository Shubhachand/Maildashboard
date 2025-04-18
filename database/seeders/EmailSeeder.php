<?php

namespace Database\Seeders;

use App\Models\Email;
use App\Models\Folder;
use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class EmailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $user = User::where('email', 'demo@example.com')->first();
        
        if (!$user) {
            return;
        }
        
        $folders = $user->folders()->get();
        $inboxFolder = $folders->where('type', 'inbox')->first();
        $sentFolder = $folders->where('type', 'sent')->first();
        $workFolder = $folders->where('name', 'Work')->first();
        $personalFolder = $folders->where('name', 'Personal')->first();
        
        // Create 20 received emails in inbox
        for ($i = 0; $i < 20; $i++) {
            Email::create([
                'user_id' => $user->id,
                'folder_id' => $inboxFolder->id,
                'from' => $faker->email,
                'to' => $user->email,
                'subject' => $faker->sentence,
                'body' => '<p>' . implode('</p><p>', $faker->paragraphs(3)) . '</p>',
                'is_read' => $faker->boolean(70),
                'is_starred' => $faker->boolean(20),
                'is_important' => $faker->boolean(30),
                'received_at' => $faker->dateTimeBetween('-2 weeks', 'now'),
            ]);
        }
        
        // Create 10 sent emails
        for ($i = 0; $i < 10; $i++) {
            Email::create([
                'user_id' => $user->id,
                'folder_id' => $sentFolder->id,
                'from' => $user->email,
                'to' => $faker->email,
                'subject' => $faker->sentence,
                'body' => '<p>' . implode('</p><p>', $faker->paragraphs(3)) . '</p>',
                'is_read' => true,
                'is_starred' => $faker->boolean(10),
                'is_important' => $faker->boolean(20),
                'received_at' => $faker->dateTimeBetween('-2 weeks', 'now'),
            ]);
        }
        
        // Create 5 work emails
        for ($i = 0; $i < 5; $i++) {
            Email::create([
                'user_id' => $user->id,
                'folder_id' => $workFolder->id,
                'from' => $faker->email,
                'to' => $user->email,
                'subject' => 'Work: ' . $faker->sentence,
                'body' => '<p>' . implode('</p><p>', $faker->paragraphs(3)) . '</p>',
                'is_read' => $faker->boolean(90),
                'is_starred' => $faker->boolean(30),
                'is_important' => $faker->boolean(60),
                'received_at' => $faker->dateTimeBetween('-2 weeks', 'now'),
            ]);
        }
        
        // Create 5 personal emails
        for ($i = 0; $i < 5; $i++) {
            Email::create([
                'user_id' => $user->id,
                'folder_id' => $personalFolder->id,
                'from' => $faker->email,
                'to' => $user->email,
                'subject' => 'Personal: ' . $faker->sentence,
                'body' => '<p>' . implode('</p><p>', $faker->paragraphs(3)) . '</p>',
                'is_read' => $faker->boolean(80),
                'is_starred' => $faker->boolean(40),
                'is_important' => $faker->boolean(20),
                'received_at' => $faker->dateTimeBetween('-2 weeks', 'now'),
            ]);
        }
    }
}