<?php

namespace Blaze\AdminCore\Http\Controllers;

use App\Http\Controllers\Controller;
use Blaze\AdminCore\Models\Faq;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FaqController extends Controller
{
    public function index(Request $request)
    {
        // For the picker component
        if ($request->expectsJson()) {
            $search = trim((string) $request->query('search', ''));
            
            $faqs = Faq::query()
                ->when($search !== '', function ($query) use ($search): void {
                    $query->where('question', 'like', '%' . $search . '%');
                })
                ->orderByDesc('usage_count')
                ->orderBy('sort_order')
                ->limit(20)
                ->get(['id', 'question', 'usage_count']);
                
            return response()->json($faqs);
        }

        // For management UI
        $search = trim((string) $request->query('search', ''));

        $faqs = Faq::query()
            ->when($search !== '', fn($query) => $query->where('question', 'like', '%' . $search . '%'))
            ->orderBy('sort_order')
            ->paginate(15)
            ->withQueryString();

        return view('admin-core::faqs.index', compact('faqs', 'search'));
    }

    public function create()
    {
        return view('admin-core::faqs.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'question' => ['required', 'string', 'max:255'],
            'answer' => ['required', 'string'],
            'status' => ['boolean'],
        ]);

        Faq::create([
            'question' => $validated['question'],
            'answer' => $validated['answer'],
            'status' => $request->has('status'),
            'sort_order' => Faq::max('sort_order') + 1,
        ]);

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ created successfully.');
    }

    public function edit(Faq $faq)
    {
        return view('admin-core::faqs.form', compact('faq'));
    }

    public function update(Request $request, Faq $faq)
    {
        $validated = $request->validate([
            'question' => ['required', 'string', 'max:255'],
            'answer' => ['required', 'string'],
        ]);

        $faq->update([
            'question' => $validated['question'],
            'answer' => $validated['answer'],
            'status' => $request->has('status'),
        ]);

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ updated successfully.');
    }

    public function destroy(Faq $faq)
    {
        if ($faq->usage_count > 0) {
            return back()->with('error', 'FAQs in use cannot be deleted. Remove it from content first.');
        }

        DB::table('faqables')->where('faq_id', $faq->id)->delete();
        $faq->delete();

        return back()->with('success', 'FAQ deleted successfully.');
    }
}
