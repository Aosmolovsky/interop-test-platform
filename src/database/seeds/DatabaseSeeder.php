<?php declare(strict_types=1);

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * @return void
     */
    public function run()
    {
        $this->call([
            UsersTableSeeder::class,
            ApisTableSeeder::class,
//            ScenariosTableSeeder::class,
        ]);
    }
}
