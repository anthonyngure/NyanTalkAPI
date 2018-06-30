<?php
    
    use App\Forum;
    use App\Topic;
    use App\User;
    use Illuminate\Database\Seeder;
    
    class TopicsTableSeeder extends Seeder
    {
        /**
         * Run the database seeds.
         *
         * @return void
         */
        public function run()
        {
            $testCitizen = User::whereName('Test Citizen')->firstOrFail();
            $faker = \Faker\Factory::create();
            for ($i = 0; $i < 20; $i++) {
                Topic::create([
                    'forum_id'    => Forum::withoutTrashed()->inRandomOrder()->first()->getKey(),
                    'user_id'     => $testCitizen->getKey(),
                    'title'       => $faker->realText(50),
                    'description' => $faker->realText(190),
                ]);
            }
            
        }
    }
