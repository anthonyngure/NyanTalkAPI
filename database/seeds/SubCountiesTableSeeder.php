<?php
    
    use App\SubCounty;
    use Illuminate\Database\Seeder;

class SubCountiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        SubCounty::create(['name' => 'Kipipiri']);
        SubCounty::create(['name' => 'Kinangop']);
        SubCounty::create(['name' => 'ol Joro Orok']);
        SubCounty::create(['name' => 'Ndaragwa']);
        SubCounty::create(['name' => 'Ol Kalou']);
    }
}
