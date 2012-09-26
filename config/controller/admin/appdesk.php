<?php
/**
 * NOVIUS OS - Web OS for digital communication
 *
 * @copyright  2011 Novius
 * @license    GNU Affero General Public License v3 or (at your option) any later version
 *             http://www.gnu.org/licenses/agpl-3.0.html
 * @link http://www.novius-os.org
 */

use Nos\I18n;

I18n::load('local::slideshow');

return array(
    'query' => array(
        'model' => 'Nos\Slideshow\Model_Slideshow',
        'order_by' => array('slideshow_created_at' => 'ASC'),
        'limit' => 20,
    ),
    'search_text' => 'slideshow_title',
    'selectedView' => 'default',
    'views' => array(
        'default' => array(
            'name' => __('Default view'),
        ),
    ),
    'i18n' => array(),
    'dataset' => array(
        'id' => 'slideshow_id',
        'title' => 'slideshow_title',
        'actions' => array(),
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
    'appdesk' => array(
        'tab' => array(
            'label' => __('slideshows'),
            'iconUrl' => 'static/apps/slideshow/img/slideshow-32.png'
        ),
        'actions' => array(
            'update' => array(
                'action' => array(
                    'action' => 'nosTabs',
                    'tab' => array(
                        'url' => "admin/slideshow/slideshow/insert_update/{{id}}",
                        'label' => __('Edit'),
                    ),
                ),
                'label' => __('Edit'),
                'name' => 'edit',
                'primary' => true,
                'icon' => 'pencil'
            ),
            'delete' => array(
                'action' => array(
                    'action' => 'confirmationDialog',
                    'dialog' => array(
                        'contentUrl' => 'admin/slideshow/slideshow/delete/{{id}}',
                        'title' => __('Delete a slideshow'),
                    ),
                ),
                'label' => __('Delete'),
                'name' => 'delete',
                'primary' => true,
                'icon' => 'trash'
            ),
            'visualise' => array(
                'label' => 'Visualise',
                'name' => 'visualise',
                'primary' => true,
                'iconClasses' => 'nos-icon16 nos-icon16-eye',
                'action' => array(
                    'action' => 'window.open',
                    'url' => '{{url}}?_preview=1'
                ),
            ),
        ),
        'reloadEvent' => 'Nos\\Slideshow\\Model_Slideshow',
        'appdesk' => array(
            'buttons' => array(
                'slideshow' => array(
                    'label' => __('Add a slideshow'),
                    'action' => array(
                        'action' => 'nosTabs',
                        'method' => 'add',
                        'tab' => array(
                            'url' => 'admin/slideshow/slideshow/insert_update?site={{site}}',
                            'label' => __('Add a new slideshow'),
                        ),
                    ),
                ),
            ),
            'splittersVertical' => 250,
            'grid' => array(
                'urlJson' => 'admin/slideshow/appdesk/json',
                'columns' => array(
                    'title' => array(
                        'headerText' => __('Title'),
                        'dataKey' => 'title'
                    ),
                    'actions' => array(
                        'actions' => array('update', 'delete')
                    ),
                ),
            ),
            'inspectors' => array(),
        ),
    )
);
