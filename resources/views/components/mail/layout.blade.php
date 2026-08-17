@props([
    'subject' => null,
    'title' => null,
    'hideIcon' => false,
])

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="x-apple-disable-message-reformatting">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $subject ?? $title ?? config('app.name') }}</title>

    <style>
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
        body { margin: 0 !important; padding: 0 !important; width: 100% !important; }
        a { color: #a855f7; text-decoration: none; }

        @media only screen and (max-width: 620px) {
            .container { width: 100% !important; padding: 0 !important; }
            .content-padding { padding: 24px !important; }
            .summary-cell { display: block !important; width: 100% !important; padding-bottom: 12px !important; }
            .mars-globe { display: none !important; }
        }
    </style>
</head>
<body style="margin:0; padding:0; background:#0a0518; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#0a0518; background-image: radial-gradient(ellipse at 20% 60%, rgba(139,92,246,0.18) 0%, transparent 50%), radial-gradient(ellipse at 80% 20%, rgba(139,92,246,0.12) 0%, transparent 50%), radial-gradient(ellipse at 90% 90%, rgba(168,85,247,0.12) 0%, transparent 50%), radial-gradient(1.5px 1.5px at 10% 20%, rgba(255,255,255,0.9), transparent), radial-gradient(1px 1px at 18% 55%, rgba(255,255,255,0.7), transparent), radial-gradient(1.5px 1.5px at 30% 70%, rgba(255,255,255,0.7), transparent), radial-gradient(1px 1px at 42% 15%, rgba(255,255,255,0.5), transparent), radial-gradient(1.5px 1.5px at 50% 40%, rgba(255,255,255,0.8), transparent), radial-gradient(1px 1px at 58% 85%, rgba(255,255,255,0.5), transparent), radial-gradient(1.5px 1.5px at 70% 80%, rgba(255,255,255,0.7), transparent), radial-gradient(1px 1px at 75% 30%, rgba(255,255,255,0.6), transparent), radial-gradient(1.5px 1.5px at 85% 15%, rgba(255,255,255,0.9), transparent), radial-gradient(1px 1px at 88% 65%, rgba(255,255,255,0.5), transparent), radial-gradient(1.5px 1.5px at 15% 90%, rgba(255,255,255,0.7), transparent), radial-gradient(1px 1px at 60% 10%, rgba(255,255,255,0.8), transparent), radial-gradient(1.5px 1.5px at 40% 55%, rgba(255,255,255,0.6), transparent), radial-gradient(1px 1px at 90% 60%, rgba(255,255,255,0.7), transparent), radial-gradient(1.5px 1.5px at 25% 35%, rgba(255,255,255,0.8), transparent), radial-gradient(1px 1px at 5% 45%, rgba(255,255,255,0.5), transparent), radial-gradient(1px 1px at 95% 85%, rgba(255,255,255,0.6), transparent); padding:32px 12px;">
        <tr>
            <td align="center">

                <table role="presentation" class="container" width="600" cellpadding="0" cellspacing="0" border="0" style="max-width:600px; width:100%;">

                    {{-- HEADER --}}
                    <tr>
                        <td style="padding: 0; background: linear-gradient(180deg, #130826 0%, #1a0b30 100%); border-radius: 12px 12px 0 0; overflow: hidden; position: relative;">
                            {{-- Mars Globe (right background) --}}
                            <img class="mars-globe" src="{{ config('app.url') }}/images/planets/mars.jpg" alt="" width="150" style="position:absolute; top:-15px; right:-15px; border-radius:50%; opacity:0.65; box-shadow: 0 0 60px rgba(196,130,60,0.35), 0 0 120px rgba(196,130,60,0.15);">
                            {{-- Centered Logo + Text --}}
                            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
                                <tr>
                                    <td align="center" style="padding: 30px 20px 34px; position: relative; z-index: 1;">
                                        <table role="presentation" cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td style="vertical-align: middle; padding-right: 14px;">
                                                    <img src="{{ config('app.url') }}/logo.png" alt="Mars Station" width="52" height="52" style="display:block; border-radius:12px; box-shadow: 0 4px 20px rgba(139,92,246,0.35);">
                                                </td>

                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- WHITE CONTENT CARD --}}
                    <tr>
                        <td class="content-padding" style="background:#ffffff; padding:40px 40px 32px; border-radius:0;">

                            {{-- Icon circle --}}
                            @if (! $hideIcon)
                                <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
                                    <tr>
                                        <td align="center" style="padding-bottom: 20px;">
                                            <table role="presentation" cellpadding="0" cellspacing="0" border="0">
                                                <tr>
                                                    <td style="width:64px; height:64px; background:#f3e8ff; border-radius:50%; text-align:center; vertical-align:middle;">
                                                        {!! $icon ?? '<span style="color:#7c3aed; font-size:26px; line-height:64px;">&#128274;</span>' !!}
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            @endif

                            {{ $slot }}

                        </td>
                    </tr>

                    {{-- FOOTER --}}
                    <tr>
                        <td style="background:#0a0518; padding:24px 20px; border-radius: 0 0 12px 12px; text-align:center;">
                            <table role="presentation" cellpadding="0" cellspacing="0" border="0" align="center" style="margin-bottom:14px;">
                                <tr>
                                    @foreach ([
                                        ['url' => 'https://www.facebook.com/marsstation.dev/', 'label' => 'f'],
                                        ['url' => 'https://www.instagram.com/marsstation.dev/', 'label' => 'ig'],
                                        ['url' => 'https://www.tiktok.com/@marsstation.dev', 'label' => 'tk'],
                                        ['url' => 'https://www.youtube.com/channel/UCN2oHe5nB6EX29IqslGTy3w', 'label' => 'yt'],
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
