<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        foreach ([['Admin','admin@example.com','admin'],['Data Entry','dataentry@example.com','data_entry'],['Viewer','viewer@example.com','viewer']] as [$name,$email,$role]) User::updateOrCreate(['email'=>$email],['name'=>$name,'role'=>$role,'password'=>bcrypt('password')]);
    }
}
