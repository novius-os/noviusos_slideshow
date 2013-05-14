<?php
/**
 * NOVIUS OS - Web OS for digital communication
 *
 * @copyright  2011 Novius
 * @license    GNU Affero General Public License v3 or (at your option) any later version
 *             http://www.gnu.org/licenses/agpl-3.0.html
 * @link http://www.novius-os.org
 */

$app_config = \Config::load('noviusos_slideshow::slideshow', true);

$sizes = array();
foreach ($app_config['sizes'] as $key => $size) {
    $sizes[$key] = \Arr::get($size, 'label', $key);
}

return array(
    'fields' => array(
        'slideshow_id' => array(
            'label' => __('Select a slideshow:'),
            'form' => array(
                'type' => 'select',
            ),
        ),
        'size' => array(
            'label' => __('Format:'),
            'form' => array(
                'type' => count($sizes) === 1 ? 'hidden' : 'select',
                'options' => $sizes,
                'value' => key($sizes),
            ),
        ),
    ),
    'preview' => array(
        'view' => 'noviusos_slideshow::admin/enhancer/preview',
    ),
);
