<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use function Laravel\Prompts\info;
use function Laravel\Prompts\text;
use function Laravel\Prompts\password;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Super Admin
        $role = Role::firstOrCreate([
            'name' => 'super admin'
        ]);

        info('Create Super admin by filling the following inputs;-');

        $first_name = text(
            label: 'Enter first name:',
            required: true
        );

        $middle_name = text(
            label: 'Enter middle name:',
            required: true
        );

        $last_name = text(
            label: 'Enter last name:',
            required: true
        );

        $email = text(
            label: 'Enter email:',
            required: true
        );

        $phone = text(
            label: 'Enter phone:',
            required: true
        );

        $password = password(
            label: 'Enter password:',
            required: true
        );

        $user = \App\Models\User::factory()->create([
            'first_name' => $first_name,
            'middle_name' => $middle_name,
            'last_name' => $last_name,
            'email' => $email,
            'phone' => $phone,
            'password' => $password
        ]);

        $user->assignRole($role);

        info('Super admin created successful!');

    }
}
