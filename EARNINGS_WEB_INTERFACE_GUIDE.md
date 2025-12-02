# Instructor Earnings Web Interface - User Guide

## Access the Earnings Dashboard

**URL:** `/instructor/earnings`

**Requirements:**
- Must be logged in
- Must have "instructor" role

## Dashboard Overview

### Balance Cards (Top Section)

The dashboard shows 4 key metrics:

1. **💰 Available Balance** (Green)
   - Money ready to withdraw
   - Can request payout when ≥ 5,000 NGN

2. **⏳ Pending Balance** (Yellow)
   - Money in 7-day holding period
   - Will become available automatically after holding period

3. **📊 Total Earned** (Gray)
   - Lifetime earnings after 20% platform commission
   - Sum of all earnings (pending + available + paid)

4. **💸 Total Paid Out** (Blue)
   - Total amount withdrawn
   - Sum of all completed payouts

### Request Payout Button

- **Appears when:** Available balance ≥ 5,000 NGN
- **Action:** Click to request withdrawal
- **Note:** Minimum payout is configurable in `.env` file

### Earnings History Table

Shows all your course sales earnings:

| Column | Description |
|--------|-------------|
| **Course** | Name of the course sold |
| **Date** | When the purchase was made |
| **Gross Amount** | Full course price paid by student |
| **Platform Fee** | 20% commission taken by platform |
| **Net Amount** | Your earnings (80% of gross) |
| **Status** | `pending`, `available`, `paid`, or `refunded` |
| **Available At** | When pending earnings become available |

**Status Meanings:**
- 🟡 **Pending**: In 7-day holding period
- 🟢 **Available**: Ready to withdraw
- 🔵 **Paid**: Already withdrawn in a payout
- 🔴 **Refunded**: Course purchase was refunded

### Payout Requests Table

Shows all your withdrawal requests:

| Column | Description |
|--------|-------------|
| **Reference** | Unique payout tracking number |
| **Amount** | Withdrawal amount |
| **Method** | `bank_transfer`, `payoneer`, or `manual` |
| **Requested** | When you requested the payout |
| **Status** | Current payout status |

**Payout Statuses:**
- 🟡 **Pending**: Awaiting admin approval
- 🟣 **Processing**: Being processed
- 🟢 **Completed**: Money sent
- 🔴 **Failed**: Payout failed
- ⚫ **Cancelled**: Payout cancelled

## How Earnings Work

### When a Student Purchases Your Course:

1. **Transaction Completes**
   - Student pays full course price (e.g., 10,000 NGN)

2. **Platform Commission Deducted**
   - Platform takes 20% (2,000 NGN)
   - You get 80% (8,000 NGN)

3. **Earning Created**
   - Status: **Pending**
   - Available at: 7 days from now

4. **After 7 Days (Automatic)**
   - Status changes to: **Available**
   - You can now request payout

5. **Request Payout** (When balance ≥ 5,000 NGN)
   - Choose payout method
   - Enter bank details
   - Submit request

6. **Admin Processes Payout**
   - Status: **Processing**
   - Then: **Completed** (money sent)

### Automatic Status Updates

The system uses "lazy evaluation" - your earnings status is automatically updated whenever you:
- Visit the earnings page
- Navigate around the instructor dashboard
- Access any instructor feature

**No cron jobs needed!** Perfect for shared hosting.

## Test Data

For demonstration, the system has created test earnings:

**Instructor:** markonuoha97@gmail.com
- Available: 6,240.00 NGN
- Pending: 12,000.00 NGN
- Total: 18,240.00 NGN

**To view:**
1. Login with the instructor account
2. Visit `/instructor/earnings`

## Customization

You can customize the earnings system in your `.env` file:

```env
# Platform commission percentage (default: 20%)
PLATFORM_COMMISSION_PERCENTAGE=20.0

# Holding period in days (default: 7)
PAYOUT_HOLDING_PERIOD_DAYS=7

# Minimum payout amount in NGN (default: 5000)
MINIMUM_PAYOUT_AMOUNT=5000

# Default payout method
DEFAULT_PAYOUT_METHOD=bank_transfer
```

## Features

✅ **Real-time updates** - Balance updates automatically
✅ **No cron jobs** - Works on shared hosting
✅ **Responsive design** - Works on mobile and desktop
✅ **Status indicators** - Clear visual status badges
✅ **Detailed history** - See all earnings and payouts
✅ **Secure** - Protected by authentication and role middleware

## Navigation

Currently, you access the earnings page by visiting `/instructor/earnings` directly.

**Suggested:** Add a link in your instructor navigation menu:

```html
<a href="/instructor/earnings">Earnings & Payouts</a>
```

## Next Steps

### For Instructors:
1. Check your earnings regularly
2. Request payout when balance ≥ 5,000 NGN
3. Track payout status

### For Admins:
1. Build admin interface to process payout requests
2. Add email notifications for:
   - New earnings
   - Earnings available
   - Payout completed
3. Add export functionality for accounting

## Troubleshooting

### "No earnings yet" message
- No course sales have been recorded
- Check that courses are published and purchasable

### Balance shows 0
- All earnings are still in pending status (7-day holding)
- Check "Available At" date in earnings table

### Can't request payout
- Available balance < 5,000 NGN
- Need more course sales or wait for pending earnings

### Earnings not showing
- Clear cache: `php artisan cache:clear`
- Verify transaction was marked as successful
- Check instructor_earnings table in database

## Support

For technical issues:
- Check logs: `storage/logs/laravel.log`
- Run tests: `php artisan test:instructor-earnings`
- Review documentation: `INSTRUCTOR_EARNINGS_TESTING.md`
