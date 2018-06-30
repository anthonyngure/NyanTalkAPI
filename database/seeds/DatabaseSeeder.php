<?php
    
    use Illuminate\Database\Seeder;
    
    class DatabaseSeeder extends Seeder
    {
        /**
         * Seed the application's database.
         *
         * @return void
         */
        public function run()
        {
            /*$tables = [];
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            foreach (DB::select('SHOW TABLES') as $k => $v) {
                $tables[] = array_values((array)$v)[0];
            }
            foreach ($tables as $table) {
                Schema::drop($table);
                echo "Table " . $table . " has been dropped." . PHP_EOL;
            }
            DB::statement('SET FOREIGN_KEY_CHECKS=1');*/
            
            //php artisan migrate --path=database/migrations/tracker --database=tracker
            
            /*DB::statement('ALTER SEQUENCE users_id_seq RESTART WITH ' . random_int(100000, 200000));
            DB::statement('ALTER SEQUENCE tickets_id_seq RESTART WITH ' . random_int(100000, 200000));
            DB::statement('ALTER SEQUENCE topics_id_seq RESTART WITH ' . random_int(100000, 200000));
            DB::statement('ALTER SEQUENCE contributions_id_seq RESTART WITH ' . random_int(100000, 200000));*/
            
            DB::statement('ALTER SEQUENCE users_id_seq RESTART WITH 1');
            DB::statement('ALTER SEQUENCE tickets_id_seq RESTART WITH 1');
            DB::statement('ALTER SEQUENCE topics_id_seq RESTART WITH 1');
            DB::statement('ALTER SEQUENCE contributions_id_seq RESTART WITH 1');
            
            $this->call(DepartmentsTableSeeder::class);
            $this->call(SubCountiesTableSeeder::class);
            $this->call(WardsTableSeeder::class);
            $this->call(UsersTableSeeder::class);
            $this->call(TicketsTableSeeder::class);
            $this->call(ForumsTableSeeder::class);
            $this->call(TopicsTableSeeder::class);
            $this->call(ContributionsTableSeeder::class);
        }
    }
