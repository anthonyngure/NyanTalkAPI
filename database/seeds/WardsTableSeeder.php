<?php
    
    use App\SubCounty;
    use App\Ward;
    use Illuminate\Database\Seeder;
    
    class WardsTableSeeder extends Seeder
    {
        /**
         * Run the database seeds.
         *
         * @return void
         */
        public function run()
        {
            //
            
            //Seed wards for Kipipiri
            $kipipiri = SubCounty::whereName('Kipipiri')->firstOrFail();
            $kipipiri->wards()->save(new Ward(['name' => 'Wanjohi']));
            $kipipiri->wards()->save(new Ward(['name' => 'Kipipiri']));
            $kipipiri->wards()->save(new Ward(['name' => 'Geta']));
            $kipipiri->wards()->save(new Ward(['name' => 'Githioro']));
            
            //Seed wards for Kinangop
            $kinangop = SubCounty::whereName('Kinangop')->firstOrFail();
            $kinangop->wards()->save(new Ward(['name' => 'Engineer']));
            $kinangop->wards()->save(new Ward(['name' => 'Gathara']));
            $kinangop->wards()->save(new Ward(['name' => 'North Kinangop']));
            $kinangop->wards()->save(new Ward(['name' => 'Murungaru']));
            $kinangop->wards()->save(new Ward(['name' => 'Njabini/Kibiru']));
            $kinangop->wards()->save(new Ward(['name' => 'Nyakio']));
            $kinangop->wards()->save(new Ward(['name' => 'Magumu']));
            $kinangop->wards()->save(new Ward(['name' => 'Githabai']));
            
            
            //Seed wards for ol Joro Orok
            $olJoroOrok = SubCounty::whereName('ol Joro Orok')->firstOrFail();
            $olJoroOrok->wards()->save(new Ward(['name' => 'Gathanji']));
            $olJoroOrok->wards()->save(new Ward(['name' => 'Gatimu']));
            $olJoroOrok->wards()->save(new Ward(['name' => 'Weru']));
            $olJoroOrok->wards()->save(new Ward(['name' => 'Charagita']));
            
            //Seed wards for Ndaragwa
            $ndaragwa = SubCounty::whereName('Ndaragwa')->firstOrFail();
            $ndaragwa->wards()->save(new Ward(['name' => 'Leshau/Pondo']));
            $ndaragwa->wards()->save(new Ward(['name' => 'Kiriita']));
            $ndaragwa->wards()->save(new Ward(['name' => 'Central']));
            $ndaragwa->wards()->save(new Ward(['name' => 'Shamata']));
            
            //Seed wards for Ol Kalou
            $ndaragwa = SubCounty::whereName('Ol Kalou')->firstOrFail();
            $ndaragwa->wards()->save(new Ward(['name' => 'Karau']));
            $ndaragwa->wards()->save(new Ward(['name' => 'Kanjuire Ridge']));
            $ndaragwa->wards()->save(new Ward(['name' => 'Mirangine']));
            $ndaragwa->wards()->save(new Ward(['name' => 'Kaimbaga']));
            $ndaragwa->wards()->save(new Ward(['name' => 'Rurii']));
            
        }
    }
