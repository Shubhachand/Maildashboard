<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;

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
            
            // Try sending a simple test email first
            Mail::send('emails.debug-test', [], function(Message $message) use ($to) {
                $message->to($to)
                       ->subject('Test Email');
            });
            
            Log::info('Test email sent successfully');
            
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
     * Fetch emails from the user's mail server.
     *
     * @param int $userId
     * @return array
     */
    public function fetchEmails($userId)
    {
        // Implementation for fetching emails from IMAP server
        return [];
    }
}