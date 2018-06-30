<?php
    
    use App\Department;
    use App\User;
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
            //
            
            //A user for test admin
            User::create([
                'name'                    => 'Administrator',
                'password'                => Hash::make('admin'),
                'email'                   => 'admin',
                'sms_notifiable'          => false,
                'email_notifiable'        => false,
                'deletable'               => false,
                'phone_verified'          => true,
                'type'                    => \App\Constants::USER_TYPE_ADMIN,
                'phone_verification_code' => 9999,
            ]);
            
            //A user for test citizen
            User::create([
                'name'                    => 'Test Citizen',
                'phone'                   => \App\Utils::phoneWithCode('0700000000'),
                'password'                => Hash::make('testcitizen'),
                'email'                   => 'testcitizen@tickets.com',
                'phone_verified'          => true,
                'ward_id'                 => \App\Ward::whereKeyNot(0)->inRandomOrder()->first()->id,
                'type'                    => \App\Constants::USER_TYPE_CITIZEN,
                'phone_verification_code' => 9999,
            ]);
            
            User::create([
                'name'                    => 'Test Department Admin',
                'department_id'           => Department::whereName('Test Department')->firstOrFail()->id,
                'password'                => Hash::make('testdepartmentadmin'),
                'email'                   => 'testdepartmentadmin@nccms.com',
                'phone_verified'          => true,
                'type'                    => User::TYPE_DEPARTMENT_ADMIN,
                'phone_verification_code' => 9999,
            ]);
            
            User::create([
                'name'                    => 'Test Cabinet Secretaries',
                'department_id'           => Department::whereName('Test Department')->firstOrFail()->id,
                'password'                => Hash::make('testcs'),
                'email'                   => 'testcs@nccms.com',
                'phone_verified'          => true,
                'type'                    => User::TYPE_DEPARTMENT_ADMIN,
                'phone_verification_code' => 9999,
            ]);
            
            //A user for test official
            User::create([
                'name'                    => 'Test Official One',
                'department_id'           => Department::whereName('Test Department')->firstOrFail()->id,
                'phone'                   => \App\Utils::phoneWithCode('0700000001'),
                'password'                => Hash::make('testofficial1'),
                'email'                   => 'testofficial1@nccms.com',
                'phone_verified'          => true,
                'type'                    => \App\Constants::USER_TYPE_OFFICIAL,
                'phone_verification_code' => 9999,
            ]);
            
            //A user for test official
            User::create([
                'name'                    => 'Test Official Two',
                'department_id'           => Department::whereName('Test Department')->firstOrFail()->id,
                'phone'                   => \App\Utils::phoneWithCode('0700000002'),
                'password'                => Hash::make('testofficial2'),
                'email'                   => 'testofficial2@nccms.com',
                'phone_verified'          => true,
                'type'                    => \App\Constants::USER_TYPE_OFFICIAL,
                'phone_verification_code' => 9999,
            ]);
            
            //A user for test official
            User::create([
                'name'                    => 'Test Official Three',
                'department_id'           => Department::whereName('Test Department')->firstOrFail()->id,
                'phone'                   => \App\Utils::phoneWithCode('0700000003'),
                'password'                => Hash::make('testofficial3'),
                'email'                   => 'testofficial3@nccms.com',
                'phone_verified'          => true,
                'type'                    => \App\Constants::USER_TYPE_OFFICIAL,
                'phone_verification_code' => 9999,
            ]);
            
            //A user for test admin
            User::create([
                'name'                    => 'Test Admin',
                'phone'                   => \App\Utils::phoneWithCode('0700000004'),
                'password'                => Hash::make('testadmin'),
                'email'                   => 'testadmin@nccms.com',
                'phone_verified'          => true,
                'type'                    => \App\Constants::USER_TYPE_ADMIN,
                'phone_verification_code' => 9999,
            ]);
            
            
            for ($i = 0; $i < 10; $i++) {
                //A user for test citizen
                User::create([
                    'name'                    => "Test Citizen $i",
                    //'phone'                   => \App\Utils::phoneWithCode('0700000000'),
                    'password'                => Hash::make('testcitizen'),
                    'email'                   => "testcitizen$i@tickets.com",
                    'phone_verified'          => true,
                    'type'                    => \App\Constants::USER_TYPE_CITIZEN,
                    'phone_verification_code' => 9999,
                ]);
            }
            
        }
    }
