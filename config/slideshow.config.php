<?php
/**
 * NOVIUS OS - Web OS for digital communication
 *
 * @copyright  2011 Novius
 * @license    GNU Affero General Public License v3 or (at your option) any later version
 *             http://www.gnu.org/licenses/agpl-3.0.html
 * @link       http://www.novius-os.org
 */

return array(
    'default_format' => 'flexslider-big',

    'formats'        => array(
        'flexslider-big' => array(
            'view' => 'noviusos_slideshow::flexslider/slideshow',
            'label' => __('Big Flexslider v1.8'),
            'config' => array(
                'slides_with_link' => true,
                'slides_preview' => true,
                'width' => '800',
                'height' => '600',
                'class' => 'slide-home',
                'js' => array(
                    'jquery' => 'static/apps/noviusos_slideshow/js/jquery.min.js',
                    'flexslider' => 'static/apps/noviusos_slideshow/js/jquery.flexslider.js',
                    'flexpreview' => 'static/apps/noviusos_slideshow/js/jquery.novius_flexpreview.js',
                ),
                'css' => array(
                    'flexslider' => 'static/apps/noviusos_slideshow/css/flexslider.css',
                    'flexpreview' => 'static/apps/noviusos_slideshow/css/flexpreview.css',
                ),
            ),
        ),
        'flexslider-small' => array(
            'view' => 'noviusos_slideshow::flexslider/slideshow',
            'label' => __('Small Flexslider v1.8'),
            'config' => array(
                'slides_with_link' => true,
                'slides_preview' => true,
                'width' => '414',
                'height' => '300',
                'class' => 'slide-small',
                'js' => array(
                    'jquery' => 'static/apps/noviusos_slideshow/js/jquery.min.js',
                    'flexslider' => 'static/apps/noviusos_slideshow/js/jquery.flexslider.js',
                    'flexpreview' => 'static/apps/noviusos_slideshow/js/jquery.novius_flexpreview.js',
                ),
                'css' => array(
                    'flexslider' => 'static/apps/noviusos_slideshow/css/flexslider.css',
                    'flexpreview' => 'static/apps/noviusos_slideshow/css/flexpreview.css',
                ),
            ),
        ),
        'flexslider-big-23'   => array(
            'view'   => 'noviusos_slideshow::flexslider23/slideshow',
            'label'  => __('Flexslider Large v2.3'),
            'config' => array(
                'slides_with_link' => true,
                'slides_preview'   => true,
                'width'            => '800',
                'height'           => '600',
                'class'            => 'slide-home',
                'js'               => array(
                    'jquery'      => 'static/apps/noviusos_slideshow/js/jquery.min.js',
                    'flexslider'  => 'static/apps/noviusos_slideshow/js/2.3/jquery.flexslider-min.js',
                    'flexpreview' => 'static/apps/noviusos_slideshow/js/2.3/jquery.novius_flexpreview.js',
                ),
                'css'              => array(
                    'flexslider'  => 'static/apps/noviusos_slideshow/css/2.3/flexslider.css',
                    'flexpreview' => 'static/apps/noviusos_slideshow/css/2.3/flexpreview.css',
                ),
            ),
        ),
        'flexslider-small-23' => array(
            'view'   => 'noviusos_slideshow::flexslider23/slideshow',
            'label'  => __('Flexslider Small v2.3'),
            'config' => array(
                'slides_with_link' => true,
                'slides_preview'   => false,
                'width'            => '414',
                'height'           => '300',
                'class'            => 'slide-small',
                'js'               => array(
                    'jquery'      => 'static/apps/noviusos_slideshow/js/jquery.min.js',
                    'flexslider'  => 'static/apps/noviusos_slideshow/js/2.3/jquery.flexslider-min.js',
                    'flexpreview' => 'static/apps/noviusos_slideshow/js/2.3/jquery.novius_flexpreview.js',
                ),
                'css'              => array(
                    'flexslider'  => 'static/apps/noviusos_slideshow/css/2.3/flexslider.css',
                    'flexpreview' => 'static/apps/noviusos_slideshow/css/2.3/flexpreview.css',
                ),
            ),
        )

    ),
);
