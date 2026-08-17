<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="x-apple-disable-message-reformatting">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $subject ?? config('app.name') }}</title>

    <style>
        /* Reset */
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
        body { margin: 0 !important; padding: 0 !important; width: 100% !important; }
        a { color: #a855f7; text-decoration: none; }

        /* Responsive */
        @media only screen and (max-width: 620px) {
            .container { width: 100% !important; padding: 0 !important; }
            .content-padding { padding: 24px !important; }
            .summary-cell { display: block !important; width: 100% !important; padding-bottom: 12px !important; }
        }
    </style>
</head>
<body style="margin:0; padding:0; background:#0a0518; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

    {{-- Outer wrapper (dark cosmic) --}}
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#0a0518; padding:32px 12px;">
        <tr>
            <td align="center">

                {{-- Main container --}}
                <table role="presentation" class="container" width="600" cellpadding="0" cellspacing="0" border="0" style="max-width:600px; width:100%;">

                    {{-- ========== HEADER (dark with logo) ========== --}}
                    <tr>
                        <td align="center" style="padding: 24px 20px 28px; background: linear-gradient(180deg, #130826 0%, #1a0b30 100%); border-radius: 12px 12px 0 0;">
                            <img src="{{ config('app.url') }}/logo.png" alt="Mars Station" width="160" style="display:block; max-width:160px; height:auto;">
                        </td>
                    </tr>

                    {{-- ========== WHITE CONTENT CARD ========== --}}
                    <tr>
                        <td class="content-padding" style="background:#ffffff; padding:40px 40px 32px; border-radius:0;">

                            {{-- Icon circle — only shows if $icon is explicitly passed --}}
                            @if (isset($icon) && $icon)
                                <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
                                    <tr>
                                        <td align="center" style="padding-bottom: 20px;">
                                            <table role="presentation" cellpadding="0" cellspacing="0" border="0">
                                                <tr>
                                                    <td style="width:64px; height:64px; background:#f3e8ff; border-radius:50%; text-align:center; vertical-align:middle;">
                                                        {!! $icon !!}
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            @endif

                            {{-- Main slot --}}
                            {{ $slot }}

                        </td>
                    </tr>

                    {{-- ========== FOOTER (dark) ========== --}}
                    <tr>
                        <td style="background:#0a0518; padding:24px 20px; border-radius: 0 0 12px 12px; text-align:center;">
                            {{-- Social icons --}}
                            <table role="presentation" cellpadding="0" cellspacing="0" border="0" align="center" style="margin-bottom:14px;">
                                <tr>
                                    @foreach ([
                                        ['url' => '#', 'label' => 'f'],
                                        ['url' => '#', 'label' => 't'],
                                        ['url' => '#', 'label' => 'in'],
                                        ['url' => '#', 'label' => 'ig'],
                                    ] as $social)
                                        <td style="padding:0 6px;">
                                            <a href="{{ $social['url'] }}" style="display:inline-block; width:32px; height:32px; background:#1e1030; border-radius:50%; color:#a855f7; font-size:13px; font-weight:700; line-height:32px; text-align:center; text-decoration:none;">
                                                {{ $social['label'] }}
                                            </a>
                                        </td>
                                    @endforeach
                                </tr>
                            </table>

                            <p style="margin:0; color:#94a3b8; font-size:12px; line-height:18px;">
                                &copy; {{ date('Y') }} Mars Station. All rights reserved.<br>
                                This is an automated email, please do not reply.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
