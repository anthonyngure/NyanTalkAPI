<?php
    
    use App\Contribution;
    use Illuminate\Database\Seeder;
    
    class ContributionsTableSeeder extends Seeder
    {
        /**
         * Run the database seeds.
         *
         * @return void
         */
        public function run()
        {
            //
            
            $faker = \Faker\Factory::create();
            for ($i = 0; $i < 100; $i++){
                Contribution::create([
                    'user_id'  => \App\User::inRandomOrder()->first()->getKey(),
                    'topic_id' => \App\Topic::inRandomOrder()->first()->getKey(),
                    'text'     => $faker->realText(190),
                ]);
            }
        }
    }
