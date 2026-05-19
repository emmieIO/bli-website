{{ $subjectLine }}

Hello {{ $recipientName }},

{{ $isWaitlistPromotion
    ? "A place opened up and your registration for {$event->title} has been moved from the waitlist into confirmed attendance."
    : "Your registration for {$event->title} has been confirmed. Everything you need is now in your event workspace." }}

EVENT DETAILS
- Event: {{ $event->title }}
@if($event->theme)- Theme: {{ $event->theme }}
@endif
- Date: {{ $dateRange }}
- Time: {{ $timeRange }}
- Format: {{ ucfirst($event->mode ?? 'Hybrid') }}
- Location: {{ $locationDisplay }}
- Entry Fee: {{ $entryFeeDisplay }}

WHAT HAPPENS NEXT
@foreach($nextSteps as $nextStep)- {{ $nextStep }}
@endforeach
@foreach($modeTips as $tip)- {{ $tip }}
@endforeach

Open your event workspace:
{{ $workspaceUrl }}

A calendar invite is attached to this email for quick saving.

Need help or need to make a change? Visit your event workspace or reply to this email@if($contactEmail) using {{ $contactEmail }}@endif.

Best regards,
{{ $appName }} Team
