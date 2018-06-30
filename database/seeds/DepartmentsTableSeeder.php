<?php
    
    use App\Department;
    use Illuminate\Database\Seeder;
    
    class DepartmentsTableSeeder extends Seeder
    {
        /**
         * Run the database seeds.
         *
         * @return void
         */
        public function run()
        {
            Department::create(['name' => 'General']);
            Department::create(['name' => 'Test Department']);
            Department::create(['name' => 'County Secretary & Head of Public Service']);
            Department::create(['name' => 'Youth, Sport & the Arts']);
            Department::create(['name' => 'Public Administration & ICT']);
            Department::create(['name' => 'Education, Gender Affairs, Culture & Social Services']);
            Department::create(['name' => 'Transport, Energy & Public Works']);
            Department::create(['name' => 'Agriculture, Livestock & Fisheries']);
            Department::create(['name' => 'Water, Environment, Tourism & Natural Resources']);
            Department::create(['name' => 'Industrialization, Trade & Cooperatives']);
            Department::create(['name' => 'Lands, Housing & Physical Planning & Urban Development']);
            Department::create(['name' => 'Health Services']);
            Department::create(['name' => 'Finance & Economic Development Governance']);
            
        }
    }
