<?php

use Illuminate\Database\Seeder;

class CitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cities')->delete();
        $city = array(
            array('name' => "Prishtinë", 'id' => 1),
            array('name' => "Pejë", 'id' => 2),
            array('name' => "Gjakovë", 'id' => 3),
            array('name' => "Podujevë", 'id' => 4),
            array('name' => "Deçan", 'id' => 5),
            array('name' => "Sharr", 'id' => 6),
            array('name' => "Drenas", 'id' => 7),
            array('name' => "Gjilan", 'id' => 8),
            array('name' => "Istog", 'id' => 9),
            array('name' => "Kaçanik", 'id' => 10),
            array('name' => "Dardanë", 'id' => 11),
            array('name' => "Klinë", 'id' => 12),
            array('name' => "Fushë Kosovë", 'id' => 13),
            array('name' => "Lipjan", 'id' => 14),
            array('name' => "Malishevë", 'id' => 15),
            array('name' => "Mitrovicë", 'id' => 16),
            array('name' => "Albanik", 'id' => 17),
            array('name' => "Artanë", 'id' => 18),
            array('name' => "Kastriot", 'id' => 19),
            array('name' => "Rahovec", 'id' => 20),
            array('name' => "Prizren", 'id' => 21),
            array('name' => "Skënderaj", 'id' => 22),
            array('name' => "Shtërpcë", 'id' => 23),
            array('name' => "Shtime", 'id' => 24),
            array('name' => "Therandë", 'id' => 25),
            
        );
        DB::table('cities')->insert($city);

    }
}
