<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-09
 * Time: 17:51
 */

use Illuminate\Database\Seeder;
use Katniss\Everdeen\Models\Category;
use Katniss\Everdeen\Models\Post;

class ArticleSeeder extends Seeder
{
    public function run()
    {
        $generator = Faker\Factory::create();

        $loopCategory = 20;
        $categoryIds = [];
        for ($i = 0; $i < $loopCategory; ++$i) {
            $category = Category::create([
                'type' => Category::TYPE_ARTICLE
            ]);
            $categoryIds[] = $category->id;

            $trans = $category->translateOrNew('--');
            $trans->category_id = $category->id;
            $trans->name = ucfirst($generator->words(5, true));
            $trans->slug = str_slug($trans->name);
            $trans->description = $generator->sentence(10);
            $trans->save();
        }

        $loopArticle = 36500;
        for ($i = 0; $i < $loopArticle; ++$i) {
            $post = Post::create([
                'user_id' => 2,
                'template' => null,
                'featured_image' => $generator->imageUrl(),
                'type' => Post::TYPE_ARTICLE,
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
            $post->categories()->attach($generator->randomElements($categoryIds, random_int(0, $loopCategory - 1)));
        }
    }
}