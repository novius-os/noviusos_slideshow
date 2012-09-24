<?php
/**
 * NOVIUS OS - Web OS for digital communication
 *
 * @copyright  2011 Novius
 * @license    GNU Affero General Public License v3 or (at your option) any later version
 *             http://www.gnu.org/licenses/agpl-3.0.html
 * @link http://www.novius-os.org
 */

namespace Arcom\Slideshow;

use Nos\Controller;
use Fuel\Core\View;

class Controller_Admin_Popup extends \Nos\Controller {

    public function action_index() {
        $slideshows = Model_Slideshow::find('all', array(
            'order_by' => array('slideshow_title' => 'asc'),
        ));

        \Config::load('slideshow::slideshow', 'slideshow');
        $sizes = \Config::get('slideshow.sizes');

        return View::forge('slideshow::admin/popup', array(
            'slideshows' => $slideshows,
            'sizes' => $sizes
        ));
    }
}
