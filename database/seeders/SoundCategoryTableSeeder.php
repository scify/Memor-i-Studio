<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;

class SoundCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sound_category')->delete();
        //insert some dummy records
        DB::table('sound_category')->insert(array(
            array('category'=>'card_sounds','description'=>'The sound that plays when the card is opened'),
            array('category'=>'card_description_sounds','description'=>'Every card has a descriptive sound that plays when the card is won'),
            array('category'=>'storyline_audios','description'=>'While the player progresses through levels, there is a story sound playing each time the user completes a level and starts a new one, even if they play the same level multiple times.'),
            array('category'=>'level_intro_sounds','description'=>'Each game level has an introductory sound'),
            array('category'=>'level_name_sounds','description'=>'The sounds that is played when the user visits the level button (For example "Two times three"'),
            array('category'=>'fun_factor_sounds','description'=>'Optional sounds that add fun to the game. Played at every other level, after the level intro sounds'),
            array('category'=>'end_level_starting_sounds','description'=>'When a level ends, there is a sound just before the time score. Eg "Congratulations! You finished in...'),
            array('category'=>'end_level_ending_sounds','description'=>'When a level ends, after the end_level_starting_sound there might be another sound like "I know I could trust you!"'),
            array('category'=>'game_instructions','description'=>'Game instruction sounds (tutorial sounds, etc)'),
            array('category'=>'letters','description'=>'Letters from A-H. Used to identify a row of a tile (For example A1)'),
            array('category'=>'numbers','description'=>'Numbers from 1-60. Used to identify a column of a tile (For example A1), and for high scores (1 minute and 42 seconds)'),
            array('category'=>'miscellaneous','description'=>'Helper sounds, like "minute", "minutes", "and", "seconds", etc')
        ));
    }
}
