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

use Nos\User\Permission;

class Controller_Admin_Slideshow extends \Nos\Controller_Admin_Crud
{
    protected $to_delete = array();
    protected $images_fieldset = array();
    protected $images_data = array();

    public static function _init()
    {
        // Used to retrieve the slides_with_link config
        \Config::load('noviusos_slideshow::slideshow', true);
    }

    public function before_save($item, $data)
    {
        $images_data = \Input::post('image', array());

        $this->to_delete = array_diff(
            array_keys($item->images),
            \Arr::pluck($images_data, 'slidimg_id')
        );

        foreach ($images_data as $img_id => $image_data) {
            if (empty($image_data['media_id'])) {
                // Only unset, don't delete because it's still shown in the interface and the user can pick another image instead
                unset($item->images[$img_id]);
                continue;
            }
            $this->images_fieldset[$img_id] = \Fieldset::build_from_config($this->config['image_fields'], array(
                'save' => false,
            ));
            $this->images_data[$img_id] = $image_data;
            $item->images[$img_id] = Model_Image::find($img_id);
        }
    }

    public function save($item, $data)
    {
        $return = parent::save($item, $data);
        // Save slideshow images
        foreach ($this->images_fieldset as $img_id => $fieldset) {
            if (in_array($img_id, $this->to_delete)) {
                continue;
            }
            $img = $item->images[$img_id];
            $img->slidimg_slideshow_id = $item->slideshow_id;
            $fieldset->validation()->run($this->images_data[$img_id]);
            $fieldset->triggerComplete($img, $fieldset->validated());
        }
        // Delete slides
        foreach ($this->to_delete as $img_id) {
            $item->images[$img_id]->delete();
            unset($item->images[$img_id]);
        }
        $this->to_delete = array();
        return $return;
    }

    public function action_duplicate($id = null)
    {
        try {
            /**
             * @var $slider Model_Slideshow
             */
            $slider = $this->crud_item($id);
            $contexts = Permission::contexts();
            $duplicateContext = (string) \Input::post('duplicate_context');
            // Check context permission with selected context target
            if (!empty($duplicateContext) && !array_key_exists($duplicateContext, $contexts)) {
                throw new \Exception(__('Invalid context selected.'));
            }
            // No asking popup if only 1 context / duplicate if valid target context was chosen
            if (count($contexts) === 1 || !empty($duplicateContext)) {
                $context = !empty($duplicateContext) ? $duplicateContext : $slider->slideshow_context;
                $slider->duplicate($context);
                \Response::json(array(
                    'dispatchEvent' => array(
                        'name' => Model_Slideshow::class,
                        'action' => 'insert',
                        'context' => $context,
                    ),
                    'notify' => __('Here you are! The slideshow has just been duplicated.'),
                ));
            } else {
                \Response::json(array(
                    'action' => array(
                        'action' => 'nosDialog',
                        'dialog' => array(
                            'ajax' => true,
                            'contentUrl' => 'admin/noviusos_slideshow/slideshow/popup_duplicate/'.$id,
                            'title' => strtr(__('Duplicating the slideshow "{{title}}"'), array(
                                '{{title}}' => \Str::truncate($slider->title_item(), 40),
                            )),
                            'width' => 500,
                            'height' => 200,
                        ),
                    ),
                ));
            }
        } catch (\Exception $e) {
            $this->send_error($e);
        }
    }

    /**
     * Return popup content to ask the target context of duplication
     *
     * @param null $id : the ID of Model_Slideshow to duplicate
     * @return \Fuel\Core\View
     */
    public function action_popup_duplicate($id = null)
    {
        /**
         * @var $slider Model_Slideshow
         */
        $slider = $this->crud_item($id);
        $contexts_list = array_keys(Permission::contexts());
        return \View::forge('noviusos_slideshow::admin/popup_duplicate', array(
            'item' => $slider,
            'action' => 'admin/noviusos_slideshow/slideshow/duplicate/'.$id,
            'contexts_list' => $contexts_list,
        ), false);
    }

    public function action_image_fields()
    {
        $image = $this->create_image_db();
        $slide = $this->action_render_image_fieldset($image);
        \Response::json(array(
            'slide' => $slide,
        ));
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

        \Event::trigger('noviusos_slideshow.image_fieldset');

        \Event::trigger_function('noviusos_slideshow.image_fieldset', array(array('item' => &$item, 'replaces' => &$replaces)));

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
