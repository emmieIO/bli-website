<?php

namespace Tests\Unit;

use App\Enums\ApplicationStatus;
use App\Enums\SpeakerWorkspaceStage;
use App\Models\SpeakerApplication;
use App\Models\SpeakerInvite;
use PHPUnit\Framework\TestCase;

class SpeakerWorkspaceStageTest extends TestCase
{
    public function test_it_returns_null_without_any_speaker_workspace_access_signal(): void
    {
        $this->assertNull(SpeakerWorkspaceStage::resolve(null, null, false));
    }

    public function test_it_maps_pending_invite_to_invited_stage(): void
    {
        $invite = new SpeakerInvite(['status' => 'pending']);

        $this->assertSame(
            SpeakerWorkspaceStage::INVITED,
            SpeakerWorkspaceStage::resolve(null, $invite, false)
        );
    }

    public function test_it_maps_accepted_invite_without_application_to_approved_stage(): void
    {
        $invite = new SpeakerInvite(['status' => 'accepted']);

        $this->assertSame(
            SpeakerWorkspaceStage::APPROVED,
            SpeakerWorkspaceStage::resolve(null, $invite, false)
        );
    }

    public function test_it_maps_pending_application_to_under_review_stage(): void
    {
        $application = new SpeakerApplication(['status' => ApplicationStatus::PENDING->value]);

        $this->assertSame(
            SpeakerWorkspaceStage::UNDER_REVIEW,
            SpeakerWorkspaceStage::resolve($application, null, false)
        );
    }

    public function test_it_prioritizes_application_rejection_over_invite_acceptance(): void
    {
        $application = new SpeakerApplication(['status' => ApplicationStatus::REJECTED->value]);
        $invite = new SpeakerInvite(['status' => 'accepted']);

        $this->assertSame(
            SpeakerWorkspaceStage::REJECTED,
            SpeakerWorkspaceStage::resolve($application, $invite, false)
        );
    }

    public function test_it_maps_rejected_invite_to_withdrawn_stage(): void
    {
        $invite = new SpeakerInvite(['status' => 'rejected']);

        $this->assertSame(
            SpeakerWorkspaceStage::WITHDRAWN,
            SpeakerWorkspaceStage::resolve(null, $invite, false)
        );
    }

    public function test_it_prioritizes_assignment_as_approved_stage(): void
    {
        $this->assertSame(
            SpeakerWorkspaceStage::APPROVED,
            SpeakerWorkspaceStage::resolve(null, null, true)
        );
    }
}
