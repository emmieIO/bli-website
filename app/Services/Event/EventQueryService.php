<?php

namespace App\Services\Event;

use App\Enums\EventStatus;
use App\Models\Event;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class EventQueryService
{
    public function getPublishedEvents(?string $q = null, ?string $status = null): LengthAwarePaginator
    {
        /** @var Builder<Event> $query */
        $query = Event::query()->publiclyVisible();

        if ($q !== null && $q !== '') {
            $like = '%'.$q.'%';

            $query->where(function (Builder $nestedQuery) use ($like): void {
                $nestedQuery->where('title', 'like', $like, 'and')
                    ->orWhere('mode', 'like', $like)
                    ->orWhere('theme', 'like', $like)
                    ->orWhere('physical_address', 'like', $like);
            });
        }

        if ($status !== null && $status !== '') {
            $query->where('status', '=', $status, 'and');
        }

        return $query
            ->orderBy('created_at', 'asc')
            ->paginate()
            ->withQueryString();
    }

    /** @return EloquentCollection<int, Event> */
    public function fetchFeaturedEvents(): EloquentCollection
    {
        return Event::query()
            ->where('is_featured', '=', true, 'and')
            ->publiclyVisible()
            ->where('start_date', '>', Carbon::now(), 'and')
            ->orderByDesc('created_at')
            ->take(3)
            ->get(['*']);
    }

    public function getEventsCreatedByUser(?string $filter = null, bool $includePaymentMetrics = true): LengthAwarePaginator|Collection
    {
        $user = Auth::user();

        if (! $user) {
            return collect([]);
        }

        /** @var Builder<Event> $query */
        $query = $user->hasRole('admin') || $user->hasRole('super-admin')
            ? Event::query()
            : $user->eventsCreated()->getQuery();

        if ($filter === 'past') {
            $query->where('end_date', '<', now(), 'and');
        } elseif ($filter === 'ongoing') {
            $query->where('start_date', '<=', now(), 'and')
                ->where('end_date', '>=', now(), 'and');
        } elseif ($filter === 'future') {
            $query->where('start_date', '>', now(), 'and');
        } elseif ($filter === 'draft') {
            $query->where('status', '=', EventStatus::DRAFT->value, 'and');
        } elseif (in_array($filter, EventStatus::values(), true)) {
            $query->where('status', '=', $filter, 'and');
        }

        $withCount = [
            'speakers',
            'attendees',
            'guestAttendees',
            'speakerApplications',
        ];

        if ($includePaymentMetrics) {
            $withCount['transactions as successful_transactions_count'] = fn (Builder $transactionQuery): Builder => $transactionQuery->where('status', '=', 'successful', 'and');
        }

        return $query
            ->withCount($withCount)
            ->orderBy('start_date', 'desc')
            ->paginate()
            ->withQueryString();
    }
}
