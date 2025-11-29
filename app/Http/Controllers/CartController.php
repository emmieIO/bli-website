<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CartController extends Controller
{
    /**
     * Display the user's cart
     */
    public function index()
    {
        $user = auth()->user();

        // For guest users, show empty cart
        if (!$user) {
            return Inertia::render('Cart/Index', [
                'cart' => [
                    'items' => [],
                    'total' => 0,
                    'itemCount' => 0,
                ],
            ]);
        }

        $cart = $user->cart()->with(['items.course.instructor', 'items.course.category'])->first();

        $cartData = [
            'items' => $cart ? $cart->items->map(fn($item) => [
                'id' => $item->id,
                'course' => $item->course,
                'price' => $item->price,
            ]) : [],
            'total' => $cart ? $cart->total : 0,
            'itemCount' => $cart ? $cart->item_count : 0,
        ];

        return Inertia::render('Cart/Index', [
            'cart' => $cartData,
        ]);
    }

    /**
     * Add a course to cart
     */
    public function add(Request $request, Course $course)
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login')->with([
                'message' => 'Please login to add courses to cart',
                'type' => 'info'
            ]);
        }

        // Check if course is free
        if ($course->price <= 0 || $course->is_free) {
            return redirect()->back()->with([
                'message' => 'Free courses do not need to be added to cart. Click "Enroll Now" instead.',
                'type' => 'info'
            ]);
        }

        // Check if already enrolled
        if ($course->students()->where('user_id', $user->id)->exists()) {
            return redirect()->back()->with([
                'message' => 'You are already enrolled in this course',
                'type' => 'info'
            ]);
        }

        $cart = $user->getOrCreateCart();

        // Check if already in cart
        if ($cart->hasCourse($course)) {
            return redirect()->back()->with([
                'message' => 'Course is already in your cart',
                'type' => 'info'
            ]);
        }

        $cart->addCourse($course);

        return redirect()->back()->with([
            'message' => 'Course added to cart successfully',
            'type' => 'success'
        ]);
    }

    /**
     * Remove a course from cart
     */
    public function remove(Request $request, Course $course)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $cart = $user->cart;

        if ($cart && $cart->removeCourse($course)) {
            return redirect()->back()->with([
                'message' => 'Course removed from cart',
                'type' => 'success'
            ]);
        }

        return redirect()->back()->with([
            'message' => 'Course not found in cart',
            'type' => 'error'
        ]);
    }

    /**
     * Clear all items from cart
     */
    public function clear()
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $cart = $user->cart;

        if ($cart) {
            $cart->clear();
        }

        return redirect()->back()->with([
            'message' => 'Cart cleared successfully',
            'type' => 'success'
        ]);
    }

    /**
     * Get cart count (for navbar)
     */
    public function count()
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['count' => 0]);
        }

        $cart = $user->cart;
        $count = $cart ? $cart->item_count : 0;

        return response()->json(['count' => $count]);
    }

    /**
     * Proceed to checkout
     */
    public function checkout()
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login')->with([
                'message' => 'Please login to checkout',
                'type' => 'info'
            ]);
        }

        $cart = $user->cart()->with(['items.course.instructor'])->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with([
                'message' => 'Your cart is empty',
                'type' => 'warning'
            ]);
        }

        return Inertia::render('Cart/Checkout', [
            'cart' => [
                'items' => $cart->items->map(fn($item) => [
                    'id' => $item->id,
                    'course' => $item->course,
                    'price' => $item->price,
                ]),
                'total' => $cart->total,
                'itemCount' => $cart->item_count,
            ],
            'paystackPublicKey' => config('services.paystack.public_key'),
        ]);
    }
}
