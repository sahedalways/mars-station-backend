<x-mail.layout>
    <x-slot:title>Your export is ready</x-slot:title>

    <h1 style="margin: 0 0 16px; font-size: 20px; color: #0f172a;">Payment export ready</h1>

    <p style="margin: 0 0 16px; font-size: 14px; line-height: 1.6; color: #334155;">
        Your payment export has been generated. You can download it from the admin panel.
    </p>

    <p style="margin: 0; font-size: 14px; line-height: 1.6; color: #334155;">
        File: <strong>{{ $filePath }}</strong>
    </p>
</x-mail.layout>
