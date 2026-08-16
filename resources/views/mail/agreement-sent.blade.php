<x-mail.layout>
    <h2 style="margin: 0 0 16px; color: #111827; font-size: 18px; line-height: 24px;">
        Your agreement is ready to review
    </h2>

    <p style="margin: 0 0 16px; color: #374151; font-size: 14px; line-height: 22px;">
        Hi {{ $agreement->client_name }}, please review and sign your agreement below.
    </p>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin: 0 0 20px; border: 1px solid #e5e7eb; border-radius: 8px;">
        <tr>
            <td style="padding: 12px 16px; width: 40%; color: #6b7280; font-size: 13px;">Agreement</td>
            <td style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #111827;">{{ $agreement->title }}</td>
        </tr>
        <tr>
            <td style="padding: 12px 16px; border-top: 1px solid #e5e7eb; width: 40%; color: #6b7280; font-size: 13px;">Agreement No.</td>
            <td style="padding: 12px 16px; border-top: 1px solid #e5e7eb; font-size: 13px; color: #111827;">{{ $agreement->agreement_number }}</td>
        </tr>
        <tr>
            <td style="padding: 12px 16px; border-top: 1px solid #e5e7eb; width: 40%; color: #6b7280; font-size: 13px;">Version</td>
            <td style="padding: 12px 16px; border-top: 1px solid #e5e7eb; font-size: 13px; color: #111827;">V{{ $version->version }}</td>
        </tr>
    </table>

    <a href="{{ $link->publicUrl() }}"
       style="display: inline-block; padding: 12px 24px; background: #4f46e5; color: #ffffff; text-decoration: none; border-radius: 8px; font-size: 14px; font-weight: 600;">
        View &amp; Sign Agreement
    </a>

    <p style="margin: 20px 0 0; color: #6b7280; font-size: 13px; line-height: 20px;">
        If the button doesn't work, copy and paste this link into your browser:
    </p>
    <p style="margin: 4px 0 0; color: #6b7280; font-size: 12px; line-height: 18px; word-break: break-all;">
        {{ $link->publicUrl() }}
    </p>
</x-mail.layout>
