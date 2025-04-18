<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Services\EmailService;
use Illuminate\Support\Facades\Log;

class FetchEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch new incoming emails via IMAP and store them in the inbox';

    protected $emailService;

    public function __construct(EmailService $emailService)
    {
        parent::__construct();

        $this->emailService = $emailService;
    }

    public function handle()
    {
        Log::info('Starting mail:fetch');

        // loop through every user (or only those who’ve configured IMAP)
        User::chunk(50, function($users) {
            foreach ($users as $user) {
                try {
                    $fetched = $this->emailService->fetchEmails($user->id);
                    Log::info("Fetched " . count($fetched) . " for user {$user->id}");
                } catch (\Throwable $e) {
                    Log::error("Error fetching for user {$user->id}: " . $e->getMessage());
                }
            }
        });

        Log::info('Finished mail:fetch');
        return 0;
    }
}