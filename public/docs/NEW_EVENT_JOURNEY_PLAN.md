# New Event Journey Plan

## Goal

Rebuild the event experience into a cleaner enterprise flow with:

- clear lifecycle states
- role-based permissions
- distinct attendee and speaker journeys
- better admin operations
- fewer ambiguous event actions

## Core User Types

1. Guest
2. Authenticated user
3. Attendee
4. Speaker applicant
5. Approved speaker
6. Event manager
7. Admin

## Event Lifecycle

Use one primary lifecycle instead of mixing `is_active`, `is_published`, and UI assumptions.

Recommended event statuses:

1. `draft`
2. `review`
3. `published`
4. `registration_open`
5. `registration_closed`
6. `live`
7. `completed`
8. `cancelled`
9. `archived`

Short-term compatibility:

- keep current booleans working for now
- introduce `status` in the next schema pass
- backfill booleans from status until old code is removed

## Public Journey

### 1. Discovery

- public event listing
- category or type filters
- upcoming / live / past segmentation
- event details page with agenda, speakers, pricing, attendance mode, capacity, FAQs

### 2. Registration Decision

Each event should expose exactly one primary call to action:

- `Register now`
- `Buy ticket`
- `Join waitlist`
- `Apply to speak`
- `Registration closed`

Do not show competing actions without clear priority.

### 3. Checkout / Registration

#### Free event

- confirm attendance
- create attendee record
- send confirmation

#### Paid event

- attendee details
- payment
- transaction verification
- create confirmed attendee record
- send receipt and confirmation

#### Full event

- create waitlist record instead of failing silently

## Attendee Journey

After registration, the user should not be sent back into the generic event page as the main workspace.

Recommended attendee flow:

1. confirmation page
2. `My Events` dashboard
3. single attendee event workspace

Attendee workspace should contain:

- registration status
- payment status
- calendar download
- event access instructions
- meeting link when eligible
- resources
- reminders / updates
- cancel registration if allowed

## Speaker Journey

Speaker flow should be separate from attendee flow.

### Speaker stages

1. invited
2. applied
3. under_review
4. approved
5. rejected
6. withdrawn

### Speaker workspace

- invitation status
- application status
- event details
- speaking topic
- schedule slot
- organizer notes
- uploaded assets

## Admin / Event Manager Journey

The admin event area should operate like a system, not a collection of isolated forms.

Recommended event manager sections:

1. Overview
2. Agenda
3. Speakers
4. Registrations
5. Tickets and payments
6. Resources
7. Messaging and reminders
8. Audit log
9. Analytics

## Recommended Permission Model

Current permissions are too broad around events. Split them by responsibility.

### Guest

- `event.view_public`

### Authenticated user

- `event.view_public`
- `event.view_owned_registration`
- `event.register`
- `event.join_waitlist`

### Speaker applicant

- `event.apply_to_speak`
- `speaker_application.view_own`
- `speaker_application.update_own_draft`
- `speaker_invitation.respond`

### Approved speaker

- all speaker applicant permissions
- `speaker_session.view_own`
- `speaker_session.upload_resources`

### Event manager

- `event.view_any`
- `event.create`
- `event.update`
- `event.publish`
- `event.manage_agenda`
- `event.manage_resources`
- `event.manage_attendees`
- `event.manage_speakers`
- `event.manage_waitlist`
- `event.send_updates`
- `event.view_payments`

### Admin

- all event manager permissions
- `event.delete`
- `event.cancel`
- `event.archive`
- `event.manage_permissions`
- `transaction.view_any`

## Recommended Data Model Changes For Next Phase

### Event registrations

Replace the current attendee pivot semantics with explicit registration intent.

Recommended statuses:

- `pending`
- `confirmed`
- `waitlisted`
- `cancelled`
- `refunded`
- `attended`
- `no_show`

### Speaker applications

Keep:

- event
- user
- speaker profile
- topic
- format
- review timestamps
- feedback

Add later if needed:

- session duration
- track
- supporting links
- deck upload
- internal reviewer notes

### Transactions

Keep transactions polymorphic so events and courses share payment infrastructure.

## Delivery Order

### Phase 1

- consolidate migrations
- keep app bootable
- clean permission seeds

### Phase 2

- add event status enum and registration status enum
- add waitlist support
- refactor event policy and permission names

### Phase 3

- rebuild admin event management information architecture
- rebuild attendee workspace
- rebuild speaker workspace

### Phase 4

- notifications and reminders
- analytics and reporting
- audit trail improvements

## Immediate Next Implementation Tasks

1. replace broad `manage events` usage with granular event permissions
2. introduce event `status` and registration `status` enums
3. redesign attendee registration flow around `confirmed` vs `waitlisted`
4. separate speaker application routes and UI from attendee actions
5. rebuild admin event screens around tabs: overview, speakers, registrations, payments, resources

