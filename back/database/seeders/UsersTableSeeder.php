<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Services\User\UserService;
use App\Values\RoleValue;
use Carbon\Carbon;
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
        $userService = new UserService();

        $users = [
            [
                'id' => 1,
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'user_name' => 'superadmin',
                'email' => 'superadmin@app.com',
                'email_verified_at' => Carbon::now()->toDateTimeString(),
                'password' => $userService->generatePassword("S123456"),
            ],
            [
                'id' => 2,
                'first_name' => 'Staff',
                'last_name' => 'Admin',
                'user_name' => 'staffadmin',
                'email' => 'staffadmin@app.com',
                'email_verified_at' => Carbon::now()->toDateTimeString(),
                'password' => $userService->generatePassword("S123456"),
            ]
        ];

        $superAdminUser = User::create($users[0]);
        $staffAdminUser = User::create($users[1]);

        $superAdmin = Role::find(RoleValue::SUPER_ADMIN_ID);
        $staffAdmin = Role::find(RoleValue::STAFF_ADMIN_ID);

        $superAdminUser->attachRole($superAdmin);
        $staffAdminUser->attachRole($staffAdmin);


    }
}
