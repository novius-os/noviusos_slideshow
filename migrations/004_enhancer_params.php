<?php
/**
 * NOVIUS OS - Web OS for digital communication
 *
 * @copyright  2011 Novius
 * @license    GNU Affero General Public License v3 or (at your option) any later version
 *             http://www.gnu.org/licenses/agpl-3.0.html
 * @link http://www.novius-os.org
 */

namespace Nos\Slideshow\Migrations;

use Nos\Tools_Wysiwyg;

class Enhancer_Params extends \Nos\Migration
{
    public function up()
    {
        $wysiwygs = \Nos\Model_Wysiwyg::find('all', array(
            'where' => array(
                array('wysiwyg_text', 'LIKE', '%noviusos_slideshow%')
            ),
        ));
        foreach ($wysiwygs as $wysiwyg) {
            static::wysiwygCleanup($wysiwyg);
        }
    }

    protected static function wysiwygCleanup($wysiwyg)
    {
        $content = $wysiwyg->wysiwyg_text;

        $callback = array(__CLASS__, 'enhancerContent');
        Tools_Wysiwyg::parse_enhancers(
            $content,
            function ($enhancer, $config, $tag) use (&$content, $callback) {
                $new_tag = call_user_func($callback, $tag, $config);
                $content = str_replace($tag, $new_tag, $content);
            }
        );

        if ($content != $wysiwyg->wysiwyg_text) {
            $wysiwyg->wysiwyg_text = $content;
            $wysiwyg->save();
        }
    }


    public static function enhancerContent($tag, $config)
    {
        $json = json_decode(
            strtr(
                $config,
                array(
                    '&quot;' => '"',
                )
            ),
            true
        );

        if (\Arr::key_exists($json, 'size')) {
            $format = \Arr::get($json, 'size') === 'grand' ? 'flexslider-big' : 'flexslider-small';
            $tag = str_replace('size&quot;:&quot;'.\Arr::get($json, 'size'), 'format&quot;:&quot;'.$format, $tag);
        }
        return $tag;
    }
}
