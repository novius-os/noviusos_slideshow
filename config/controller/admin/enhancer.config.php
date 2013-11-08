<?php
/**
 * NOVIUS OS - Web OS for digital communication
 *
 * @copyright  2011 Novius
 * @license    GNU Affero General Public License v3 or (at your option) any later version
 *             http://www.gnu.org/licenses/agpl-3.0.html
 * @link http://www.novius-os.org
 */

$app_config = \Config::loadConfiguration('noviusos_slideshow::slideshow');

$formats = array();
foreach ($app_config['formats'] as $key => $config) {
    $formats[$key] = \Arr::get($config, 'label', $key);
}
$current = \Arr::get($app_config, 'default_format', key($formats));

return array(
    'fields' => array(
        'slideshow_id' => array(
            'label' => __('Select a slideshow:'),
            'form' => array(
                'type' => 'select',
            ),
        ),
        'format' => array(
            'label' => __('Format:'),
            'form' => array(
                'type' => count($formats) === 1 ? 'hidden' : 'select',
                'options' => $formats,
                'value' => $current,
            ),
        ),
    ),
    'preview' => array(
        'view' => 'noviusos_slideshow::admin/enhancer/preview',
    ),
);
