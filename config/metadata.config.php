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
    'name'      => 'Slideshow',
    'namespace' => 'Nos\Slideshow',
    'version'   => '0.2',
    'icon16'  => 'static/apps/noviusos_slideshow/img/slideshow-16.png',
    'icon64'  => 'static/apps/noviusos_slideshow/img/slideshow-64.png',
    'provider'  => array(
        'name'  => 'Novius OS',
    ),
    'permission' => array(
    ),
    'launchers' => array( // = item ajouté dans l'admin
        'noviusos_slideshow' => array(
            'name'    => 'Slideshow',
            'action' => array(
                'action' => 'nosTabs',
                'tab' => array(
                    'url' => 'admin/noviusos_slideshow/appdesk',
                ),
            ),
        ),
    ),
    'enhancers' => array(
        'noviusos_slideshow' => array( // = bloc insérable dans un wyysiwig
            'title' => 'Slideshow',
            'id'    => 'slideshow',
            'desc'  => '',
            'enhancer' => 'noviusos_slideshow/slideshow/main', // pour l'affichage en front
            'iconUrl' => 'static/apps/contact/img/slideshow-16.png', // icon du wysiwig
            'previewUrl' => 'admin/noviusos_slideshow/preview', // preview pour le wysiwig
            'dialog' => array(
                'contentUrl'    => 'admin/noviusos_slideshow/popup',
                'width'         => 450,
                'height'        => 180,
                'ajax'          => true,
            ),
        ),
    ),
    'icons' => array(
        64 => '/static/apps/noviusos_slideshow/img/slideshow-64.png',
        32 => '/static/apps/noviusos_slideshow/img/slideshow-32.png',
        16 => '/static/apps/noviusos_slideshow/img/slideshow-16.png',
    ),
);
