<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use App\Models\Deal;

class DealTest extends TestCase
{
    // Страница списка сделок открывается
    public function test_deals_page_is_accessible()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/deals');
        $response->assertStatus(200);
    }

    // Можно создать сделку через веб-форму
    public function test_can_create_deal_via_web()
    {
        $user = User::factory()->create();
        $client = Client::factory()->create();

        $data = [
            'client_id' => $client->id,
            'name' => 'Тестовая сделка',
            'amount' => 10000,
            'status' => 'new',
            'description' => 'Тестовое описание',
        ];

        $response = $this->actingAs($user)->post('/deals', $data);
        $response->assertRedirect('/deals');
        $this->assertDatabaseHas('deals', ['name' => 'Тестовая сделка']);
    }

    // API возвращает список сделок в JSON
    public function test_api_returns_deals_json()
    {
        $response = $this->getJson('/api/deals');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => ['id', 'name', 'amount', 'status', 'client_id']
        ]);
    }
}