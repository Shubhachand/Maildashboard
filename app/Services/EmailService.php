<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;
use App\Models\Email;
use Carbon\Carbon;

class EmailService
{
    /**
     * Send an email.
     *
     * @param string $from
     * @param string $to
     * @param string $cc
     * @param string $bcc
     * @param string $subject
     * @param string $body
     * @return bool
     */
    public function sendEmail($from, $to, $cc, $bcc, $subject, $body)
    {
        try {
            // Log the attempt
            Log::info('Attempting to send email', [
                'from' => $from,
                'to' => $to,
                'subject' => $subject
            ]);
            
            // Now try the actual email
            Mail::send([], [], function(Message $message) use ($from, $to, $cc, $bcc, $subject, $body) {
                $message->to($to)
                       ->subject($subject);
                
                // Set the from address if different from the default
                if ($from != config('mail.from.address')) {
                    $message->from($from, config('mail.from.name'));
                }
                
                if (!empty($cc)) {
                    $message->cc($cc);
                }
                
                if (!empty($bcc)) {
                    $message->bcc($bcc);
                }
                
                // Set HTML content
                $message->html($body);
            });
            
            Log::info('Email sent successfully');
            
            return true;
        } catch (\Exception $e) {
            Log::error('Email sending error: ' . $e->getMessage());
            Log::error('Error details: ' . $e->getTraceAsString());
            return false;
        }
    }

    /**
     * Pull new messages from the user’s Gmail inbox via IMAP.
     *
     * @param int $userId
     * @return void
     */
    public function fetchEmails(int $userId)
    {
        // Build the IMAP mailbox string
        $mailbox = sprintf(
            '{%s:%d/imap/%s}INBOX',
            config('mail.imap.host'),
            config('mail.imap.port'),
            config('mail.imap.encryption')
        );

        // Open IMAP connection
        $inbox = @imap_open($mailbox, config('mail.username'), config('mail.password'));

        if (!$inbox) {
            Log::error('IMAP connect failed: ' . imap_last_error());
            return;
        }

        // Search for unseen messages
        $messages = imap_search($inbox, 'UNSEEN');
        if (!empty($messages)) {
            foreach ($messages as $msgNum) {
                $header = imap_headerinfo($inbox, $msgNum);
                $body = imap_fetchbody($inbox, $msgNum, 1.1) 
                      ?: imap_fetchbody($inbox, $msgNum, 1);

                Email::create([
                    'user_id'     => $userId,
                    'folder_id'   => $this->getOrCreateFolder($userId, 'inbox'),
                    'from'        => $header->fromaddress,
                    'to'          => $header->toaddress,
                    'cc'          => $header->ccaddress ?? '',
                    'bcc'         => $header->bccaddress ?? '',
                    'subject'     => $header->subject,
                    'body'        => $body,
                    'is_read'     => false,
                    'received_at' => Carbon::parse($header->date),
                ]);

                // Mark as seen so we don't fetch it again
                imap_setflag_full($inbox, $msgNum, "\\Seen");
            }
        }

        imap_close($inbox);
    }
    
    /**
     * Helper to retrieve or create a folder of the given type for a user.
     *
     * @param int $userId
     * @param string $type
     * @return int
     */
    protected function getOrCreateFolder(int $userId, string $type): int
    {
        $folder = \App\Models\Folder::firstOrCreate(
            ['user_id' => $userId, 'type' => $type],
            ['name' => ucfirst($type), 'sort_order' => 0]
        );
        return $folder->id;
    }
}