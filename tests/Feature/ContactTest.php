<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use App\Models\Contact;

class ContactTest extends TestCase
{
    public function test_contacts_page_is_accessible()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/contacts');
        $response->assertStatus(200);
    }

    public function test_can_create_contact_via_web()
    {
        $user = User::factory()->create();
        $client = Client::factory()->create();

        $data = [
            'client_id' => $client->id,
            'type' => 'call',
            'contact_date' => '2026-05-10',
            'comment' => 'Тестовый звонок',
        ];

        $response = $this->actingAs($user)->post('/contacts', $data);
        $response->assertRedirect('/contacts');
        $this->assertDatabaseHas('contacts', ['comment' => 'Тестовый звонок']);
    }

    public function test_api_returns_contacts_json()
    {
        $response = $this->getJson('/api/contacts');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => ['id', 'type', 'contact_date', 'client_id']
        ]);
    }
}