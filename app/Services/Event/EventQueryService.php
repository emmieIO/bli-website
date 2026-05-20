<?php

namespace App\Services\Event;

use App\Enums\EventStatus;
use App\Models\Event;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class EventQueryService
{
    public function getPublishedEvents(?string $q = null)
    {
        return Event::publiclyVisible()
            ->when($q, function ($query, $searchQuery) {
                $like = '%'.$searchQuery.'%';

                $query->where(function ($nestedQuery) use ($like) {
                    $nestedQuery->where('title', 'like', $like)
                        ->orWhere('mode', 'like', $like)
                        ->orWhere('theme', 'like', $like)
                        ->orWhere('physical_address', 'like', $like);
                });
            })
            ->orderBy('created_at', 'asc')
            ->paginate()
            ->withQueryString();
    }

    public function fetchFeaturedEvents()
    {
        return Event::query()
            ->where('is_featured', true)
            ->publiclyVisible()
            ->where('start_date', '>', Carbon::now())
            ->orderByDesc('created_at')
            ->take(3)
            ->get();
    }

    public function getEventsCreatedByUser(?string $filter = null, bool $includePaymentMetrics = true)
    {
        $user = Auth::user();

        if (! $user) {
            return collect([]);
        }

        $query = $user->hasRole('admin') || $user->hasRole('super-admin')
            ? Event::query()
            : $user->eventsCreated();

        if ($filter === 'past') {
            $query->where('end_date', '<', now());
        } elseif ($filter === 'ongoing') {
            $query->where('start_date', '<=', now())
                ->where('end_date', '>=', now());
        } elseif ($filter === 'future') {
            $query->where('start_date', '>', now());
        } elseif ($filter === 'draft') {
            $query->where('status', EventStatus::DRAFT->value);
        } elseif (in_array($filter, EventStatus::values(), true)) {
            $query->where('status', $filter);
        }

        $withCount = [
            'speakers',
            'attendees',
            'speakerApplications',
        ];

        if ($includePaymentMetrics) {
            $withCount['transactions as successful_transactions_count'] = fn ($transactionQuery) => $transactionQuery->where('status', 'successful');
        }

        return $query
            ->withCount($withCount)
            ->orderBy('start_date', 'desc')
            ->paginate()
            ->withQueryString();
    }
}
