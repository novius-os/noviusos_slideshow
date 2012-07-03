<?php

/**
 * Application Diaporama
 * @author Julien
 */

// TODO
// nos_media_link

namespace Arcom\Diaporama;

class Controller_Admin_Form extends \Nos\Controller_Admin_Application {

     public function action_add() {
        return $this->action_edit(0);
     }

     public function action_edit($diaporama_id = 0) {

        // Diaporamas + images
        if ( $diaporama_id > 0 )
        {
            $diaporama  = Model_Diaporama::find($diaporama_id, array(
                'related' => array(
                    'images' => array(
                        'order_by' => array('diapimg_position' => 'asc'),
                    ),
                ),
            ));
        }

        if ( !empty($diaporama) )
        {
             $images        = $diaporama->images;
        }
        else
        {
            $diaporama      = Model_Diaporama::forge();
            $images         = array();
        }

        //\Debug::dump($images);

        // Vue
        $config = array(
            'diaporama_id' => array (
                'label' => 'ID: ',
                'form' => array(
                    'type' => 'hidden',
                ),
            ),
            'diaporama_nom' => array(
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

        $is_new = $diaporama->is_new();
        $fieldset = \Fieldset::build_from_config($config, $diaporama, array(
	        'success' => function($diaporama) use ($is_new, $images) {

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
                        if ( !empty($image['diapimg_id']) && !empty($images[$image['diapimg_id']]) )
                        {
                            $values = array_diff_key($image, array(
                                'diapimg_id' => true
                            ));
                            $values = array_merge($values, array(
                                'diapimg_position'      => $position,
                            ));
                            $images[$image['diapimg_id']]->values($values);
                            $images[$image['diapimg_id']]->medias->image = $media_id;
                            $images[$image['diapimg_id']]->save();

                            $form_images_ids[] = $image['diapimg_id'];
                        }

                        // Insert
                        else
                        {
                            if ( isset($image['diapimg_id']) )
                            {
                                unset($image['diapimg_id']);
                            }
                            $values = array_merge($image, array(
                                'diapimg_position'      => $position,
                            ));
                            $image_model = Model_Image::forge($values);

                            $diaporama->images[] = $image_model;
                            //$diaporama->save();
                            //$image_model->save();

                            $image_model->medias->image = $media_id;

                            $form_images_ids[] = $image_model->diapimg_id;
                        }

                        $position++;
                    }

                    // Images a supprimer
                    //\Debug::dump(array_keys($images), $form_images_ids);
                    $images_to_be_deleted = array_diff(array_keys($images), $form_images_ids);
                    if ( !empty($images_to_be_deleted) ) {
                        \DB::delete('diaporama_image')->where('diapimg_id', 'IN', $images_to_be_deleted)->execute();
                        \DB::delete('nos_media_link')->where(array(
                            array('medil_from_table', '=', 'diaporama_image'),
                            array('medil_foreign_id', 'IN', $images_to_be_deleted),
                        ))->execute();
                    }

                    $diaporama->save();
                }

		        return array(
			        'notify' => ( $is_new ? 'Diaporama sucessfully added.' : 'Diaporama sucessfully updated.' ),
			        'dispatchEvent' => array(
                        //'reload.diaporama_edit',
                        'reload.diaporama'
                    ),
			        'replaceTab' => $is_new ? 'admin/diaporama/form/edit/'.$diaporama->diaporama_id : '',
		        );
	        }
        ));

        \Config::load('diaporama::diaporama', 'diaporama');

        return \View::forge('diaporama::admin/form', array(
            'fieldset'  => $fieldset,
            'diaporama' => $diaporama,
            'show_link' => \Config::get('diaporama.slides_with_link'),
            'images'    => $images,
        ), false);
	}



}
