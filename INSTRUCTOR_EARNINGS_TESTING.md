# Testing Instructor Earnings System

This guide shows you how to verify that the instructor earnings and payout system is working correctly.

## Quick Test Command

Run the automated test suite:
```bash
php artisan test:instructor-earnings
```

This tests:
- ✅ Database tables exist
- ✅ Earnings are recorded from transactions
- ✅ Balance calculations work
- ✅ Status updates (lazy evaluation) work
- ✅ Payout validation works
- ✅ Middleware is registered

## Manual Testing with Real Transactions

### Step 1: Check Existing Earnings

View all earnings in the database:
```bash
php artisan tinker --execute="
\App\Models\InstructorEarning::with(['instructor', 'course'])->get()->each(function(\$e) {
    echo 'ID: ' . \$e->id . PHP_EOL;
    echo 'Instructor: ' . \$e->instructor->name . PHP_EOL;
    echo 'Course: ' . \$e->course?->title . PHP_EOL;
    echo 'Amount: ' . \$e->net_amount . ' ' . \$e->currency . PHP_EOL;
    echo 'Status: ' . \$e->status . PHP_EOL;
    echo 'Available At: ' . \$e->available_at . PHP_EOL;
    echo '---' . PHP_EOL;
});
"
```

### Step 2: Simulate a Course Purchase

When a student purchases a course, earnings should be automatically recorded:

```bash
php artisan tinker
```

Then in tinker:
```php
use App\Models\User;
use App\Models\Course;
use App\Models\Transaction;
use App\Services\Instructor\InstructorEarningsService;

// Get an instructor and their course
$instructor = User::role('instructor')->first();
$course = Course::where('instructor_id', $instructor->id)->first();
$student = User::whereDoesntHave('roles')->first();

// Create a successful transaction
$transaction = Transaction::create([
    'user_id' => $student->id,
    'course_id' => $course->id,
    'transaction_id' => 'TEST_' . time(),
    'amount' => $course->price,
    'currency' => 'NGN',
    'status' => 'successful',
    'payment_ref' => 'TEST_REF_' . time(),
    'paid_at' => now(),
]);

// The PaymentService would normally call this automatically
$earningsService = app(\App\Services\Instructor\InstructorEarningsService::class);
$earning = $earningsService->recordEarningFromTransaction($transaction);

// View the result
echo "Earning recorded!" . PHP_EOL;
echo "Gross: {$earning->gross_amount} {$earning->currency}" . PHP_EOL;
echo "Platform Fee: {$earning->platform_fee} ({$earning->platform_fee_percentage}%)" . PHP_EOL;
echo "Net Amount: {$earning->net_amount} {$earning->currency}" . PHP_EOL;
echo "Status: {$earning->status}" . PHP_EOL;
echo "Available at: {$earning->available_at}" . PHP_EOL;
```

### Step 3: Check Instructor Balance

```bash
php artisan tinker
```

```php
use App\Models\User;
use App\Services\Instructor\InstructorEarningsService;

$instructor = User::role('instructor')->first();
$earningsService = app(\App\Services\Instructor\InstructorEarningsService::class);

$balance = $earningsService->getAvailableBalance($instructor);

echo "Available: {$balance['available']} NGN" . PHP_EOL;
echo "Pending: {$balance['pending']} NGN" . PHP_EOL;
echo "Total Earned: {$balance['total_earned']} NGN" . PHP_EOL;
echo "Total Paid: {$balance['total_paid']} NGN" . PHP_EOL;
```

### Step 4: Test Status Update (Lazy Evaluation)

Simulate time passing to make earnings available:

```bash
php artisan tinker
```

```php
use App\Models\InstructorEarning;
use App\Services\Instructor\InstructorEarningsService;

// Get a pending earning
$earning = InstructorEarning::where('status', 'pending')->first();

// Manually set available_at to past (simulate 7 days passing)
$earning->available_at = now()->subDays(8);
$earning->save();

echo "Set earning to be available in the past" . PHP_EOL;

// Now trigger the lazy evaluation
$earningsService = app(\App\Services\Instructor\InstructorEarningsService::class);
$updated = $earningsService->markPendingEarningsAsAvailable($earning->instructor_id);

echo "Updated {$updated} earning(s)" . PHP_EOL;

// Check the status
$earning->refresh();
echo "New status: {$earning->status}" . PHP_EOL;
```

### Step 5: Test Payout Request

```bash
php artisan tinker
```

```php
use App\Models\User;
use App\Services\Instructor\InstructorEarningsService;

$instructor = User::role('instructor')->first();
$earningsService = app(\App\Services\Instructor\InstructorEarningsService::class);

// First, make sure the instructor has available balance
// (Use Step 4 to make earnings available first)

$result = $earningsService->requestPayout($instructor, [
    'method' => 'bank_transfer',
    'bank_name' => 'GTBank',
    'account_number' => '0123456789',
    'account_name' => 'John Doe',
    'bank_code' => '058',
]);

if ($result['success']) {
    echo "Payout requested successfully!" . PHP_EOL;
    echo "Amount: {$result['payout']->amount} {$result['payout']->currency}" . PHP_EOL;
    echo "Reference: {$result['payout']->payout_reference}" . PHP_EOL;
} else {
    echo "Payout failed: {$result['message']}" . PHP_EOL;
}
```

## Testing Middleware (Lazy Evaluation)

The middleware automatically updates earnings status when instructors visit the site. To test:

1. **Login as an instructor** on your website
2. **Navigate to any page** (e.g., dashboard, courses)
3. **Check the logs**:
```bash
tail -f storage/logs/laravel.log | grep "Marked"
```

You should see log entries like:
```
Marked X earnings as available for payout
```

## Testing Real Course Purchases

The best way to test is with a real purchase flow:

1. **Create a test course** with a price (e.g., 10,000 NGN)
2. **Purchase the course** using Flutterwave/Paystack test mode
3. **Check the instructor_earnings table**:
```bash
php artisan tinker --execute="
\App\Models\InstructorEarning::latest()->first()?->tap(function(\$e) {
    echo 'Latest Earning:' . PHP_EOL;
    echo 'Course: ' . \$e->course?->title . PHP_EOL;
    echo 'Gross: ' . \$e->gross_amount . ' ' . \$e->currency . PHP_EOL;
    echo 'Platform Fee: ' . \$e->platform_fee . ' (' . \$e->platform_fee_percentage . '%)' . PHP_EOL;
    echo 'Net: ' . \$e->net_amount . ' ' . \$e->currency . PHP_EOL;
    echo 'Status: ' . \$e->status . PHP_EOL;
});
"
```

## Verification Checklist

- [ ] Earnings are created when transactions succeed
- [ ] Platform commission is calculated correctly (default 20%)
- [ ] Earnings start with 'pending' status
- [ ] Available_at is set to 7 days in the future
- [ ] Balance calculations are accurate
- [ ] Middleware updates status automatically
- [ ] Payout requests validate minimum amount
- [ ] Payout requests create records with bank details
- [ ] Foreign key relationships work correctly

## Configuration

Check your configuration:
```bash
php artisan tinker --execute="
echo 'Platform Commission: ' . config('services.instructor_payouts.platform_commission') . '%' . PHP_EOL;
echo 'Holding Period: ' . config('services.instructor_payouts.holding_period_days') . ' days' . PHP_EOL;
echo 'Minimum Payout: ' . config('services.instructor_payouts.minimum_payout') . ' NGN' . PHP_EOL;
"
```

You can customize these in your `.env` file:
```env
PLATFORM_COMMISSION_PERCENTAGE=20.0
PAYOUT_HOLDING_PERIOD_DAYS=7
MINIMUM_PAYOUT_AMOUNT=5000
DEFAULT_PAYOUT_METHOD=bank_transfer
```

## Troubleshooting

### Earnings not being recorded
- Check that `App\Services\Payment\PaymentService` is calling `recordEarningFromTransaction()`
- Verify the transaction status is 'successful'
- Check the course has an instructor_id set

### Status not updating
- Verify middleware is registered in `bootstrap/app.php`
- Check that `available_at` is in the past
- Review logs in `storage/logs/laravel.log`

### Payout requests failing
- Ensure available balance meets minimum (default 5000 NGN)
- Check that earnings status is 'available' not 'pending'
- Verify required bank details are provided
