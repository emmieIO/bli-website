<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light dark">
    <meta name="supported-color-schemes" content="light dark">
    <title>{{ $subjectLine }}</title>
    <style>
        .email-bg { background-color: #f4f7f9; }
        .email-card { background: #ffffff; border: 1px solid #e2e8f0; }
        .email-header { background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); }
        .email-header-badge { color: #86efac; }
        .email-header-title { color: #ffffff; }
        .email-header-text { color: #cbd5e1; }
        .email-body-text { color: #334155; }
        .email-detail-card { border: 1px solid #e2e8f0; background: #f8fafc; }
        .email-detail-label { color: #64748b; }
        .email-detail-value { color: #0f172a; }
        .email-detail-border { border-top: 1px solid #e2e8f0; }
        .email-heading { color: #0f172a; }
        .email-next-panel { border: 1px solid #d1fae5; background: #f0fdf4; }
        .email-next-label { color: #15803d; }
        .email-next-text { color: #166534; }
        .email-button { background: #0f172a; color: #ffffff; }
        .email-footer-border { border-top: 1px solid #e2e8f0; }
        .email-footer-text { color: #475569; }
        .email-muted { color: #64748b; }

        @media (prefers-color-scheme: dark) {
            .email-bg { background-color: #0a1628 !important; }
            .email-card { background: #12233d !important; border-color: #1e2d44 !important; }
            .email-header { background: linear-gradient(135deg, #001830 0%, #002147 100%) !important; }
            .email-header-badge { color: #86efac !important; }
            .email-header-title { color: #ffffff !important; }
            .email-header-text { color: #94a3b8 !important; }
            .email-body-text { color: #cbd5e1 !important; }
            .email-detail-card { border-color: #1e2d44 !important; background: #0f1d33 !important; }
            .email-detail-label { color: #94a3b8 !important; }
            .email-detail-value { color: #e2e8f0 !important; }
            .email-detail-border { border-top-color: #1e2d44 !important; }
            .email-heading { color: #ffffff !important; }
            .email-next-panel { border-color: #1a3a1a !important; background: #0f2218 !important; }
            .email-next-label { color: #86efac !important; }
            .email-next-text { color: #a7f3d0 !important; }
            .email-button { background: #b91c1c !important; color: #ffffff !important; }
            .email-footer-border { border-top-color: #1e2d44 !important; }
            .email-footer-text { color: #94a3b8 !important; }
            .email-muted { color: #94a3b8 !important; }
        }
    </style>
</head>
<body style="margin:0;padding:0;font-family:Inter,Segoe UI,Arial,sans-serif;color:#0f172a;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" class="email-bg" style="margin:0;padding:32px 0;width:100%;">
        <tr>
            <td align="center">
                <table role="presentation" width="640" cellspacing="0" cellpadding="0" class="email-card" style="width:640px;max-width:640px;border-radius:20px;overflow:hidden;box-shadow:0 18px 60px rgba(15,23,42,0.08);">
                    <tr>
                        <td style="padding:0;" class="email-header">
                            <div style="padding:36px 40px 30px 40px;">
                                <div class="email-header-badge" style="font-size:12px;letter-spacing:0.18em;text-transform:uppercase;font-weight:700;">
                                    Event Registration
                                </div>
                                <h1 class="email-header-title" style="margin:14px 0 0 0;font-size:32px;line-height:1.08;font-weight:700;">
                                    {{ $isWaitlistPromotion ? 'Your seat is confirmed.' : 'You are officially registered.' }}
                                </h1>
                                <p class="email-header-text" style="margin:14px 0 0 0;font-size:16px;line-height:1.7;">
                                    {{ $isWaitlistPromotion
                                        ? "A place opened up and your registration for {$event->title} has been moved from the waitlist into confirmed attendance."
                                        : "Your registration for {$event->title} has been confirmed. Everything you need is now in your event workspace." }}
                                </p>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:32px 40px 8px 40px;">
                            <p class="email-body-text" style="margin:0 0 18px 0;font-size:16px;line-height:1.7;">
                                Hello {{ $recipientName }},
                            </p>

                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" class="email-detail-card" style="width:100%;border-radius:16px;">
                                <tr>
                                    <td style="padding:24px 24px 10px 24px;">
                                        <div class="email-detail-label" style="font-size:12px;letter-spacing:0.16em;text-transform:uppercase;font-weight:700;">
                                            Event Details
                                        </div>
                                        <h2 class="email-heading" style="margin:10px 0 0 0;font-size:24px;line-height:1.2;font-weight:700;">
                                            {{ $event->title }}
                                        </h2>
                                        @if($event->theme)
                                            <p class="email-body-text" style="margin:10px 0 0 0;font-size:15px;line-height:1.7;">
                                                {{ $event->theme }}
                                            </p>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:0 24px 24px 24px;">
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="width:100%;border-collapse:collapse;">
                                            <tr>
                                                <td class="email-detail-border email-detail-label" style="padding:14px 0;width:34%;font-size:13px;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;">Date</td>
                                                <td class="email-detail-border email-detail-value" style="padding:14px 0;font-size:15px;">{{ $dateRange }}</td>
                                            </tr>
                                            <tr>
                                                <td class="email-detail-border email-detail-label" style="padding:14px 0;width:34%;font-size:13px;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;">Time</td>
                                                <td class="email-detail-border email-detail-value" style="padding:14px 0;font-size:15px;">{{ $timeRange }}</td>
                                            </tr>
                                            <tr>
                                                <td class="email-detail-border email-detail-label" style="padding:14px 0;width:34%;font-size:13px;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;">Format</td>
                                                <td class="email-detail-border email-detail-value" style="padding:14px 0;font-size:15px;">{{ ucfirst($event->mode ?? 'Hybrid') }}</td>
                                            </tr>
                                            <tr>
                                                <td class="email-detail-border email-detail-label" style="padding:14px 0;width:34%;font-size:13px;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;">Location</td>
                                                <td class="email-detail-border email-detail-value" style="padding:14px 0;font-size:15px;">{{ $locationDisplay }}</td>
                                            </tr>
                                            <tr>
                                                <td class="email-detail-border email-detail-label" style="padding:14px 0;width:34%;font-size:13px;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;">Entry Fee</td>
                                                <td class="email-detail-border email-detail-value" style="padding:14px 0;font-size:15px;">{{ $entryFeeDisplay }}</td>
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
                                    <td style="padding:0;" class="email-next-panel" style="border-radius:16px;padding:22px 24px;">
                                        <div class="email-next-label" style="font-size:12px;letter-spacing:0.16em;text-transform:uppercase;font-weight:700;">
                                            What Happens Next
                                        </div>
                                        <ul class="email-next-text" style="margin:14px 0 0 0;padding-left:20px;">
                                            @foreach($nextSteps as $nextStep)
                                                <li style="margin:0 0 10px 0;font-size:15px;line-height:1.7;">{{ $nextStep }}</li>
                                            @endforeach
                                            @foreach($modeTips as $tip)
                                                <li style="margin:0 0 10px 0;font-size:15px;line-height:1.7;">{{ $tip }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:24px 40px 10px 40px;" align="center">
                            <a href="{{ $workspaceUrl }}" class="email-button" style="display:inline-block;text-decoration:none;font-size:15px;font-weight:700;padding:15px 28px;border-radius:10px;">
                                Open Event Workspace
                            </a>
                            <p class="email-muted" style="margin:14px 0 0 0;font-size:13px;line-height:1.6;">
                                A calendar invite is attached to this email for quick saving.
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:14px 40px 36px 40px;">
                            <div class="email-footer-border" style="padding-top:18px;">
                                <p class="email-footer-text" style="margin:0;font-size:14px;line-height:1.7;">
                                    Need help or need to make a change? Visit your event workspace or reply to this email{{ $contactEmail ? ' using '.$contactEmail : '' }}.
                                </p>
                                <p class="email-footer-text" style="margin:16px 0 0 0;font-size:14px;line-height:1.7;">
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
