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
    'model' => 'Nos\Slideshow\Model_Slideshow',
    'query' => array(
        'order_by' => array('slideshow_created_at' => 'ASC'),
        'related' => array('images'),
    ),
    'search_text' => 'slideshow_title',
    'i18n' => array(
        'item' => __('slideshow'),
        'items' => __('slideshows'),
        'showNbItems' => __('Showing {{x}} slideshows out of {{y}}'),
        'showOneItem' => __('Show 1 slideshow'),
        'showNoItem' => __('No slideshows'),
        'showAll' => __('Show all slideshows'),
    ),
    'inputs' => array(
        'slideshow_created_at' =>
            function ($value, $query) {
                list($begin, $end) = explode('|', $value.'|');
                if ($begin) {
                    if ($begin = Date::create_from_string($begin, '%Y-%m-%d')) {
                        $query->where(array('slideshow_created_at', '>=', $begin->format('mysql')));
                    }
                }
                if ($end) {
                    if ($end = Date::create_from_string($end, '%Y-%m-%d')) {
                        $query->where(array('slideshow_created_at', '<=', $end->format('mysql')));
                    }
                }
                return $query;
            },
    ),
    'thumbnails' => true,
    'appdesk' => array(
        'appdesk' => array(
            'defaultView' => 'thumbnails',
        ),
    ),
);
