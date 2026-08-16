<x-mail.layout>
    <x-slot:title>Your verification code</x-slot:title>

    <h1 style="margin: 0 0 16px; font-size: 20px; color: #0f172a;">Agreement access code</h1>

    <p style="margin: 0 0 16px; font-size: 14px; line-height: 1.6; color: #334155;">
        You requested access to agreement <strong>{{ $agreement->agreement_number }}</strong>.
        Use the code below to continue. It expires in {{ config('mars.agreement_otp.expiry_minutes', 5) }} minutes.
    </p>

    <div style="margin: 24px 0; text-align: center;">
        <span style="display: inline-block; padding: 12px 28px; border-radius: 10px; background: #eef2ff; color: #4338ca; font-size: 28px; font-weight: 700; letter-spacing: 8px;">{{ $otp }}</span>
    </div>

    <p style="margin: 0; font-size: 13px; line-height: 1.6; color: #64748b;">
        If you did not request this code, you can safely ignore this email.
    </p>
</x-mail.layout>
