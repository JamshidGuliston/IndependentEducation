<?php

namespace Database\Seeders;

use App\Models\Testcategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TestCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $table = Testcategory::all();
        if($table->count() == 0){
            Testcategory::create([
                'category_name' => 'Variantli test',
                'category_html_code' => '
                    <ol>
                        <li>Variant A</li>
                        <li>Variant B</li>
                        <li>Variant C</li>
                        <li>Variant D</li>
                    </ol>
                ',
            ]);

            Testcategory::create([
                'category_name' => 'Fayl yuboradigan test',
                'category_html_code' => '
                    <input type="file" id="myFile" name="filename">
                ',
            ]);

            Testcategory::create([
                'category_name' => 'Yozish imkonini beruvchi test',
                'category_html_code' => '
                    <input type="file" id="myFile" name="filename">
                ',
            ]);
            
        }
    }
}
