<?php

namespace Database\Seeders;

use App\Models\Level;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LevelsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $table = Level::all();
        if($table->count() == 0){
            Level::create([
                'level_name' => 'oson',
                'level_degree' => '55%',
            ]);

            Level::create([
                'level_name' => "o'rta",
                'level_degree' => '85%',
            ]);

            Level::create([
                'level_name' => "qiyin",
                'level_degree' => '95%',
            ]);
        }
    }
}
