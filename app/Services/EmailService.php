<?php

namespace App\Services;

use App\Jobs\SendEmailJob;
use App\Mail\StatusNotificationMail;
use App\Models\Admin;
use App\Models\EmailLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Mail\Mailable;

class EmailService
{
    public function sendStatusNotification(string $to, array $data, ?Model $emailable = null, ?Admin $admin = null): EmailLog
    {
        return $this->send(new StatusNotificationMail($data), $to, $data['event'] ?? 'status.changed', $emailable, $admin);
    }

    public function send(
        Mailable $mailable,
        string $to,
        ?string $eventType = null,
        ?Model $emailable = null,
        ?Admin $admin = null
    ): EmailLog {
        $mailable->to($to);

        $log = EmailLog::create([
            'to_email' => $to,
            'subject' => $mailable->subject ?? get_class($mailable),
            'mailable' => get_class($mailable),
            'event_type' => $eventType,
            'emailable_type' => $emailable ? get_class($emailable) : null,
            'emailable_id' => $emailable?->getKey(),
            'status' => 'queued',
            'admin_id' => $admin?->getKey(),
        ]);

        SendEmailJob::dispatch($log->id, $mailable);

        return $log;
    }
}
