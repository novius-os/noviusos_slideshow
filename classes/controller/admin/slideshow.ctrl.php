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

use Fuel\Core\Arr;

class Controller_Admin_Slideshow extends \Nos\Controller_Admin_Crud
{
    protected static $to_delete = array();

    public function before_save($item, $data)
    {
        $field_names = array();
        foreach ($this->config['image_fields'] as $name => $img_data) {
            if (!empty($img_data['dont_save']) || (!empty($img_data['form']['type']) && $img_data['form']['type'] == 'submit')) {
                continue;
            }
            $field_names[] = $name;
        }
        // null is for the first argument of array_map() to transpose the matrix
        $values = array(null);
        $images = array();
        foreach ($field_names as &$name) {
            $values[] = \Input::post('image.'.$name);
        }
        // If there are values
        if (!empty($values[1])) {
            foreach (call_user_func_array('array_map', $values) as $value) {
                $images[] = array_combine(array_values($field_names), $value);
            }
        }

        static::$to_delete = array_diff(
            array_keys($item->images),
            Arr::pluck($images, 'slidimg_id')
        );

        foreach ($images as $index => $img_data) {
            $img_id = $img_data['slidimg_id'];
            if (empty($img_data['media_id'])) {
                // Only unset, don't delete because it's still shown in the interface and the user can pick another image instead
                unset($item->images[$img_id]);
                continue;
            }
            $img_data['slidimg_position'] = $index + 1;
            $model_img = Model_Image::find($img_id);
            foreach($this->config['image_fields'] as $name => $field_config) {
                if ($field_config['before_save'] && is_callable($field_config['before_save'])) {
                    $before_save = $field_config['before_save'];
                    $before_save($model_img, $img_data);
                }
            }
            unset($img_data['slidimg_id']);
            $model_img->set($img_data);
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

    public function action_render_image_fieldset($item, $view = null)
    {
        static $auto_id_increment = 1;
        // This action is not available from the browser. Only internal requests are authorised.
        $view = 'noviusos_slideshow::admin/layout';
        $fieldset = \Fieldset::build_from_config($this->config['image_fields'], $item, array('save' => false));
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
            $replaces[$name] = "image[$name][{$item->slidimg_id}]";
        }
        $return = (string) \View::forge($view, $image_view_params, false)->render().$fieldset->build_append();

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
