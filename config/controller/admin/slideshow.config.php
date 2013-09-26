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
        'image' => array(
            'label' => '',
            'form' => array(
                'type' => 'hidden',
            ),
        ),
    ),
    'image_fields' => array(
        'slidimg_id' => array(
            'form' => array(
                'type' => 'hidden',
            ),
        ),
        'media_id' => array(
            'label' => __('Image:'),
            'form'  => array(
                'type' => 'text',
            ),
            'template' => "\t\t<span class=\"{error_class}\">{label}&nbsp;<span style=\"font-size: 1.5em; line-height: 1em; font-weight: bold\">*</span></span>\n\t\t<br />\n\t\t<span class=\"{error_class}\">{field} {error_msg}</span>\n",
            'renderer' => 'Nos\Media\Renderer_Media',
            'renderer_options' => array(
                'inputFileThumb' => array(
                    'title' => __('Image'),
                    'allowDelete' => false,
                ),
            ),
            'populate' => function ($item) {
                if ($item->medias->image) {
                    return $item->medias->image->media_id;
                } else {
                    return '';
                }
            },
            'before_save' => function ($item, $data) {
                $item->medias->image = $data['media_id'];
            }
        ),
        'slidimg_title' => array(
            'label' => __('Slide name:'),
        ),
        'slidimg_description' => array(
            'label' => __('Description:'),
        ),
        'slidimg_position' => array(
            'dont_populate' => true,
            'before_save' => function ($item, $data) {
                static $position = 1;
                $item->slidimg_position = $position++;
            },
        ),
        'slidimg_link_to_page_id' => array(
            'label' => __('Links to:'),
            'renderer' => 'Nos\Slideshow\Renderer_Page',
            'show_when' => function () {
                return \Config::get('noviusos_slideshow::slideshow.slides_with_link', false);
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
                            'slidimg_id',
                            'media_id',
                            'slidimg_title',
                            'slidimg_description',
                            'slidimg_link_to_page_id',
                        ),
                    ),
                ),
            ),
        ),
    ),
);
