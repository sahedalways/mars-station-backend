<x-mail.layout>
    <h2 style="margin: 0 0 16px; color: #111827; font-size: 18px; line-height: 24px;">
        Admin Login Code
    </h2>

    <p style="margin: 0 0 16px; color: #374151; font-size: 14px; line-height: 22px;">
        You requested a login code for the Mars Station admin panel.
    </p>

    <div style="margin: 0 0 16px; padding: 16px; background: #f3f4f6; border-radius: 8px; text-align: center; font-size: 28px; letter-spacing: 8px; font-weight: 700; color: #111827;">
        {{ $code }}
    </div>

    <p style="margin: 0 0 8px; color: #6b7280; font-size: 13px; line-height: 20px;">
        This code expires in <strong>{{ $expiresInMinutes }} minutes</strong> and can only be used once.
    </p>

    <p style="margin: 0; color: #6b7280; font-size: 13px; line-height: 20px;">
        If you did not request this code, you can safely ignore this email.
    </p>
</x-mail.layout>
