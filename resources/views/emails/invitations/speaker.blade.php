@component('mail::message')
# Invitation to speak at {{ $invitation->event->title }}

Hi {{ $invitation->speaker?->name ?? 'Speaker' }},

We’re thrilled to be inviting you to speak at **{{ $invitation->event->title }}** on
{{ sweet_date($invitation->event->start_date) }}.

@component('mail::button', ['url' => $url])
Respond to Invitation
@endcomponent

---

### Event at a glance
- **Mode:** {{ ucfirst($invitation->event->mode) }}
- **Location / Link:** {{ $invitation->event->location ?? 'To be announced' }}

@if ($invitation->suggested_topic)
- **Suggested Topic:** {{ $invitation->suggested_topic }}
@endif

@if ($invitation->suggested_duration)
- **Suggested Duration:** {{ $invitation->suggested_duration }} minutes
@endif

@if ($invitation->expected_format)
- **Expected Format:** {{ $invitation->expected_format }}
@endif

@if ($invitation->audience_expectations)
  
**Audience Expectations:**  
{{ $invitation->audience_expectations }}

@endif

@if ($invitation->special_instructions)

**Special Instructions:**  
{{ $invitation->special_instructions }}

@endif

@if ($invitation->invitation_message)

**Personal Message:**  
{{ $invitation->invitation_message }}

@endif

This invitation will expire on {{ sweet_date($invitation->expires_at) ?? 'no expiration date set' }}.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
