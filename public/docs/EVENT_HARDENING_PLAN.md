# Event Hardening Plan

## Purpose

This document is the working source of truth for hardening the event system.

It has three jobs:

1. define the target event experience clearly
2. guide implementation in safe phases
3. track progress so the work does not drift

The goal is not just to add more event features. The goal is to make the entire event flow deterministic, role-aware, and easy to reason about.

---

## BCCI Discipleship Readiness

The BCCI I.D.E.A.L. program behaves like a structured cohort, not a simple one-time event.

To accommodate it well, the platform must support:

- selective admission, where payment does not guarantee acceptance
- a 6-week intensive cycle with one central teaching and one small-group meeting each week
- explicit participant expectations for prayer, Word study, evangelism, and multiplication
- cluster-based accountability rather than passive attendance only
- post-registration workspaces that reinforce commitments, not just logistics
- a launch pattern that supports Week 0 preparation, Week 1 orientation, and ongoing weekly reporting

Short-term implementation approach:

- use `events.metadata` to configure discipleship-specific structure safely
- treat the program as an event with a structured cohort profile
- surface screening, duration, cadence, and weekly targets in admin, public, and attendee views

Future domain work still needed for full fidelity:

- applicant screening workflow distinct from speaker applications
- small-group leader assignment and cluster rosters
- weekly participant report submission
- admin review dashboards for accountability and graduation readiness
- disciple-of-disciple multiplication tracking

---

## North Star

We want one event system with four clean journeys:

1. Public journey
2. Attendee journey
3. Speaker journey
4. Admin journey

At the end of this hardening work, every user should see one obvious next action based on:

- event lifecycle state
- registration state
- speaker state
- payment state
- role and permissions

No page should expose conflicting calls to action, and no important transition should depend on scattered controller logic or UI assumptions.

---

## Success Criteria

The flow is considered hardened when all of the following are true:

- event lifecycle has one canonical source of truth
- attendee registration has one canonical source of truth
- speaker progression has one canonical source of truth
- permissions map cleanly to responsibilities
- public event pages show exactly one primary CTA
- paid and free registration flows are predictable\
- full events transition to waitlist deliberately
- attendee workspace becomes the post-registration home
- speaker workspace becomes the post-application home
- admin event workspace supports operations instead of isolated forms
- important transitions are validated centrally
- reminders and notifications follow event state correctly
- feature tests cover critical role and state transitions

---

## Current Reality Summary

What already exists:

- event status enum and `status` column
- attendee waitlist support
- attendee `My Events` dashboard and single workspace
- speaker workspace separated from attendee workspace
- admin event workspace with core tabs
- payment flow for paid events
- confirmation and reminder notifications

What is still uneven:

- lifecycle states are not fully aligned with the plan
- legacy booleans still influence behavior
- permission split is incomplete
- registration terminology is not fully normalized
- speaker stage names are not fully normalized
- admin operations are missing agenda, reminders UI, audit log, analytics, and active waitlist management
- tests do not yet protect the new journey strongly enough

---

## Canonical Domain Model

### 1. Event Lifecycle

This should be the single event lifecycle model:

1. `draft`
2. `review`
3. `published`
4. `registration_open`
5. `registration_closed`
6. `live`
7. `completed`
8. `cancelled`
9. `archived`

Rules:

- `status` is the primary source of truth
- legacy flags like `is_active` and `is_published` may exist temporarily, but must be derived from `status`
- no page should infer lifecycle from dates alone when a real lifecycle status exists

Recommended compatibility mapping:

| Status | Publicly visible | Registration allowed | Active |
| --- | --- | --- | --- |
| `draft` | no | no | yes |
| `review` | no | no | yes |
| `published` | yes | no | yes |
| `registration_open` | yes | yes | yes |
| `registration_closed` | yes | no | yes |
| `live` | yes | no | yes |
| `completed` | yes | no | yes |
| `cancelled` | no | no | no |
| `archived` | no | no | no |

### 2. Attendee Registration State

This should be the canonical attendee model:

1. `pending`
2. `confirmed`
3. `waitlisted`
4. `cancelled`
5. `refunded`
6. `attended`
7. `no_show`

Notes:

- `pending` is important for future-safe payment and reservation handling
- `confirmed` is clearer than `registered` for post-payment state
- `refunded` should be explicit instead of inferring from transactions

Short-term compatibility note:

- current code uses `registered`
- we should decide whether to rename to `confirmed` now or defer with a compatibility layer

### 3. Speaker Journey State

This should be the canonical speaker model:

1. `invited`
2. `applied`
3. `under_review`
4. `approved`
5. `rejected`
6. `withdrawn`

Notes:

- `invitation_accepted` and `declined` are implementation details unless we decide they should become first-class domain states
- if invitation handling remains separate, we should model invitation response independently from speaker application stage

### 4. Payment State

Payment state should remain transaction-driven and separate from registration state.

Transaction state:

1. `pending`
2. `successful`
3. `failed`
4. `refunded`

Rules:

- a paid event attendee should not become `confirmed` until payment is verified
- refunds should update both transaction and attendee registration state

---

## Journey Matrix

### Guest

- Can browse event list
- Can view public event details
- Can see event status and pricing
- Cannot access attendee workspace
- Cannot access speaker workspace
- Primary CTA should be one of:
  - `Login to register`
  - `Login to buy ticket`
  - `Apply to speak`
  - `Registration closed`

### Authenticated User

- Can browse public events
- Can register for free events if eligible
- Can start checkout for paid events if eligible
- Can join waitlist if event is full and waitlist is allowed
- Can apply to speak if applications are open
- Should never be dumped back into the public event page after successful registration as the main home

### Attendee

- Home becomes `My Events`
- Can open single attendee workspace
- Can see registration status
- Can see payment status
- Can download calendar entry
- Can see access instructions
- Can see meeting link only when allowed
- Can see attendee resources
- Can cancel registration if policy allows

### Waitlisted Attendee

- Appears in `My Events`
- Can open the same workspace
- Sees waitlist-specific messaging
- Does not see attendee-only materials that require confirmation
- Can be promoted to confirmed by explicit system/admin action

### Speaker Applicant

- Home becomes speaker workspace
- Can see invitation status
- Can see application status
- Can see topic and submission details
- Can see organizer feedback
- Cannot see attendee-only actions unless also registered as attendee

### Approved Speaker

- Can access speaker workspace
- Can see confirmed session logistics
- Can see organizer notes
- Can upload assets once enabled
- Can access speaker-specific resources and instructions

### Event Manager

- Can manage event lifecycle
- Can manage speakers
- Can manage attendees
- Can manage waitlist
- Can manage resources
- Can send updates and reminders
- Can inspect payments for owned/managable events

### Admin

- Can do everything event managers do
- Can cancel, archive, delete, and audit across all events

---

## Public CTA Rules

The public event page must always compute one primary CTA.

Priority order:

1. `Cancelled`
2. `Archived`
3. `Completed`
4. `Live`
5. `Registration closed`
6. `Join waitlist`
7. `Buy ticket`
8. `Register now`
9. `Apply to speak`
10. `View attendee workspace`
11. `View speaker workspace`

Decision rules:

- if user is already an attendee, workspace CTA wins over registration CTA
- if user is already a speaker applicant or approved speaker, speaker workspace CTA wins over speak-application CTA
- if registration is closed, do not show buy/register/join actions
- if event is full and waitlist is enabled, show `Join waitlist`
- if event is paid and capacity remains, show `Buy ticket`
- if event is free and capacity remains, show `Register now`
- if registration is not open but speaking applications are open, `Apply to speak` may be the primary CTA only when attendee registration is not available

---

## Transition Rules

### Event Lifecycle Transitions

Allowed:

- `draft -> review`
- `review -> draft`
- `review -> published`
- `published -> registration_open`
- `registration_open -> registration_closed`
- `registration_closed -> live`
- `live -> completed`
- `published -> cancelled`
- `registration_open -> cancelled`
- `registration_closed -> cancelled`
- `live -> cancelled`
- `completed -> archived`
- `cancelled -> archived`

Blocked:

- skipping directly from `draft` to `live`
- reopening archived events
- allowing registration in `registration_closed`, `live`, `completed`, `cancelled`, or `archived`

### Attendee Registration Transitions

Allowed:

- `pending -> confirmed`
- `pending -> cancelled`
- `pending -> refunded`
- `confirmed -> cancelled`
- `confirmed -> attended`
- `confirmed -> no_show`
- `confirmed -> refunded`
- `waitlisted -> confirmed`
- `waitlisted -> cancelled`

Blocked:

- `cancelled -> attended`
- `refunded -> attended`
- `no_show -> confirmed` without explicit admin intervention rules

### Speaker Transitions

Allowed:

- `invited -> applied`
- `applied -> under_review`
- `under_review -> approved`
- `under_review -> rejected`
- `applied -> withdrawn`
- `under_review -> withdrawn`

Blocked:

- `rejected -> approved` without reopening review explicitly
- `withdrawn -> approved`

Implementation rule:

- all transition logic should live in one place, not be spread across controllers and UI checks

---

## Permission Model

### Public and User

- `event.view_public`
- `event.view_owned_registration`
- `event.register`
- `event.join_waitlist`

### Speaker

- `event.apply_to_speak`
- `speaker_application.view_own`
- `speaker_application.update_own_draft`
- `speaker_invitation.respond`
- `speaker_session.view_own`
- `speaker_session.upload_resources`

### Event Manager

- `event.view_any`
- `event.create`
- `event.update`
- `event.publish`
- `event.manage_agenda`
- `event.manage_resources`
- `event.manage_attendees`
- `event.manage_waitlist`
- `event.manage_speakers`
- `event.send_updates`
- `event.view_payments`

### Admin

- all event manager permissions
- `event.delete`
- `event.cancel`
- `event.archive`
- `event.manage_permissions`
- `transaction.view_any`

Implementation note:

- we may keep current permission slugs for compatibility if needed, but we should decide which set is canonical and then adapt policy checks consistently

---

## Admin Workspace Target

The admin event area should grow into these sections:

1. Overview
2. Agenda
3. Speakers
4. Registrations
5. Waitlist
6. Tickets and payments
7. Resources
8. Messaging and reminders
9. Audit log
10. Analytics

Definition of done for admin workspace:

- each section is reachable from one event home
- data visibility follows permissions
- key actions are not scattered across unrelated pages

---

## Rollout Strategy

### Phase 1: Lock the Spec

Goal:

- agree on vocabulary and rules before changing behavior

Deliverables:

- finalize canonical lifecycle
- finalize attendee registration states
- finalize speaker states
- finalize CTA priority rules
- finalize permission map

### Phase 2: Domain Hardening

Goal:

- make backend state rules trustworthy

Deliverables:

- status and transition enums or domain services
- compatibility mapping for legacy flags
- normalized registration state handling
- normalized speaker stage handling
- policy cleanup

### Phase 3: Flow Hardening

Goal:

- make user journeys deterministic

Deliverables:

- public event page CTA resolver
- free event registration path
- paid event payment-to-confirmation path
- waitlist promotion path
- attendee workspace rules
- speaker workspace rules

### Phase 4: Admin Operations Hardening

Goal:

- make event management operationally complete

Deliverables:

- agenda management
- waitlist management
- payments visibility and filtering
- reminders and messaging UI
- audit events
- analytics summary

### Phase 5: Reliability and Safety

Goal:

- protect behavior against regressions

Deliverables:

- feature tests for major journeys
- transition tests
- permission tests
- notification tests
- logging and audit coverage

---

## Suggested Implementation Order

To reduce risk, implementation should proceed in this order:

1. create the workflow matrix in code comments/docs
2. normalize enums and transition rules
3. centralize policy and permission checks
4. centralize CTA selection for event detail pages
5. normalize attendee registration flow
6. normalize speaker flow
7. expand admin workspace
8. backfill tests around every critical transition

Why this order:

- it prevents UI work from hard-coding unstable domain rules
- it lets us fix logic once and reuse it everywhere
- it makes progress measurable

---

## Phase 2 Scope

The next implementation phase should focus on domain hardening only.

This means:

- no broad UI redesign yet
- no analytics work yet
- no admin information architecture expansion yet

The purpose of Phase 2 is to make the state model stable enough that later UI work does not encode the wrong rules.

### Phase 2 Deliverables

- event lifecycle source of truth finalized
- legacy boolean behavior documented and derived from lifecycle
- attendee registration transition rules centralized
- speaker state model normalized for v1
- CTA resolution rules written down in code-ready form
- policy and permission gaps listed for implementation
- high-risk terminology mismatches documented

### Phase 2 Non-Goals

- full admin workspace rebuild
- speaker asset upload implementation
- analytics dashboards
- broad notification redesign

---

## Immediate Decisions For V1

These decisions are intentionally pragmatic. They are meant to let us harden the flow safely against the current codebase before we attempt deeper structural cleanup.

### Decision 1: Keep `registered` as the stored attendee state in v1

Decision:

- `registered` remains the stored database value for now
- `confirmed` remains the product-language label in UI and documentation where helpful

Why:

- the current codebase uses `registered` widely across actions, services, views, reminders, and tests
- a hard rename right now would expand the change surface unnecessarily
- we can still centralize behavior first, then rename later if it is still worth doing

Implementation consequence:

- add a compatibility note in code where attendee state is normalized
- use product copy like "confirmed" in UI where that reads better, while mapping internally to `registered`

### Decision 2: Add `review` to the canonical lifecycle, but defer enabling it in the UI until admin review tooling exists

Decision:

- `review` stays in the target lifecycle model
- the app may continue operating without active UI entry into `review` until admin review mechanics are ready

Why:

- removing it now would weaken the long-term lifecycle model
- forcing it into active use immediately would create partial workflow complexity without supporting tools

Implementation consequence:

- transition logic should reserve the state
- admin create/edit flows do not need to expose it immediately if that would create dead-end behavior

### Decision 3: Keep invitation response separate from speaker application stage

Decision:

- invitation response remains its own concern
- speaker application stage remains the canonical speaker progression model

Why:

- current code already distinguishes invitation status from application status
- combining them now would blur two different processes
- the real normalization problem is presentation and transition handling, not that both concepts exist

Implementation consequence:

- canonical speaker progression for v1 becomes:
  - `invited`
  - `applied`
  - `under_review`
  - `approved`
  - `rejected`
  - `withdrawn`
- invitation response states like accepted or declined should map into that journey deliberately rather than become competing top-level stage models

### Decision 4: Waitlist should be enabled by default for full events in v1

Decision:

- if an event reaches capacity and registration is otherwise open, the system should prefer waitlisting instead of hard failure

Why:

- the current system already supports waitlist behavior
- this aligns with the hardening goal of preventing silent or ambiguous failure
- per-event configurability can be added later if needed

Implementation consequence:

- CTA logic should deliberately switch to waitlist when capacity is exhausted
- admin tooling later needs promotion flow from waitlist to registered

### Decision 5: `location` and `meeting_link` need separate responsibilities

Decision:

- `location` remains the general event venue or public-facing location field
- `meeting_link` becomes the attendee-access field for online access
- `access_notes` remains a separate instructional field

Why:

- the current codebase uses `location` both as venue text and as a join URL
- that ambiguity leaks into public pages, attendee workspace, notifications, and admin pages
- the attendee workspace already points toward a cleaner model by reading `meeting_link` and `access_notes` from metadata

Implementation consequence:

- in v1, continue reading `meeting_link` and `access_notes` from `metadata`
- treat `location` as public event location information, not the attendee-only join URL
- when the time comes for schema refinement, `meeting_link` should become a dedicated event field

---

## Progress Tracker

### Foundation

- [x] Canonical event lifecycle agreed
- [x] Canonical attendee registration states agreed
- [x] Canonical speaker stages agreed
- [x] Canonical permission model agreed
- [x] CTA priority rules agreed

### Domain

- [x] Event lifecycle rules centralized
- [x] Legacy boolean compatibility documented
- [x] Registration transitions centralized
- [x] Speaker transitions centralized
- [x] Refund state integrated into registration flow
- [x] Policies aligned with canonical permissions

### Public Journey

- [x] Public list segmentation is correct
- [x] Public event detail page shows one primary CTA
- [x] Free registration flow is deterministic
- [x] Paid checkout flow is deterministic
- [x] Full events route into waitlist deliberately
- [ ] Closed registration states are handled clearly

### Attendee Journey

- [ ] Confirmation step defined
- [x] `My Events` is the attendee home
- [ ] Single attendee workspace is complete
- [ ] Calendar download behavior is correct
- [ ] Access instructions visibility is correct
- [ ] Meeting link visibility is correct
- [ ] Cancel registration rules are correct

### Speaker Journey

- [ ] Invitation flow is normalized
- [ ] Application flow is normalized
- [x] Speaker workspace reflects canonical stages
- [ ] Organizer feedback flow is complete
- [ ] Speaker assets flow is designed
- [ ] Session slot visibility rules are defined

### Admin Journey

- [ ] Overview section hardened
- [ ] Agenda section implemented
- [ ] Speakers section hardened
- [ ] Registrations section hardened
- [ ] Waitlist section implemented
- [ ] Payments section hardened
- [ ] Resources section hardened
- [ ] Messaging and reminders section implemented
- [ ] Audit log section implemented
- [ ] Analytics section implemented

### Reliability

- [ ] Registration notifications verified
- [x] Reminder notifications verified
- [x] Audit logging for critical transitions implemented
- [ ] Event lifecycle feature tests added
- [x] Registration feature tests added
- [x] Speaker journey feature tests added
- [x] Permission feature tests added
- [x] Payment-to-registration feature tests updated

### Latest Checkpoint

- [x] 2026-05-01: Permission hardening moved forward.
- [x] Added missing canonical event permissions for waitlist management, payments visibility, sending updates, and archiving.
- [x] Wired policy abilities for event admin operations, resources, speaker invites, and waitlist promotion.
- [x] Split admin event route guards so create, update, delete, speakers, resources, and waitlist actions are no longer all implicitly tied to `event-view-any`.
- [x] Gated admin event workspace actions and payment visibility behind explicit event capabilities.
- [x] Stopped loading event payment data for admins without the event payments ability.
- [x] Added explicit authorization and validation around speaker assignment flows.
- [x] Fixed the PHPUnit testing harness to use in-memory SQLite with an app key, so feature coverage can run reliably again.
- [x] Added permission-focused feature coverage for waitlist promotion, speaker assignment, and event payment visibility.
- [x] Introduced a confirmed-language compatibility layer while keeping the stored v1 attendee state as `registered`.
- [x] Switched event confirmation notifications and user-facing event registration copy to canonical confirmed wording.
- [x] Hardened paid event verification so successful payment respects real-time event capacity instead of silently overbooking.
- [x] Added feature coverage for the pending transaction -> confirmed attendee boundary and the paid confirmation -> waitlist fallback path.
- [x] Verified reminder dispatch rules so only confirmed attendees are notified and duplicate cron sends are suppressed by cache locks.
- [x] Added a lightweight `event_transition_audits` trail for confirmation, cancellation, and waitlist promotion transitions through the event registration workflow.
- [x] Added feature coverage for registration confirmation, RSVP cancellation, and waitlist promotion audit writes.
- [x] Extended end-to-end registration journey coverage for free confirmation, full-event waitlisting, and cancelled-attendee re-entry rules.
- [x] Added speaker journey feature coverage for application submission, invitation acceptance, admin approval, rejection, and reopening.
- [x] Centralized speaker state changes and workspace-stage resolution behind a single speaker transition service so invite acceptance, application approval, rejection, and reopening now share one backend path.
- [x] Integrated `refunded` as a first-class attendee state through an internal refund-request workflow: attendees submit pending requests, admins review them, and approval finalizes both transaction refund status and attendee refund state, with webhook sync completing approved requests when remittance happens through Paystack.
- [x] Refined the refund request UI so attendees can provide context from the event workspace and admins can review, annotate, approve, or decline requests from the event operations view.
- [x] Moved refund handling into a dedicated attendee refund page so the event workspace can stay focused while refund requests get a clearer form, status timeline, and next-step guidance.
- [x] Cleaned up the admin event payments tab so refund reviews and transaction history are easier to operate: latest-first ordering, clearer payment summary metrics, and a more readable ledger with refs, paid dates, and refund context.
- [x] Reframed the attendee event workspace into a more restrained, premium briefing layout with quieter hierarchy, fewer dashboard-style cards, and clearer action grouping for access, resources, and refund state.
- [x] Converted the attendee events home into a clean table-based register so prominent users can scan commitments, status, schedule, payment context, and workspace actions with less visual noise.
- [x] Tightened the refund outcome so refunded attendees are no longer treated as active event participants: they lose attendee workspace access and drop out of the `My Events` registry while refund records remain in payment/admin flows.
- [x] Segmented the public event list around actual lifecycle and capacity states, so live events, open registration, waitlist-only events, announced events, closed registration, and completed events no longer appear in one undifferentiated grid.
- [x] Clarified the free public registration flow so guests see an explicit login-to-register path, authenticated users get a true free-registration CTA, and the public event modal stops borrowing paid checkout language.
- [x] Audited the paid event checkout path against course-era assumptions and centralized event-specific eligibility checks, so checkout and payment initialization now reject already-confirmed, waitlisted, refunded, full-capacity, closed, and duplicate-pending-payment states consistently.
- [x] Replaced the old `EventService` facade with focused event services for queries, CRUD, participant state, registration flow, and speaker invitations so the event domain now has clearer boundaries.
- [ ] Next: move to the next unchecked item in `Public Journey`, `Closed registration states are handled clearly`.
- [ ] Cross-domain consistency pass: apply the same boundary-cleanup pattern used in events to the remaining course, speaker, payment, and identity domains so service usage, naming, and controller dependencies stay consistent across the platform.

---

## Working Decisions Log

Use this section to record decisions so we do not re-open settled questions later.

### Pending Decisions

- [ ] Canonical permission slug strategy: keep current slugs with compatibility mapping, or rename to dot-notation later?
- [ ] Should event managers be introduced as a distinct role now, or via admin permissions first?
- [ ] Should attendee cancellation limits remain as-is, be simplified, or become policy-driven?
- [ ] What is the exact promotion path from waitlisted to registered in v1?

### Confirmed Decisions

- [x] Keep attendee `registered` as the stored v1 state and use "confirmed" as product-language where helpful
- [x] Keep attendee `pending` as a deferred domain state in v1, while `refunded` is now modeled explicitly for event registrations
- [x] Keep `review` in the canonical lifecycle, but defer active UI usage until supporting admin review flow exists
- [x] Keep invitation response separate from speaker application stage
- [x] Enable waitlist by default for full events in v1
- [x] Separate public `location` from attendee `meeting_link`, with `meeting_link` continuing to live in `metadata` in v1

---

## Next Build Sequence

This is the recommended execution order after this document.

1. [x] Centralize event lifecycle interpretation in one backend layer.
2. [x] Centralize attendee registration interpretation in one backend layer.
3. [x] Normalize speaker workspace stage resolution against the confirmed v1 model.
4. [x] Create a single CTA resolver for public event pages.
5. [x] Update tests around payment, registration, waitlist, and workspace redirects.
6. [ ] Run the next domain-consistency cleanup pass across courses, speakers, payments, and identity boundaries.

This gives us the safest route to implementation because it hardens logic before expanding UI surface area.

---

## Operating Rule

Before implementing any event-related change, check:

1. which journey does this affect?
2. which canonical state model does it rely on?
3. which transition does it introduce or modify?
4. which permission should guard it?
5. which checklist item should be updated after it lands?

If a change cannot answer those five questions cleanly, it is probably introducing more ambiguity and should be reworked before merge.
