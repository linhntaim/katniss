<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-09
 * Time: 17:51
 */

use Illuminate\Database\Seeder;
use Katniss\Everdeen\Models\Post;

class PageSeeder extends Seeder
{
    public function run()
    {
        $generator = Faker\Factory::create();
        $loop = 36500;
        for ($i = 0; $i < $loop; ++$i) {
            $post = Post::create([
                'user_id' => 2,
                'template' => null,
                'featured_image' => $generator->imageUrl(),
                'type' => Post::TYPE_PAGE,
            ]);
            $trans = $post->translateOrNew('--');
            $trans->post_id = $post->id;
            $trans->title = ucfirst($generator->words(10, true));
            $trans->slug = str_slug($trans->title);
            $trans->description = $generator->sentence(10);
            $trans->content = wrapContent($generator->paragraphs(10, true),'<p>','</p>')
                .  wrapContent($generator->paragraphs(10, true),'<p>','</p>')
                .  wrapContent($generator->paragraphs(10, true),'<p>','</p>');
            $trans->save();
        }
    }
}