<?php
/**
 * NOVIUS OS - Web OS for digital communication
 *
 * @copyright  2011 Novius
 * @license    GNU Affero General Public License v3 or (at your option) any later version
 *             http://www.gnu.org/licenses/agpl-3.0.html
 * @link http://www.novius-os.org
 */

namespace Arcom\Diaporama;

use Nos\Controller;
use Fuel\Core\View;

class Controller_Admin_Popup extends \Nos\Controller {

    public function action_index() {
        $diaporamas = Model_Diaporama::find('all', array(
            'order_by' => array('diaporama_nom' => 'asc'),
        ));

        \Config::load('diaporama::diaporama', 'diaporama');
        $sizes = \Config::get('diaporama.sizes');

        return View::forge('diaporama::admin/popup', array(
            'diaporamas' => $diaporamas,
            'sizes' => $sizes
        ));
    }
}
