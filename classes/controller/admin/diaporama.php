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

class Controller_Admin_Diaporama extends Controller {

    public function action_delete_confirm($id) {

        $success = false;

        $diaporama = Model_Diaporama::find($id);
        if ($diaporama && $diaporama instanceof Model_Diaporama) {
            $diaporama->delete();
            $success = true;
        }

        $this->response(array(
            'notify'    => __('The diaporama has successfully been deleted !'),
            'success'   => $success,
        ));
    }
}
