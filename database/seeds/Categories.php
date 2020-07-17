<?php

use Illuminate\Database\Seeder;

class Categories extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->insert([
            'name' => Str::random(10),
            'eng_name' => Str::random(10),
            'rus_name' => Str::random(10),

        ]);
    }
}
