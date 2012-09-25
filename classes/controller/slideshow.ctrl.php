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
        if ( empty($args) || empty($args['slideshow_id']) ) {
            return false;
        }

        $config = \Config::load('slideshow::slideshow', true);

        if (empty($args['size'])) {
            $size = current($config['sizes']);
        } else {
            $size = $config['sizes'][$args['size']];
        }
        $slideshow = Model_Slideshow::find($args['slideshow_id'], array(
            'related' => array(
                'images' => array(
                'order_by' => array('slidimg_position' => 'asc'),
                ),
            ),
        ));

        return \View::forge($this->config['views']['index'], array(
            'slideshow' => $slideshow,
            'class'		=> $size['class'],
            'height'	=> $size['img_height'],
            'width'		=> $size['img_width'],
            'show_link' => $config['slides_with_link'],
            'slides_preview' => $config['slides_preview']
        ), false);
    }
}
