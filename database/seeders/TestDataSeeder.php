<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;
use App\Models\Deal;

class TestDataSeeder extends Seeder
{
    public function run()
    {
        // 20 клиентов
        $clients = [];
        for ($i = 1; $i <= 20; $i++) {
            $clients[] = Client::create([
                'name' => "Клиент {$i}",
                'email' => "client{$i}@test.ru",
                'phone' => "+7 (900) 123-{$i}",
                'address' => "Улица {$i}, дом {$i}",
            ]);
        }
        
        // 100 сделок, распределённых между клиентами
        $statuses = ['new', 'in_progress', 'closed', 'lost'];
        
        for ($i = 1; $i <= 100; $i++) {
            $client = $clients[array_rand($clients)];
            
            Deal::create([
                'client_id' => $client->id,
                'name' => "Сделка {$i}",
                'amount' => rand(1000, 500000) / 10, // от 100 до 50 000 ₽
                'status' => $statuses[array_rand($statuses)],
                'description' => "Описание сделки {$i}",
                'created_at' => now()->subDays(rand(0, 30)),
            ]);
        }
    }
}