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

class Controller_Admin_Preview extends \Nos\Controller {


    public function action_index() {

        return $this->action_save();
    }

    public function action_save() {

        $params = array();
        $params['src'] = Model_Image::find()->where('slidimg_slideshow_id',$_POST['slideshow_id'])->get_one()->medias->image->get_public_path_resized(100, 40);
        $params['title'] = Model_Slideshow::find($_POST['slideshow_id'])->slideshow_title;
        $params['size'] = !empty($_POST['size']) ? $_POST['size'] : current(array_keys($config['sizes']));
        $body = array(
            'config'  => \Format::forge()->to_json($_POST),
            'preview' => \View::forge($this->config['views']['index'],$params)->render(),
        );

        $response = \Response::forge(\Format::forge()->to_json($body), 200, array(
            'Content-Type' => 'application/json',
        ));

        $response->send(true);
        exit();
    }
}
