<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subjectLine }}</title>
</head>
<body style="margin:0;padding:0;background-color:#f4f7f9;font-family:Inter,Segoe UI,Arial,sans-serif;color:#0f172a;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color:#f4f7f9;margin:0;padding:32px 0;width:100%;">
        <tr>
            <td align="center">
                <table role="presentation" width="640" cellspacing="0" cellpadding="0" style="width:640px;max-width:640px;background:#ffffff;border:1px solid #e2e8f0;border-radius:20px;overflow:hidden;box-shadow:0 18px 60px rgba(15,23,42,0.08);">
                    <tr>
                        <td style="padding:0;">
                            <div style="background:linear-gradient(135deg,#0f172a 0%,#1e293b 100%);padding:36px 40px 30px 40px;">
                                <div style="font-size:12px;letter-spacing:0.18em;text-transform:uppercase;color:#86efac;font-weight:700;">
                                    Event Registration
                                </div>
                                <h1 style="margin:14px 0 0 0;font-size:32px;line-height:1.08;color:#ffffff;font-weight:700;">
                                    {{ $isWaitlistPromotion ? 'Your seat is confirmed.' : 'You are officially registered.' }}
                                </h1>
                                <p style="margin:14px 0 0 0;font-size:16px;line-height:1.7;color:#cbd5e1;">
                                    {{ $isWaitlistPromotion
                                        ? "A place opened up and your registration for {$event->title} has been moved from the waitlist into confirmed attendance."
                                        : "Your registration for {$event->title} has been confirmed. Everything you need is now in your event workspace." }}
                                </p>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:32px 40px 8px 40px;">
                            <p style="margin:0 0 18px 0;font-size:16px;line-height:1.7;color:#334155;">
                                Hello {{ $recipientName }},
                            </p>

                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="width:100%;border:1px solid #e2e8f0;border-radius:16px;background:#f8fafc;">
                                <tr>
                                    <td style="padding:24px 24px 10px 24px;">
                                        <div style="font-size:12px;letter-spacing:0.16em;text-transform:uppercase;color:#64748b;font-weight:700;">
                                            Event Details
                                        </div>
                                        <h2 style="margin:10px 0 0 0;font-size:24px;line-height:1.2;color:#0f172a;font-weight:700;">
                                            {{ $event->title }}
                                        </h2>
                                        @if($event->theme)
                                            <p style="margin:10px 0 0 0;font-size:15px;line-height:1.7;color:#475569;">
                                                {{ $event->theme }}
                                            </p>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:0 24px 24px 24px;">
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="width:100%;border-collapse:collapse;">
                                            <tr>
                                                <td style="padding:14px 0;border-top:1px solid #e2e8f0;width:34%;font-size:13px;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#64748b;">Date</td>
                                                <td style="padding:14px 0;border-top:1px solid #e2e8f0;font-size:15px;color:#0f172a;">{{ $dateRange }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding:14px 0;border-top:1px solid #e2e8f0;width:34%;font-size:13px;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#64748b;">Time</td>
                                                <td style="padding:14px 0;border-top:1px solid #e2e8f0;font-size:15px;color:#0f172a;">{{ $timeRange }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding:14px 0;border-top:1px solid #e2e8f0;width:34%;font-size:13px;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#64748b;">Format</td>
                                                <td style="padding:14px 0;border-top:1px solid #e2e8f0;font-size:15px;color:#0f172a;">{{ ucfirst($event->mode ?? 'Hybrid') }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding:14px 0;border-top:1px solid #e2e8f0;width:34%;font-size:13px;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#64748b;">Location</td>
                                                <td style="padding:14px 0;border-top:1px solid #e2e8f0;font-size:15px;color:#0f172a;">{{ $locationDisplay }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding:14px 0;border-top:1px solid #e2e8f0;width:34%;font-size:13px;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#64748b;">Entry Fee</td>
                                                <td style="padding:14px 0;border-top:1px solid #e2e8f0;font-size:15px;color:#0f172a;">{{ $entryFeeDisplay }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:8px 40px;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="width:100%;">
                                <tr>
                                    <td style="padding:0;">
                                        <div style="border:1px solid #d1fae5;background:#f0fdf4;border-radius:16px;padding:22px 24px;">
                                            <div style="font-size:12px;letter-spacing:0.16em;text-transform:uppercase;color:#15803d;font-weight:700;">
                                                What Happens Next
                                            </div>
                                            <ul style="margin:14px 0 0 0;padding-left:20px;color:#166534;">
                                                @foreach($nextSteps as $nextStep)
                                                    <li style="margin:0 0 10px 0;font-size:15px;line-height:1.7;">{{ $nextStep }}</li>
                                                @endforeach
                                                @foreach($modeTips as $tip)
                                                    <li style="margin:0 0 10px 0;font-size:15px;line-height:1.7;">{{ $tip }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:24px 40px 10px 40px;" align="center">
                            <a href="{{ $workspaceUrl }}" style="display:inline-block;background:#0f172a;color:#ffffff;text-decoration:none;font-size:15px;font-weight:700;padding:15px 28px;border-radius:10px;">
                                Open Event Workspace
                            </a>
                            <p style="margin:14px 0 0 0;font-size:13px;line-height:1.6;color:#64748b;">
                                A calendar invite is attached to this email for quick saving.
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:14px 40px 36px 40px;">
                            <div style="border-top:1px solid #e2e8f0;padding-top:18px;">
                                <p style="margin:0;font-size:14px;line-height:1.7;color:#475569;">
                                    Need help or need to make a change? Visit your event workspace or reply to this email{{ $contactEmail ? ' using '.$contactEmail : '' }}.
                                </p>
                                <p style="margin:16px 0 0 0;font-size:14px;line-height:1.7;color:#475569;">
                                    Best regards,<br>{{ $appName }} Team
                                </p>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
