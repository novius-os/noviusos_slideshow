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

class Controller_Admin_enhancer extends \Nos\Controller_Admin_Enhancer
{
    protected function config_build()
    {
        parent::config_build();
        $slideshows = Model_Slideshow::find('all', array(
            'where' => array(
                array('context', $this->placeholders['_parent_context']),
            ),
        ));
        if (!empty($slideshows)) {
            $this->config['fields']['slideshow_id']['form']['options'] = \Arr::pluck(
                $slideshows,
                'slideshow_title',
                'slideshow_id'
            );
        } else {
            unset($this->config['fields']);
            $this->config['popup']['view'] = 'noviusos_slideshow::admin/enhancer/blank_slate';
            $this->config['popup']['params'] = $this->placeholders;
            // Other's contexts count (this one is empty).
            $this->config['popup']['params']['slideshow_count'] = Model_Slideshow::count();
        }
    }

    public function action_save(array $args = null)
    {
        \Config::loadConfiguration('noviusos_slideshow::slideshow');
        $formats = \Config::get('noviusos_slideshow::slideshow.formats');

        if (empty($args)) {
            $args = $_POST;
        }

        $params = array();
        try {
            $slide = Model_Image::query()->where('slidimg_slideshow_id', $_POST['slideshow_id'])->get_one();
            if (empty($slide) || empty($slide->medias->image)) {
                throw new \Exception();
            }
            $params['src'] = $slide->medias->image->urlResized(100, 40);
        } catch (\Exception $e) {
            $params['src'] = 'static/apps/noviusos_slideshow/img/slideshow-64.png';
        }
        $params['title'] = Model_Slideshow::find($_POST['slideshow_id'])->slideshow_title;
        $format = (!empty($_POST['format']) ? $_POST['format'] : \Config::get('noviusos_slideshow::slideshow.default_format'));
        $params['format'] = \Arr::get($formats, $format.'.label', $format);
        $body = array(
            'config'  => $args,
            'preview' => \View::forge($this->config['preview']['view'], $params)->render(),
        );
        \Response::json($body);
    }
}
