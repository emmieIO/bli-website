# Cart Earnings Distribution - How It Works

## Overview

The cart earnings system allows instructors to receive their share of earnings when a student purchases multiple courses in a single cart transaction, even if the courses belong to different instructors.

## The Problem

When a student adds multiple courses to their cart:
- **3 courses from 2 different instructors**
- **1 transaction** for the entire cart
- **Multiple instructors** need to be paid

**Example:**
```
Cart Contents:
├─ Course A (₦10,000) - Instructor 1
├─ Course B (₦15,000) - Instructor 2
└─ Course C (₦20,000) - Instructor 1

Total: ₦45,000 (one transaction)
```

**Challenge:** How do we split the earnings fairly between instructors?

## The Solution

### Step 1: Store Cart Items in Transaction Metadata

When a cart purchase is initiated, we store detailed item information in the transaction's metadata:

```php
$transaction->metadata = [
    'type' => 'cart',
    'cart_id' => 123,
    'course_ids' => [1, 2, 3],
    'items' => [
        ['course_id' => 1, 'price' => 10000],
        ['course_id' => 2, 'price' => 15000],
        ['course_id' => 3, 'price' => 20000],
    ]
];
```

**Why?** This preserves the price at the time of purchase (important if course prices change later).

### Step 2: Create Separate Earnings Records

When the payment is successful, the system creates **individual earnings records** for each course:

```php
// One transaction, multiple earnings
Transaction #456 (₦45,000 total)
├─ Earning #1: Course A → Instructor 1 → ₦8,000 net
├─ Earning #2: Course B → Instructor 2 → ₦12,000 net
└─ Earning #3: Course C → Instructor 1 → ₦16,000 net
```

Each earning record:
- Links to the **same transaction**
- Links to a **specific course**
- Calculates its **own platform fee** (20%)
- Assigns earnings to the **correct instructor**

### Step 3: Instructor Balances Calculated Correctly

When instructors check their earnings:

**Instructor 1:**
```
Course A: ₦10,000 gross → ₦8,000 net (after 20% fee)
Course C: ₦20,000 gross → ₦16,000 net (after 20% fee)
Total: ₦24,000 net earnings
```

**Instructor 2:**
```
Course B: ₦15,000 gross → ₦12,000 net (after 20% fee)
Total: ₦12,000 net earnings
```

## Code Flow

### 1. Cart Transaction Creation

```php
// When student checks out cart
$transaction = Transaction::create([
    'user_id' => $student->id,
    'course_id' => null, // No single course
    'amount' => 45000, // Total cart amount
    'currency' => 'NGN',
    'status' => 'pending',
    'metadata' => [
        'type' => 'cart',
        'course_ids' => [1, 2, 3],
        'items' => [
            ['course_id' => 1, 'price' => 10000],
            ['course_id' => 2, 'price' => 15000],
            ['course_id' => 3, 'price' => 20000],
        ]
    ]
]);
```

### 2. Payment Verification

```php
// In PaymentService::verifyAndProcessPayment()
if ($isCartPurchase) {
    // Enroll in all courses
    foreach ($courseIds as $courseId) {
        $course = Course::find($courseId);
        $this->enrollUserInCourse($transaction->user_id, $course);
    }

    // Record earnings for each course
    $cartItems = $transaction->metadata['items'];
    $this->earningsService->recordEarningsFromCart($transaction, $cartItems);
}
```

### 3. Earnings Creation

```php
// In InstructorEarningsService::recordEarningsFromCart()
public function recordEarningsFromCart(Transaction $transaction, array $cartItems)
{
    $earnings = [];

    foreach ($cartItems as $item) {
        $course = Course::find($item['course_id']);
        $price = $item['price'];

        // Create individual earning record
        $earning = $this->recordEarningFromTransaction(
            $transaction,
            $course,
            $price
        );

        $earnings[] = $earning;
    }

    return $earnings;
}
```

### 4. Individual Earning Record

```php
// Each earning is calculated independently
InstructorEarning::create([
    'instructor_id' => $course->instructor_id,
    'transaction_id' => $transaction->id, // Same transaction
    'course_id' => $course->id,           // Specific course
    'gross_amount' => 10000,              // Course price
    'platform_fee' => 2000,               // 20% of gross
    'net_amount' => 8000,                 // 80% for instructor
    'currency' => 'NGN',
    'status' => 'pending',
    'available_at' => now()->addDays(7)
]);
```

## Database Structure

### instructor_earnings Table

| id | instructor_id | transaction_id | course_id | gross_amount | platform_fee | net_amount | status |
|----|---------------|----------------|-----------|--------------|--------------|------------|--------|
| 1  | 7             | 456            | 1         | 10000.00     | 2000.00      | 8000.00    | pending |
| 2  | 8             | 456            | 2         | 15000.00     | 3000.00      | 12000.00   | pending |
| 3  | 7             | 456            | 3         | 20000.00     | 4000.00      | 16000.00   | pending |

**Key Points:**
- Same `transaction_id` (456) for all cart items
- Different `course_id` and `instructor_id`
- Each row calculates its own fees independently

## Example Scenarios

### Scenario 1: All Courses from Same Instructor

```
Cart: 3 courses from Instructor A (₦10k, ₦15k, ₦20k)
Total: ₦45,000

Result:
- 3 separate earning records
- All linked to Instructor A
- Instructor A total: ₦36,000 net (₦45,000 - 20%)
```

### Scenario 2: Courses from Different Instructors

```
Cart:
- Course A (₦10k) - Instructor 1
- Course B (₦15k) - Instructor 2
- Course C (₦20k) - Instructor 1

Result:
- Instructor 1: 2 earning records = ₦24,000 net
- Instructor 2: 1 earning record = ₦12,000 net
- Platform: ₦9,000 total fees
```

### Scenario 3: Course Price Changes After Purchase

```
Student adds Course A (₦10,000) to cart
Instructor changes price to ₦12,000
Student completes checkout

Earning recorded: ₦10,000 (price at time of cart addition)
✅ Fair - student paid ₦10,000, instructor gets 80% of ₦10,000
```

## Benefits

### ✅ Fair Distribution
Each instructor gets exactly their share based on their course prices.

### ✅ Accurate Accounting
- Platform fees calculated per course
- Total platform fees = sum of individual course fees
- No rounding errors

### ✅ Transparent Tracking
- Each earning shows which transaction and course
- Easy to audit and verify
- Instructors see individual course sales

### ✅ Flexible Queries
```php
// Get all earnings for a specific transaction
InstructorEarning::where('transaction_id', $transactionId)->get();

// Get all earnings for an instructor
InstructorEarning::where('instructor_id', $instructorId)->get();

// Get earnings for a specific course
InstructorEarning::where('course_id', $courseId)->get();
```

## Testing

### Test Cart Earnings

```bash
php artisan tinker
```

```php
use App\Models\Course;
use App\Models\Transaction;
use App\Models\User;
use App\Services\Instructor\InstructorEarningsService;

// Setup
$student = User::first();
$instructor1 = User::role('instructor')->first();
$instructor2 = User::role('instructor')->skip(1)->first();

$course1 = Course::where('instructor_id', $instructor1->id)->first();
$course2 = Course::where('instructor_id', $instructor2->id)->first();

// Create cart transaction
$transaction = Transaction::create([
    'user_id' => $student->id,
    'course_id' => null,
    'transaction_id' => 'CART_TEST_' . time(),
    'amount' => 25000,
    'currency' => 'NGN',
    'status' => 'successful',
    'paid_at' => now(),
    'metadata' => [
        'type' => 'cart',
        'course_ids' => [$course1->id, $course2->id],
        'items' => [
            ['course_id' => $course1->id, 'price' => 10000],
            ['course_id' => $course2->id, 'price' => 15000],
        ]
    ]
]);

// Record cart earnings
$earningsService = app(InstructorEarningsService::class);
$earnings = $earningsService->recordEarningsFromCart(
    $transaction,
    $transaction->metadata['items']
);

// View results
echo "Cart Transaction: {$transaction->transaction_id}\n";
echo "Total Amount: " . number_format($transaction->amount, 2) . " NGN\n\n";

foreach ($earnings as $earning) {
    echo "Earning ID: {$earning->id}\n";
    echo "Instructor: {$earning->instructor->name}\n";
    echo "Course: {$earning->course->title}\n";
    echo "Gross: " . number_format($earning->gross_amount, 2) . " NGN\n";
    echo "Platform Fee: " . number_format($earning->platform_fee, 2) . " NGN\n";
    echo "Net: " . number_format($earning->net_amount, 2) . " NGN\n";
    echo "---\n";
}

// Check instructor balances
$balance1 = $earningsService->getAvailableBalance($instructor1);
$balance2 = $earningsService->getAvailableBalance($instructor2);

echo "\nInstructor 1 Balance: " . number_format($balance1['total_earned'], 2) . " NGN\n";
echo "Instructor 2 Balance: " . number_format($balance2['total_earned'], 2) . " NGN\n";
```

## Verification

### Check Earnings were Created

```sql
SELECT
    ie.id,
    u.name as instructor,
    c.title as course,
    ie.gross_amount,
    ie.platform_fee,
    ie.net_amount,
    t.transaction_id
FROM instructor_earnings ie
JOIN users u ON ie.instructor_id = u.id
JOIN courses c ON ie.course_id = c.id
JOIN transactions t ON ie.transaction_id = t.id
WHERE t.transaction_id = 'CART_TEST_123'
ORDER BY ie.id;
```

### Verify Totals Match

```php
$transaction = Transaction::where('transaction_id', 'CART_TEST_123')->first();
$earnings = InstructorEarning::where('transaction_id', $transaction->id)->get();

$totalGross = $earnings->sum('gross_amount');
$totalNet = $earnings->sum('net_amount');
$totalFees = $earnings->sum('platform_fee');

echo "Transaction Amount: {$transaction->amount}\n";
echo "Sum of Gross Amounts: {$totalGross}\n";
echo "Match: " . ($transaction->amount == $totalGross ? 'YES ✅' : 'NO ❌') . "\n";
```

## Important Notes

### ⚠️ Cart Metadata Must Include Items

The cart checkout process **must** store the items array in metadata:

```php
// Required format
'metadata' => [
    'type' => 'cart',
    'items' => [
        ['course_id' => 1, 'price' => 10000],
        ['course_id' => 2, 'price' => 15000],
    ]
]
```

### ⚠️ Prevents Double Earnings

The system checks for existing earnings:

```php
// Won't create duplicate if earning already exists
$existing = InstructorEarning::where('transaction_id', $transaction->id)
    ->where('course_id', $course->id)
    ->first();
```

### ⚠️ Handles Missing Data Gracefully

```php
// Skips items with missing data
if (!$courseId || !$price) {
    Log::warning('Skipping cart item: Missing course_id or price');
    continue;
}

// Skips if course not found
$course = Course::find($courseId);
if (!$course) {
    Log::warning('Skipping cart item: Course not found');
    continue;
}
```

## Summary

**Cart earnings distribution works by:**

1. **Storing** item details in transaction metadata
2. **Creating** separate earning records for each course
3. **Calculating** platform fees independently per course
4. **Assigning** earnings to the correct instructor
5. **Maintaining** a single transaction for the entire cart

This approach is:
- ✅ **Fair** - each instructor gets their share
- ✅ **Accurate** - no rounding errors
- ✅ **Transparent** - full audit trail
- ✅ **Scalable** - works with any number of courses
- ✅ **Flexible** - supports price changes and refunds

The system now fully supports both single course purchases AND cart purchases with multiple instructors!
