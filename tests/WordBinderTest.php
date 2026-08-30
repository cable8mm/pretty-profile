<?php

namespace EscCompany\ViewTransformer\Tests;

use EscCompany\ViewTransformer\WordBinder;
use PHPUnit\Framework\TestCase;

class WordBinderTest extends TestCase
{
    public function test_expect_caption_view(): void
    {
        $view = WordBinder::view('[caption width=80]<video src="https://test.com/video/2343jj234">[/caption]');

        $this->assertEquals('<video src="https://test.com/video/2343jj234">', $view);
    }

    public function test_expect_embed_view(): void
    {
        $view = WordBinder::view('[embed]https://www.youtube.com/watch?v=XsJ9GFGkEOk[/embed]');

        $this->assertEquals('<iframe width="100%" height="100%" src="https://www.youtube.com/embed/watch" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>', $view);
    }

    public function test_expect_add_banner(): void
    {
        $bannerHtml = '<img src="/images/banner/banner_donation_720x200.png">';
        $view = WordBinder::addBanner('<h1>title</h1><p>Sentence1</p><p>Sentence2</p><p>Sentence3</p><p>Sentence4</p>', $bannerHtml);
        $this->assertEquals('<img src="/images/banner/banner_donation_720x200.png"><h1>title</h1><p>Sentence1</p><p>Sentence2</p><p>Sentence3</p><p>Sentence4</p>', $view);

        $view = WordBinder::addBanner('<h1>title</h1>'.PHP_EOL.'<p>Sentence1</p>'.PHP_EOL.'<p>Sentence2</p><p>Sentence3</p><p>Sentence4</p>', $bannerHtml, 1);
        $this->assertEquals('<h1>title</h1>'.PHP_EOL.'<p>Sentence1</p><img src="/images/banner/banner_donation_720x200.png">'.PHP_EOL.'<p>Sentence2</p><p>Sentence3</p><p>Sentence4</p>', $view);

        $view = WordBinder::addBanner('<h1>title</h1>'.PHP_EOL.'<p>Sentence1</p>'.PHP_EOL.'<p>Sentence2</p><p>Sentence3</p><p>Sentence4</p>', $bannerHtml, 2);
        $this->assertEquals('<h1>title</h1>'.PHP_EOL.'<p>Sentence1</p>'.PHP_EOL.'<p>Sentence2</p><img src="/images/banner/banner_donation_720x200.png"><p>Sentence3</p><p>Sentence4</p>', $view);
    }
}
