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
    public function save($item, $data)
    {
        // Sauvegarde des images
        if ( !empty($_POST['images']) ) {
            $images = $item->images;
            $form_images_ids = array();

            $position = 1;
            foreach ($_POST['images'] as $image) {
                // Pas de media, pas de chocolat.
                if ( empty($image['media_id']) ) {
                    continue;
                }

                $media_id = $image['media_id'];
                unset($image['media_id']);

                // Update
                if ( !empty($image['slidimg_id']) && !empty($images[$image['slidimg_id']]) ) {
                    $values = array_diff_key($image, array(
                        'slidimg_id' => true
                    ));
                    $values = array_merge($values, array(
                        'slidimg_position'      => $position,
                    ));
                    $images[$image['slidimg_id']]->set($values);
                    $images[$image['slidimg_id']]->medias->image = $media_id;
                    $images[$image['slidimg_id']]->save();

                    $form_images_ids[] = $image['slidimg_id'];
                }

                // Insert
                else {
                    if ( isset($image['slidimg_id']) ) {
                        unset($image['slidimg_id']);
                    }
                    $values = array_merge($image, array(
                        'slidimg_position'      => $position,
                    ));
                    $image_model = Model_Image::forge($values);
                    $item->images[] = $image_model;
                    $image_model->medias->image = $media_id;

                    $form_images_ids[] = $image_model->slidimg_id;
                }

                $position++;
            }

            // Images a supprimer
            $images_to_be_deleted = array_diff(array_keys($images), $form_images_ids);
            if ( !empty($images_to_be_deleted) ) {
                \DB::delete(Model_Image::table())->where('slidimg_id', 'IN', $images_to_be_deleted)->execute();
                \DB::delete('nos_media_link')->where(array(
                    array('medil_from_table', '=', 'slideshow_image'),
                    array('medil_foreign_id', 'IN', $images_to_be_deleted),
                ))->execute();
            }

            $item->save();
        }

        return parent::save($item, $data);
    }

    public function action_image_fields()
    {

        $response = array();
        $fields = array();
        $image = $this->create_image_db();
        foreach ($this->config['image_fields'] as $name => $field_data) {
            /*$field = $this->create_image_db($field_data);
            $fields[] = $this->action_render_field($field);*/
            $response[$name] = $field_data;
        }
        $response['id'] = $image->slidimg_id;
        \Response::json($response);
    }

    public function action_render_field($item, $view = null)
    {
        // This action is not available from the browser. Only internal requests are authorised.
        if (!empty($view) && !\Request::is_hmvc()) {
            exit();
        } else {
            $view = 'noviusos_form::admin/layout';
        }

        if ($item->field_type == 'page_break') {
            return $this->render_page_break($item);
        }

        $fieldset = \Fieldset::build_from_config($this->config['fields_config'], $item, array('save' => false));
        $fields_view_params = array(
            'layout' => $this->config['fields_layout'],
            'fieldset' => $fieldset,
        );
        $fields_view_params['view_params'] = &$fields_view_params;
        return \View::forge($view, $fields_view_params, false);
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
