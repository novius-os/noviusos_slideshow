<?php

use Nos\I18n;

I18n::load('local::slideshow');

return array(
	'query' => array(
		'model'     => 'Arcom\Slideshow\Model_Slideshow',
        'order_by'  => array('slideshow_created_at' => 'ASC'),
		'limit'     => 20,
	),
	'search_text'   => 'slideshow_title',
	'selectedView'  => 'default',
	'views' => array(
		'default' => array(
			'name' => __('Default view'),
		),
	),
	'i18n' => array(
	),
	'dataset' => array(
		'id' 		=> 'slideshow_id',
		'title' 	=> 'slideshow_title',
		'actions' 	=> array(
		),
	),
	'inputs' => array(
		'slideshow_created_at' => function($value, $query) {
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
		'reloadEvent' => 'Arcom\\Slideshow\\Model_Slideshow',
		'appdesk' => array(
            'adds' => array(
                'slideshow' => array(
                    'label' => __('Add a slideshow'),
                    'action' => array(
                        'action' => 'nosTabs',
                        'method' => 'add',
                        'tab' => array(
                            'url' => 'admin/slideshow/slideshow/insert_update?lang={{lang}}',
                            'label' => __('Add a new slideshow'),
                        ),
                    ),
                ),
            ),
            'splittersVertical' => 250,
            'grid' => array(
                'proxyUrl' => 'admin/slideshow/appdesk/json',
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
            'inspectors' => array(
            ),
        ),
	)
);
