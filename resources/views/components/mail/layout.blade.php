@props(['title' => null])

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background: #f1f5f9; padding: 24px 0;">
    <tr>
        <td align="center">
            <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width: 600px; width: 100%; background: #ffffff; border-radius: 12px; overflow: hidden;">
                <tr>
                    <td style="padding: 24px 32px; background: #0f172a;">
                        <span style="font-size: 18px; font-weight: 700; color: #ffffff;">Mars Station</span>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 32px;">
                        @if ($title)
                            <p style="margin: 0 0 16px; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8;">{{ $title }}</p>
                        @endif
                        {{ $slot }}
                    </td>
                </tr>
                <tr>
                    <td style="padding: 16px 32px; border-top: 1px solid #e2e8f0;">
                        <p style="margin: 0; font-size: 12px; color: #94a3b8;">Mars Station · Legal and Agreement Services</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
