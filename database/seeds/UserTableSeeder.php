<?php

use Illuminate\Database\Seeder;
use App\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->name = 'User';
        $user->email = 'user@example.com';
        $user->password = bcrypt('user');
        $user->confirmed = 1;
        $user->save();
        $user2 = new User();
        $user2->name = 'UserAdmin';
        $user2->email = 'useradmin@example.com';
        $user2->password = bcrypt('useradmin');
        $user2->confirmed = 1;
        $user2->save();

        factory(User::class, 10)->create();
    }
}
