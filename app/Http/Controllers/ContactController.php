<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Message;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Mail\ContactSubmittedMail;
use Illuminate\Support\Facades\Mail;


class ContactController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            // 'form_token' => 'required',
            'organization_id' => 'required|exists:organizations,id',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => ['required', 'regex:/^(\+977)?9[6-9]\d{8}$/'],
            'message' => 'nullable|string',
        ]);


        // if (
        //     Session::has('submitted_token') &&
        //     Session::get('submitted_token') === $request->form_token
        // ) {
        //     return redirect()
        //         ->route('contact')
        //         ->with('error', 'You have already submitted this form.');
        // }

        $contact = Contact::create($request->except('form_token'));
        $contact->load('organization');

        Mail::to($contact->email)
            ->send(new ContactSubmittedMail($contact));

        Session::put('submitted_token', $request->form_token);

        return response()->json([
            'status' => true,
            'message' => 'Your message has been submitted successfully.',
        ]);
    }


}