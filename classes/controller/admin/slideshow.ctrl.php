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

class Controller_Admin_Slideshow extends \Nos\Controller_Admin_Crud
{
    protected static $to_delete = array();

    public static function _init()
    {
        // Used to retrieve the slides_with_link config
        \Config::load('noviusos_slideshow::slideshow', true);
    }

    public function before_save($item, $data)
    {
        $images = \Input::post('image', array());

        // Empty checkboxes should be populated with the 'empty' key of the configuration array
        // We need to do it manually here, since we're not using the Fieldset class
        foreach ($this->config['image_fields'] as $name => $config) {
            if (isset($config['form']['type']) && $config['form']['type'] == 'checkbox') {
                foreach ($images as $index => $image) {
                    if (empty($image[$name]) && isset($config['form']['empty'])) {
                        $fields[$index][$name] = $config['form']['empty'];
                    }
                }
            }
        }

        static::$to_delete = array_diff(
            array_keys($item->images),
            \Arr::pluck($images, 'slidimg_id')
        );

        $position = 1;
        foreach ($images as $image) {
            $img_id = $image['slidimg_id'];
            if (empty($image['media_id'])) {
                // Only unset, don't delete because it's still shown in the interface and the user can pick another image instead
                unset($item->images[$img_id]);
                continue;
            }
            $image['slidimg_position'] = $position++;
            $model_img = Model_Image::find($img_id);
            foreach ($this->config['image_fields'] as $config) {
                if (isset($config['before_save']) && is_callable($config['before_save'])) {
                    $before_save = $config['before_save'];
                    $before_save($model_img, $image);
                }
            }
            unset($image['slidimg_id']);
            $model_img->set($image);
            $item->images[$img_id] = $model_img;
        }
    }

    public function save($item, $data)
    {
        $return = parent::save($item, $data);
        foreach ($item->images as $img) {
            if (in_array($img->slidimg_id, static::$to_delete)) {
                continue;
            }
            $img->slidimg_slideshow_id = $item->slideshow_id;
            $img->save();
        }
        foreach (static::$to_delete as $img_id) {
            $item->images[$img_id]->delete();
            unset($item->images[$img_id]);
        }
        return $return;
    }

    public function action_image_fields()
    {
        $response = array();
        $image = $this->create_image_db();
        $response['fieldset'] = $this->action_render_image_fieldset($image);

        $response['id'] = $image->slidimg_id;

        $response['conf'] = $this->config['image_fields'];
        \Response::json($response);
    }

    public function action_render_image_fieldset($item)
    {
        static $auto_id_increment = 1;

        $fieldset = \Fieldset::build_from_config($this->config['image_fields'], $item, array('save' => false, 'auto_id' => false));
        // Override auto_id generation so it don't use the name (because we replace it below)
        $auto_id = uniqid('auto_id_');
        foreach ($fieldset->field() as $field) {
            if ($field->get_attribute('id') == '') {
                $field->set_attribute('id', $auto_id.$auto_id_increment++);
            }
        }

        $image_view_params = array(
            'fieldset' => $fieldset,
            'layout' => $this->config['image_layout'],
        );
        $image_view_params['view_params'] = &$image_view_params;

        // Replace name="image[slidimg_description][]" "with image[slidimg_description][12345]" <- add slide_ID here
        $replaces = array();
        foreach ($this->config['image_fields'] as $name => $image_config) {
            $replaces[$name] = "image[{$item->slidimg_id}][$name]";
        }
        $return = (string) \View::forge('noviusos_slideshow::admin/layout', $image_view_params, false)->render().$fieldset->build_append();

        return strtr($return, $replaces);
    }

    public function create_image_db($data = array())
    {
        $default_data = array(
            'slidimg_slideshow_id' => '0',
            'slidimg_position' => 0,
            'slidimg_title' => '',
            'slidimg_description' => '',
        );
        $model_image = Model_Image::forge(array_merge($default_data, $data), true);
        $model_image->save();
        return $model_image;
    }
}
