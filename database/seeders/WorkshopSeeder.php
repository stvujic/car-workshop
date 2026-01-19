<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Workshop;
use Illuminate\Database\Seeder;

class WorkshopSeeder extends Seeder
{
    public function run(): void
    {
        // 1) Napravi admina (da ga imas kasnije)
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@carservice.test',
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]);

        // 2) Napravi nekoliko ownera
        $owners = User::factory()->count(5)->create([
            'role' => 'owner',
            'password' => bcrypt('password'),
        ]);

        // 3) Napravi 30 workshopova i dodeli random owner-a
        Workshop::factory()
            ->count(30)
            ->make()
            ->each(function ($workshop) use ($owners) {
                $workshop->owner()->associate($owners->random());
                $workshop->save();
            });
    }
}
