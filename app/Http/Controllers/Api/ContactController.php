<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Mail\ContactSubmittedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'organization_id' => 'required|exists:organizations,id',
                'name'            => 'required|string|max:255',
                'email'           => 'nullable|email|max:255',
                'phone' => ['required', 'regex:/^9[6-9]\d{8}$/'],
                'message'         => 'nullable|string',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation failed.',
                'errors'  => $e->errors(),
            ], 422);
        }

        $contact = Contact::create($validated);
        $contact->load('organization');

        return response()->json([
            'status'  => true,
            'message' => 'Your message has been submitted successfully.',
        ], 201);
    }
}