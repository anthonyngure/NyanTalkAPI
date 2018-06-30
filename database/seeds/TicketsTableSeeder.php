<?php
    
    use App\Department;
    use App\Ticket;
    use App\User;
    use App\Ward;
    use Illuminate\Database\Seeder;
    
    class TicketsTableSeeder extends Seeder
    {
        /**
         * Run the database seeds.
         *
         * @return void
         */
        public function run()
        {
            //
            $faker = Faker\Factory::create();
            $testCitizen = User::whereName('Test Citizen')->firstOrFail();
            
            for ($i = 0; $i < 50; $i++) {
                /** @var \App\Ticket $ticket */
                $ticket = $testCitizen->tickets()->save(new Ticket([
                    'department_id' => Department::withoutTrashed()->inRandomOrder()->firstOrFail()->id,
                    'ward_id'       => Ward::whereKeyNot(0)->inRandomOrder()->firstOrFail()->id,
                    'subject'       => $faker->text(50),
                    'details'       => $faker->text(),
                ]));
                
                $official = $ticket->official()->first();
                
                if ($official) {
                    $randomRes = random_int(2, 5);
                    for ($j = 0; $j < $randomRes; $j++) {
                        $official = User::whereId($ticket->assigned_official_id)->first();
                        $ticket->responses()->save(new \App\Response([
                            'user_id' => $official->id,
                            'details' => $faker->text(),
                        ]));
                    }
                    
                    //Rating
                    /*$ticket->rating()->create([
                        'text'    => $faker->realText(),
                        'stars'   => random_int(1, 5),
                        'user_id' => $testCitizen->id,
                    ]);*/
                }
            }
        }
    }
