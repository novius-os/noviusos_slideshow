<?php

/**
 * Application Slideshow
 * @author Julien
 */

// TODO
// nos_media_link

namespace Arcom\Slideshow;

class Controller_Admin_Form extends \Nos\Controller_Admin_Application {

     public function action_add() {
        return $this->action_edit(0);
     }

     public function action_edit($slideshow_id = 0) {

        // Slideshows + images
        if ( $slideshow_id > 0 )
        {
            $slideshow  = Model_Slideshow::find($slideshow_id, array(
                'related' => array(
                    'images' => array(
                        'order_by' => array('slidimg_position' => 'asc'),
                    ),
                ),
            ));
        }

        if ( !empty($slideshow) )
        {
             $images        = $slideshow->images;
        }
        else
        {
            $slideshow      = Model_Slideshow::forge();
            $images         = array();
        }

        //\Debug::dump($images);

        // Vue
        $config = array(
            'slideshow_id' => array (
                'label' => 'ID: ',
                'form' => array(
                    'type' => 'hidden',
                ),
            ),
            'slideshow_title' => array(
                'label' => 'Titre',
                'form' => array(
                    'type' => 'text',
                ),
                'validation' => array(
                    'required',
                    'min_length' => array(2),
                ),
            ),
            'save' => array(
                'label' => 'Ajouter',
                'form' => array(
                    'type' => 'submit',
                    'tag'   => 'button',

                    'value' => 'Save',
                    'class' => 'primary',
                    'data-icon' => 'check',
                ),
            ),
        );

        $is_new = $slideshow->is_new();
        $fieldset = \Fieldset::build_from_config($config, $slideshow, array(
	        'success' => function($slideshow) use ($is_new, $images) {

	            // Sauvegarde des images
                if ( !empty($_POST['images']) )
                {
                    $form_images_ids = array();

                    $position = 1;
                    foreach ( $_POST['images'] as $image )
                    {
                        // Pas de media, pas de chocolat.
                        if ( empty($image['media_id']) )
                        {
                            continue;
                        }

                        $media_id = $image['media_id'];
                        unset($image['media_id']);

                        // Update
                        if ( !empty($image['slidimg_id']) && !empty($images[$image['slidimg_id']]) )
                        {
                            $values = array_diff_key($image, array(
                                'slidimg_id' => true
                            ));
                            $values = array_merge($values, array(
                                'slidimg_position'      => $position,
                            ));
                            $images[$image['slidimg_id']]->values($values);
                            $images[$image['slidimg_id']]->medias->image = $media_id;
                            $images[$image['slidimg_id']]->save();

                            $form_images_ids[] = $image['slidimg_id'];
                        }

                        // Insert
                        else
                        {
                            if ( isset($image['slidimg_id']) )
                            {
                                unset($image['slidimg_id']);
                            }
                            $values = array_merge($image, array(
                                'slidimg_position'      => $position,
                            ));
                            $image_model = Model_Image::forge($values);

                            $slideshow->images[] = $image_model;
                            //$slideshow->save();
                            //$image_model->save();

                            $image_model->medias->image = $media_id;

                            $form_images_ids[] = $image_model->slidimg_id;
                        }

                        $position++;
                    }

                    // Images a supprimer
                    //\Debug::dump(array_keys($images), $form_images_ids);
                    $images_to_be_deleted = array_diff(array_keys($images), $form_images_ids);
                    if ( !empty($images_to_be_deleted) ) {
                        \DB::delete('slideshow_image')->where('slidimg_id', 'IN', $images_to_be_deleted)->execute();
                        \DB::delete('nos_media_link')->where(array(
                            array('medil_from_table', '=', 'slideshow_image'),
                            array('medil_foreign_id', 'IN', $images_to_be_deleted),
                        ))->execute();
                    }

                    $slideshow->save();
                }

		        return array(
			        'notify' => ( $is_new ? 'Slideshow sucessfully added.' : 'Slideshow sucessfully updated.' ),
			        'dispatchEvent' => array(
                        //'reload.slideshow_edit',
                        'reload.slideshow'
                    ),
			        'replaceTab' => $is_new ? 'admin/slideshow/form/edit/'.$slideshow->slideshow_id : '',
		        );
	        }
        ));

        \Config::load('slideshow::slideshow', 'slideshow');

        return \View::forge('slideshow::admin/form', array(
            'fieldset'  => $fieldset,
            'slideshow' => $slideshow,
            'show_link' => \Config::get('slideshow.slides_with_link'),
            'images'    => $images,
        ), false);
	}



}
