<?php

namespace Blaze\AdminCore\Http\Controllers;

use App\Http\Controllers\Controller;
use Blaze\AdminCore\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    public function index(Request $request)
    {
        $messages = ContactMessage::latest()->paginate(15);
        return view('admin-core::contact_messages.index', compact('messages'));
    }

    public function show(ContactMessage $contactMessage)
    {
        if ($contactMessage->status === 'new') {
            $contactMessage->update(['status' => 'read']);
        }
        return view('admin-core::contact_messages.show', ['message' => $contactMessage]);
    }

    public function update(Request $request, ContactMessage $contactMessage)
    {
        $validated = $request->validate([
            'status' => 'required|in:new,read,replied,closed,spam',
        ]);
        
        if ($validated['status'] === 'replied' && !$contactMessage->replied_at) {
            $validated['replied_at'] = now();
        }

        $contactMessage->update($validated);
        return back()->with('success', 'Message status updated.');
    }

    public function destroy(ContactMessage $contactMessage)
    {
        $contactMessage->delete();
        return redirect()->route('admin.contact-messages.index')->with('success', 'Message deleted.');
    }
}

