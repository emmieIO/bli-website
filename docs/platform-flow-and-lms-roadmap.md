# Platform Flow And LMS Roadmap

## Current Operating Model

The application should be organized around one predictable journey:

1. Acquire: public events and guest registration bring people into the platform.
2. Engage: event workspaces, invitations, speakers, reminders, and resources keep the experience active.
3. Learn: the LMS will own courses, cohorts, lessons, assessments, progress, and certificates.
4. Deepen: mentorship uses sessions, milestones, and resources for personal formation.
5. Transact: payments, receipts, and transaction audit stay in the commerce layer.
6. Support: tickets handle operational issues without mixing support into learning or events.
7. Govern: roles, permissions, instructors, speakers, content, and settings stay in administration.

## Module Boundaries

- Events should not become the LMS. Events can create demand, enroll a cohort, host live sessions, and expose event resources.
- Mentorship should not become the LMS. Mentorship can see learning progress later, but it should keep its own request/session/milestone workflow.
- Commerce should not care whether payment is for an event or LMS cohort beyond a payable reference.
- Support should remain generic and attachable to user journeys later through metadata.
- People & Access should own roles and permissions for every module.

## LMS Entry Contract

Build LMS as a separate module with these first contracts:

- Permissions: `lms-view-catalog`, `lms-enroll-self`, `lms-manage-courses`, `lms-teach-cohorts`.
- Public/user routes: `learning.catalog`, `learning.my-courses`, `learning.courses.show`.
- Admin routes: `admin.learning.courses.index`, `admin.learning.courses.create`, `admin.learning.cohorts.index`.
- Core models: `Course`, `CourseModule`, `Lesson`, `Cohort`, `Enrollment`, `LessonProgress`, `Assessment`, `Certificate`.

## Recommended First LMS Milestone

Start with a read-only course catalog and admin course shell:

1. Course catalog page with published courses.
2. Admin course CRUD for title, description, cover, status, and outcomes.
3. Enrollment table that can be created manually or later from paid event/cohort checkout.
4. Student "My Learning" page listing enrolled courses.

This avoids re-entangling courses with events while still leaving a clean bridge from events into cohorts.
