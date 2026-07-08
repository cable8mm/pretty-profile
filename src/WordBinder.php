<?php

namespace Cable8mm\ViewTransformer;

/**
 * This class provides functions for string conversion.
 */
class WordBinder
{
    /**
     * The main content of WordPress is publishing (e.g., deleting tags, changing video tags to embedding tags, etc.)
     *
     * @param  string  $content  WordPress content.
     * @return string Wordpress widgets to HTML embed.
     *
     * @example WordBinder::view('<p>...[caption ... /]...</p>');
     * //=> <p><iframe width... /></p>
     */
    public static function view(string $content): string
    {
        // If there is a caption block in the code copied from WordPress, only the image is used.
        $content = preg_replace("/\[caption.+(<[^>]+>).+caption]/", '$1', $content);
        $content = preg_replace(
            "/\[embed\].+\/([^\?\&]+)[\?].*\[\/embed\]/",
            '<iframe width="100%" height="100%" src="https://www.youtube.com/embed/$1" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>',
            $content
        );

        return str_replace('<p>&nbsp;</p>', '', $content);
    }

    /**
     * Create HTML with banner code in nth phrases.
     *
     * @param  string  $content  HTML content.
     * @param  string  $bannerHtml  Banner HTML code.
     * @param  int  $nthPTag  nth phrases to insert into Banner HTML.
     * @return string HTML content with Banner HTML.
     *
     * @example WordBinder::addBanner('<p>...[caption ... /]...</p>', '<a href="...">...</a>', 2);
     * //=> Insert Banner HTML into second phrases.
     */
    public static function addBanner(string $content, string $bannerHtml, int $nthPTag = 0): string
    {
        if ($nthPTag === 0) {
            return $bannerHtml.$content;
        }

        $counter = 1;

        return preg_replace_callback('/<\/p>/', function ($m) use (&$counter, $bannerHtml, $nthPTag) {
            if ($counter++ === $nthPTag) {
                return '</p>'.$bannerHtml;
            }

            return $m[0];
        }, $content);
    }
}
