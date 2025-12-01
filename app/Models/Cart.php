<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
    ];

    protected $appends = [
        'total',
        'item_count',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Get the total price of all items in the cart
     */
    public function getTotalAttribute(): float
    {
        return $this->items->sum('price');
    }

    /**
     * Get the count of items in the cart
     */
    public function getItemCountAttribute(): int
    {
        return $this->items->count();
    }

    /**
     * Add a course to the cart
     */
    public function addCourse(Course $course): CartItem
    {
        return $this->items()->updateOrCreate(
            ['course_id' => $course->id],
            ['price' => $course->price ?? 0]
        );
    }

    /**
     * Remove a course from the cart
     */
    public function removeCourse(Course $course): bool
    {
        return $this->items()->where('course_id', $course->id)->delete() > 0;
    }

    /**
     * Check if a course is in the cart
     */
    public function hasCourse(Course $course): bool
    {
        return $this->items()->where('course_id', $course->id)->exists();
    }

    /**
     * Clear all items from the cart
     */
    public function clear(): void
    {
        $this->items()->delete();
    }
}
