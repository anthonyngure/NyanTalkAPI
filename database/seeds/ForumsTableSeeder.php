<?php
    
    use App\Forum;
    use Illuminate\Database\Seeder;
    
    class ForumsTableSeeder extends Seeder
    {
        /**
         * Run the database seeds.
         *
         * @return void
         */
        public function run()
        {
            Forum::create(['name' => 'Youth, Sport & the Arts']);
            Forum::create(['name' => 'Education']);
            Forum::create(['name' => 'Gender Affairs, Culture & Social Services']);
            Forum::create(['name' => 'Culture']);
            Forum::create(['name' => 'Social Services']);
            Forum::create(['name' => 'Energy']);
            Forum::create(['name' => 'Transport']);
            Forum::create(['name' => 'Public Works']);
            Forum::create(['name' => 'Agriculture, Livestock & Fisheries']);
            Forum::create(['name' => 'Water, Environment & Natural Resources']);
            Forum::create(['name' => 'Tourism']);
            Forum::create(['name' => 'Industrialization, Trade & Cooperatives']);
            Forum::create(['name' => 'Lands, Housing & Physical Planning & Urban Development']);
            Forum::create(['name' => 'Health Services']);
            Forum::create(['name' => 'Finance & Economic Development']);
        }
    }
