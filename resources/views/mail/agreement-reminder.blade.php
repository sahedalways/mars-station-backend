<x-mail.layout>
    <h2 style="margin: 0 0 16px; color: #111827; font-size: 18px; line-height: 24px;">
        Agreement Reminder
    </h2>

    <p style="margin: 0 0 16px; color: #374151; font-size: 14px; line-height: 22px;">
        Hi {{ $agreement->client_name }}, we're still waiting for your review of
        <strong>{{ $agreement->title }}</strong> ({{ $agreement->agreement_number }}).
    </p>

    <p style="margin: 0 0 20px; color: #374151; font-size: 14px; line-height: 22px;">
        You can review and sign your agreement using the button below.
    </p>

    <a href="{{ $link->publicUrl() }}"
       style="display: inline-block; padding: 12px 24px; background: #4f46e5; color: #ffffff; text-decoration: none; border-radius: 8px; font-size: 14px; font-weight: 600;">
        Review Agreement
    </a>

    <p style="margin: 20px 0 0; color: #6b7280; font-size: 13px; line-height: 20px;">
        If the button doesn't work, copy and paste this link into your browser:
    </p>
    <p style="margin: 4px 0 0; color: #6b7280; font-size: 12px; line-height: 18px; word-break: break-all;">
        {{ $link->publicUrl() }}
    </p>
</x-mail.layout>
