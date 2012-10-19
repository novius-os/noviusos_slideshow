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
        'icon64'  => 'static/apps/slideshow/img/slideshow-64.png',
    ),
    'launchers' => array( // = item ajouté dans l'admin
        'slideshow' => array(
            'name'    => 'Slideshow',
            'url'     => 'admin/slideshow/appdesk',
            'iconUrl' => 'static/apps/slideshow/img/slideshow-32.png',
            'icon64'  => 'static/apps/slideshow/img/slideshow-64.png',
            'application'   => 'slideshow'
        ),
    ),
    'enhancers' => array(
        'slideshow' => array( // = bloc insérable dans un wyysiwig
            'title' => 'Slideshow',
            'id'    => 'slideshow',
            'desc'  => '',
            'enhancer' => 'slideshow/slideshow/main', // pour l'affichage en front
            'iconUrl' => 'static/apps/contact/img/slideshow-16.png', // icon du wysiwig
            'previewUrl' => 'admin/slideshow/preview', // preview pour le wysiwig
            'dialog' => array(
                'contentUrl'    => 'admin/slideshow/popup',
                'width'         => 450,
                'height'        => 180,
                'ajax'          => true,
            ),
        ),
    )
);
