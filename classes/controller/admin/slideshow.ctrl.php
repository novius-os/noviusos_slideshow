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

}
