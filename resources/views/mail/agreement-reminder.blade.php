<x-mail.layout>
    <div style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #1f2937; max-width: 600px; margin: 0 auto; padding: 10px 0;">

        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 24px; text-align: center;">
            <tr>
                <td align="center">
                    <div style="display: inline-block; width: 56px; height: 56px; background-color: #f3e8ff; border-radius: 50%; text-align: center; line-height: 56px; margin-bottom: 16px;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#7c3aed" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle;">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                            <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                        </svg>
                    </div>
                    <h1 style="margin: 0 0 6px; font-size: 22px; font-weight: 700; color: #0f172a; tracking: -0.02em;">
                        Friendly Reminder
                    </h1>
                    <p style="margin: 0 0 16px; font-size: 15px; color: #334155; font-weight: 500;">
                        Your Agreement is Awaiting Your Action
                    </p>
                    <div style="width: 50px; height: 2px; background: linear-gradient(90deg, #c4b5fd, #8b5cf6); margin: 0 auto; border-radius: 2px;"></div>
                </td>
            </tr>
        </table>

        <div style="margin-bottom: 24px; font-size: 14px; line-height: 22px; color: #334155;">
            <p style="margin: 0 0 12px; font-weight: 700; color: #0f172a;">
                Hello {{ $agreement->client_name }},
            </p>
            <p style="margin: 0 0 8px;">
                This is a friendly reminder that the agreement sent by Mars Station is still pending your review and signature.
            </p>
            <p style="margin: 0;">
                Please take a moment to review the agreement and complete the steps to accept and sign.
            </p>
        </div>

        <div style="background-color: #f8f8fd; border-radius: 12px; padding: 20px; margin-bottom: 24px; border: 1px solid #f1f0fb;">
            <h3 style="margin: 0 0 16px; color: #6d28d9; font-size: 14px; font-weight: 700;">
                Agreement Summary
            </h3>

            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="font-size: 13px;">
                <tr>
                    <td width="33%" valign="top" style="padding-right: 12px; padding-bottom: 16px; border-right: 1px solid #eef0f6;">
                        <table cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td valign="top" style="padding-right: 8px;">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6d28d9" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                                </td>
                                <td>
                                    <span style="color: #64748b; display: block; font-size: 12px; margin-bottom: 2px;">Agreement Title</span>
                                    <strong style="color: #1e293b; font-weight: 600; line-height: 16px; display: block;">{{ $agreement->title }}</strong>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td width="33%" valign="top" style="padding-left: 12px; padding-right: 12px; padding-bottom: 16px; border-right: 1px solid #eef0f6;">
                        <table cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td valign="top" style="padding-right: 8px;">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6d28d9" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                </td>
                                <td>
                                    <span style="color: #64748b; display: block; font-size: 12px; margin-bottom: 2px;">Valid Until</span>
                                    <strong style="color: #1e293b; font-weight: 600; line-height: 16px; display: block;">{{ $agreement->valid_until ? \Carbon\Carbon::parse($agreement->valid_until)->format('M d, Y') : 'N/A' }}</strong>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td width="34%" valign="top" style="padding-left: 12px; padding-bottom: 16px;">
                        <table cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td valign="top" style="padding-right: 8px;">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6d28d9" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                </td>
                                <td>
                                    <span style="color: #64748b; display: block; font-size: 12px; margin-bottom: 2px;">Date Sent</span>
                                    <strong style="color: #1e293b; font-weight: 600; line-height: 16px; display: block;">{{ \Carbon\Carbon::parse($agreement->created_at)->format('M d, Y h:i A') }}</strong>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="padding-top: 12px; border-top: 1px solid #eef0f6;"></td>
                </tr>
                <tr>
                    <td valign="top" style="padding-right: 12px; border-right: 1px solid #eef0f6;">
                        <table cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td valign="top" style="padding-right: 8px;">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6d28d9" stroke-width="2"><line x1="4" y1="9" x2="20" y2="9"></line><line x1="4" y1="15" x2="20" y2="15"></line><line x1="10" y1="3" x2="8" y2="21"></line><line x1="16" y1="3" x2="14" y2="21"></line></svg>
                                </td>
                                <td>
                                    <span style="color: #64748b; display: block; font-size: 12px; margin-bottom: 2px;">Agreement No.</span>
                                    <strong style="color: #6d28d9; font-weight: 600; line-height: 16px; display: block;">{{ $agreement->agreement_number }}</strong>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td valign="top" style="padding-left: 12px; padding-right: 12px; border-right: 1px solid #eef0f6;">
                        <table cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td valign="top" style="padding-right: 8px;">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6d28d9" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
                                </td>
                                <td>
                                    <span style="color: #64748b; display: block; font-size: 12px; margin-bottom: 2px;">Payment Type</span>
                                    <strong style="color: #1e293b; font-weight: 600; line-height: 16px; display: block;">{{ $agreement->payment_type ?? 'Milestone Payment' }}</strong>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td valign="top" style="padding-left: 12px;">
                        <table cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td valign="top" style="padding-right: 8px;">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6d28d9" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                </td>
                                <td>
                                    <span style="color: #64748b; display: block; font-size: 12px; margin-bottom: 4px;">Status</span>
                                    <span style="background-color: #fef3c7; color: #92400e; font-size: 11px; font-weight: 600; padding: 3px 8px; border-radius: 4px; display: inline-block;">
                                        {{ $agreement->status_label ?? 'Pending Signature' }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>

        <div style="text-align: center; margin-bottom: 24px;">
            <a href="{{ $link->publicUrl() }}" style="display: inline-block; background-color: #09051d; color: #ffffff; text-decoration: none; padding: 14px 32px; border-radius: 50px; font-size: 14px; font-weight: 600; box-shadow: 0 4px 12px rgba(9, 5, 29, 0.15);">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" style="vertical-align: sub; margin-right: 6px;"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                Review &amp; Sign Agreement
            </a>
            <p style="margin: 10px 0 0; font-size: 12px; color: #64748b;">
                Click the button above to review the agreement and take the next step.
            </p>
        </div>

        <div style="background-color: #fafafd; border: 1px solid #eef0f6; border-radius: 10px; padding: 12px 16px; margin-bottom: 20px;">
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td width="36" valign="middle">
                        <div style="width: 32px; height: 32px; background-color: #f3e8ff; border-radius: 50%; text-align: center; line-height: 32px;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#7c3aed" stroke-width="2" style="vertical-align: middle;"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
                        </div>
                    </td>
                    <td style="font-size: 12px; color: #475569; padding-left: 8px;">
                        <div>If the button doesn't work, copy and paste this link into your browser:</div>
                        <a href="{{ $link->publicUrl() }}" style="color: #6d28d9; text-decoration: underline; word-break: break-all; font-weight: 500;">
                            {{ $link->publicUrl() }}
                        </a>
                    </td>
                </tr>
            </table>
        </div>

        <div style="background-color: #fafafd; border: 1px solid #eef0f6; border-radius: 10px; padding: 16px; margin-bottom: 24px;">
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td valign="top">
                        <table cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td width="36" valign="top">
                                    <div style="width: 32px; height: 32px; background-color: #f3e8ff; border-radius: 50%; text-align: center; line-height: 32px;">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#7c3aed" stroke-width="2" style="vertical-align: middle;"><path d="M3 18v-6a9 9 0 0 1 18 0v6"></path><path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"></path></svg>
                                    </div>
                                </td>
                                <td style="padding-left: 8px;">
                                    <strong style="font-size: 13px; color: #0f172a; display: block; margin-bottom: 2px;">Need Help?</strong>
                                    <span style="font-size: 12px; color: #64748b;">If you have any questions, feel free to contact us. We're here to help.</span>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td align="right" valign="middle" style="font-size: 12px;">
                        <div style="margin-bottom: 4px;">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2" style="vertical-align: middle; margin-right: 4px;"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                            <a href="mailto:support@marsstation.dev" style="color: #6d28d9; text-decoration: underline;">support@marsstation.dev</a>
                        </div>
                        <div>
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2" style="vertical-align: middle; margin-right: 4px;"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
                            <a href="https://marsstation.dev" style="color: #6d28d9; text-decoration: underline;">marsstation.dev</a>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <div style="text-align: center; font-size: 12px; color: #64748b; line-height: 18px;">
            Thank you for choosing Mars Station.<br>
            We appreciate your time and trust.
        </div>

    </div>
</x-mail.layout>
