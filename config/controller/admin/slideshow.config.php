<?php
/**
 * NOVIUS OS - Web OS for digital communication
 *
 * @copyright  2011 Novius
 * @license    GNU Affero General Public License v3 or (at your option) any later version
 *             http://www.gnu.org/licenses/agpl-3.0.html
 * @link http://www.novius-os.org
 */

Nos\I18n::current_dictionary('noviusos_slideshow::common');

return array(
    'controller_url'  => 'admin/noviusos_slideshow/slideshow',
    'model' => 'Nos\\Slideshow\\Model_Slideshow',
    'tab' => array(
        'iconUrl' => 'static/apps/noviusos_slideshow/img/slideshow-16.png',
        'labels' => array(
            'insert' => __('Add a slideshow'),
        ),
    ),
    'layout' => array(
        'title' => 'slideshow_title',
        'large' => true,
        'content' => array(
            'images' => array(
                'view' => 'noviusos_slideshow::admin/_form_content',
                'params' => array(

                ),
            ),
        ),
        'menu' => array(

        ),
        'save' => 'save',
    ),
    'fields' => array(
        'slideshow_id' => array (
            'label' => 'ID: ',
            'form' => array(
                'type' => 'hidden',
            ),
        ),
        'slideshow_title' => array(
            'label' => __('Title'),
            'form' => array(
                'type' => 'text',
            ),
            'validation' => array(
                'required',
                'min_length' => array(2),
            ),
        ),
        'save' => array(
            'label' => '',
            'form' => array(
                'type' => 'submit',
                'tag' => 'button',
                // Note to translator: This is a submit button
                'value' => __('Save'),
                'class' => 'primary',
                'data-icon' => 'check',
            ),
        ),
        'image' => array(
            'label' => '',
            'form' => array(
                'type' => 'hidden',
            ),
        ),
    ),
    'image_fields' => array(
        'image[slidimg_id][]'              => array(
            'form' => array(
                'type' => 'hidden',
            ),
            'populate' => function($item) {
                return $item->slidimg_id;
            }
        ),
        'image[media_id][]'                => array(
            'label' => __('Image'),
            'form'  => array(),
            'renderer' => '\Nos\Renderer_Media',
            'populate' => function($item) {
                if ($item->medias->image) {
                    return $item->medias->image->media_id;
                } else {
                    return '';
                }
            },
            'before_save' => function($item, $item_data) {
                $item->medias->image = $item_data['media_id'];
            }
        ),
        'image[slidimg_title][]'           => array(
            'label' => __('Title'),
            'populate' => function($item) {
                return $item->slidimg_title;
            }
        ),
        'image[slidimg_description][]'     => array(
            'label' => __('Description'),
            'populate' => function($item) {
                return $item->slidimg_description;
            }
        ),
        'image[slidimg_link_to_page_id][]' => array(
            'label' => __('Link to page'),
            'populate' => function($item) {
                return $item->slidimg_link_to_page_id;
            }
        ),
    ),
    'image_layout' => array(
        'standard' => array(
            'view'   => 'nos::form/accordion',
            'params' => array(
                //'classes' => 'notransform',
                'accordions' => array(
                    'main' => array(
                        'title'  => __('Properties'),
                        'fields' => array(
                            'image[slidimg_id][]',
                            'image[media_id][]',
                            'image[slidimg_title][]',
                            'image[slidimg_description][]',
                            'image[slidimg_link_to_page_id][]',
                        ),
                    ),
                ),
            ),
        ),
    ),
);