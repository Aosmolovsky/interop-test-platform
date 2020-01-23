<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class)->create([
            'first_name' => 'GSMA',
            'last_name' => 'Admin',
            'role' => User::ROLE_ADMIN,
            'email' => 'admin@gsma.com',
        ]);
        factory(User::class)->create([
            'first_name' => 'GSMA',
            'last_name' => 'Superadmin',
            'role' => User::ROLE_SUPERADMIN,
            'email' => 'superadmin@gsma.com',
        ]);
        factory(User::class, 20)->create();
    }
}
