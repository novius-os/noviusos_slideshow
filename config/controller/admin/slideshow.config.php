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
                'value' => __('Save'),
                'class' => 'primary',
                'data-icon' => 'check',
            ),
        ),
    ),
);