<?php
/**
 * NOVIUS OS - Web OS for digital communication
 *
 * @copyright  2011 Novius
 * @license    GNU Affero General Public License v3 or (at your option) any later version
 *             http://www.gnu.org/licenses/agpl-3.0.html
 * @link http://www.novius-os.org
 */

namespace Nos\Slideshow;

use Nos\Controller_Front_Application;

class Controller_Slideshow extends Controller_Front_Application
{
    /**
     * méthode qui gère l'affichage en front du slideshow
     * @param  array  $args tableau de paramètres, récupérés depuis le wysywig, en provenance de
     * la popup de config. dans notre cas, on attend slideshow_id et $size
     */
    public function action_main($args = array())
    {
        if (empty($args) || empty($args['slideshow_id'])) {
            return false;
        }

        $config = \Config::load('noviusos_slideshow::slideshow', true);

        $default_format = \Arr::get($config, 'default_format');
        $format = \Arr::get($args, 'format', $default_format);
        $format_config = \Arr::get($config, 'formats.'.$format,
            \Arr::get($config, 'formats.'.$default_format, array()));

        $slideshow = Model_Slideshow::find($args['slideshow_id'], array(
            'related' => array(
                'images' => array(
                'order_by' => array('slidimg_position' => 'asc'),
                ),
            ),
        ));

        $view = \Arr::get($this->config, 'view.index', null);
        if (!empty($view)) {
            \Log::deprecated('The use of view.index in Controller_Slideshow config file is deprecated, '.
                'use view key of your format in slideshow config file instead.', 'Chiba.2');

            $size_key = $format === 'flexslider-small' ? 'petit' : 'grand';
            return \View::forge($this->config['views']['index'], array(
                'slideshow' => $slideshow,
                'size_key'  => $size_key,
                'class'		=> \Arr::get($config, 'sizes.'.$size_key.'.class',
                    \Arr::get($format_config, 'config.class', 'slide-home')),
                'height'	=> \Arr::get($config, 'sizes.'.$size_key.'.img_height',
                    \Arr::get($format_config, 'config.height', '600')),
                'width'		=> \Arr::get($config, 'sizes.'.$size_key.'.img_width',
                    \Arr::get($format_config, 'config.width', '800')),
                'show_link' => \Arr::get($config, 'slides_with_link',
                    \Arr::get($format_config, 'config.slides_with_link', true)),
                'slides_preview' => \Arr::get($config, 'slides_preview',
                    \Arr::get($format_config, 'config.slides_preview', true)),
            ), false);
        } else {
            if (\Arr::key_exists($config, 'slides_with_link') ||
                \Arr::key_exists($config, 'slides_preview') ||
                \Arr::key_exists($config, 'sizes')) {
                \Log::deprecated('The struture of slideshow config file have changed, '.
                    'please update your extension.', 'Chiba.2');

                if (\Arr::key_exists($config, 'slides_with_link')) {
                    \Arr::set($format_config, 'config.slides_with_link', \Arr::get($config, 'slides_with_link'));
                }
                if (\Arr::key_exists($config, 'slides_preview')) {
                    \Arr::set($format_config, 'config.slides_preview', \Arr::get($config, 'slides_preview'));
                }
                $size_key = $format === 'flexslider-small' ? 'petit' : 'grand';
                if (\Arr::key_exists($config, 'sizes.'.$size_key.'.img_width')) {
                    \Arr::set($format_config, 'config.width', \Arr::get($config, 'sizes.'.$size_key.'.img_width'));
                }
                if (\Arr::key_exists($config, 'sizes.'.$size_key.'.img_height')) {
                    \Arr::set($format_config, 'config.height', \Arr::get($config, 'sizes.'.$size_key.'.img_height'));
                }
                if (\Arr::key_exists($config, 'sizes.'.$size_key.'.class')) {
                    \Arr::set($format_config, 'config.class', \Arr::get($config, 'sizes.'.$size_key.'.class'));
                }
            }

            return \View::forge(\Arr::get($format_config, 'view'), array(
                'slideshow' => $slideshow,
                'format' => $format,
                'config' => \Arr::get($format_config, 'config', array()),
            ), false);
        }
    }
}
