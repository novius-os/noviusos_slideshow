<?php
/**
 * NOVIUS OS - Web OS for digital communication
 *
 * @copyright  2011 Novius
 * @license    GNU Affero General Public License v3 or (at your option) any later version
 *             http://www.gnu.org/licenses/agpl-3.0.html
 * @link http://www.novius-os.org
 */

return array(
    'name'      => 'Slideshows',
    'namespace' => 'Nos\Slideshow',
    'version' => '5.0 (Elche)',
    'provider'  => array(
        'name'  => 'Novius OS',
    ),
    'permission' => array(
    ),
    'i18n_file' => 'noviusos_slideshow::metadata',
    'launchers' => array(
        'noviusos_slideshow' => array(
            'name'    => 'Slideshows',
            'action' => array(
                'action' => 'nosTabs',
                'tab' => array(
                    'url' => 'admin/noviusos_slideshow/appdesk',
                ),
            ),
        ),
    ),
    'enhancers' => array(
        'noviusos_slideshow' => array(
            'title' => 'Slideshow',
            'id'    => 'slideshow',
            'desc'  => '',
            'enhancer' => 'noviusos_slideshow/slideshow/main',
            'iconUrl' => '/static/apps/noviusos_slideshow/img/slideshow-16.png',
            'previewUrl' => 'admin/noviusos_slideshow/enhancer/preview',
            'dialog' => array(
                'contentUrl'    => 'admin/noviusos_slideshow/enhancer/popup',
                'width'         => 450,
                'height'        => 300,
                'ajax'          => true,
            ),
        ),
    ),
    'icons' => array(
        16 => 'static/apps/noviusos_slideshow/img/slideshow-16.png',
        32 => 'static/apps/noviusos_slideshow/img/slideshow-32.png',
        64 => 'static/apps/noviusos_slideshow/img/slideshow-64.png',
    ),
);
