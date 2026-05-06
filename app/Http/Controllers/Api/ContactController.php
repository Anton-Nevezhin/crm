<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{
    // GET /api/contacts — список всех контактов
    public function index()
    {
        $contacts = Contact::with('client')->get();
        return response()->json($contacts);
    }

    // POST /api/contacts — создание контакта
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'type' => 'required|in:call,meeting,email',
            'contact_date' => 'required|date',
            'comment' => 'nullable|string',
        ]);

        $contact = Contact::create($validated);
        return response()->json($contact, 201);
    }

    // GET /api/contacts/{id} — просмотр контакта
    public function show($id)
    {
        $contact = Contact::with('client')->findOrFail($id);
        return response()->json($contact);
    }

    // PUT /api/contacts/{id} — обновление контакта
    public function update(Request $request, $id)
    {
        $contact = Contact::findOrFail($id);

        $validated = $request->validate([
            'client_id' => 'sometimes|exists:clients,id',
            'type' => 'sometimes|in:call,meeting,email',
            'contact_date' => 'sometimes|date',
            'comment' => 'nullable|string',
        ]);

        $contact->update($validated);
        return response()->json($contact);
    }

    // DELETE /api/contacts/{id} — удаление контакта
    public function destroy($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();
        return response()->json(null, 204);
    }
}
