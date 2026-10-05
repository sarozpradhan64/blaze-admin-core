<?php

namespace Blaze\AdminCore\Http\Controllers;

use App\Http\Controllers\Controller;
use Blaze\AdminCore\Models\NewsletterSubscription;
use Illuminate\Http\Request;

class NewsletterSubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $query = NewsletterSubscription::query();

        if ($request->has('search') && $request->search != '') {
            $query->where('email', 'like', '%' . $request->search . '%');
        }

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('is_active', $request->status === 'active');
        }

        $subscriptions = $query->latest()->paginate($request->get('per_page', 15))->withQueryString();

        return view('admin-core::newsletter_subscriptions.index', compact('subscriptions'));
    }

    public function destroy(NewsletterSubscription $newsletterSubscription)
    {
        $newsletterSubscription->delete();
        return back()->with('success', 'Subscription removed successfully.');
    }
}
