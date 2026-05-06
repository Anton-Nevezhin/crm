<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Client;

class ClientTest extends TestCase
{
    // Тест 1: страница списка клиентов открывается
    public function test_clients_page_is_accessible()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/clients');
        $response->assertStatus(200);
    }

    // Тест 2: можно создать клиента через веб-форму
    public function test_can_create_client_via_web()
    {
        $user = User::factory()->create();
        $data = [
            'name' => 'Тестовый клиент',
            'email' => 'test@client.ru',
            'phone' => '123456789',
            'address' => 'Тестовый адрес',
        ];

        $response = $this->actingAs($user)->post('/clients', $data);
        $response->assertRedirect('/clients');
        $this->assertDatabaseHas('clients', ['email' => 'test@client.ru']);
    }

    // Тест 3: API возвращает список клиентов в JSON
    public function test_api_returns_clients_json()
    {
        $response = $this->getJson('/api/clients');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => ['id', 'name', 'email']
        ]);
    }
}