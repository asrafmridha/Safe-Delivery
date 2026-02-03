<?php

namespace Database\Seeders;

use App\Models\FrequentlyAskQuestion;
use Illuminate\Database\Seeder;

class FrequentlyAskQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        FrequentlyAskQuestion::truncate();
        FrequentlyAskQuestion::create(
            [
                'question'          => 'Question Title ',
                'answer'            => 'Question Answer',
                'created_admin_id'  => 1,
            ]
        );
    }
}
