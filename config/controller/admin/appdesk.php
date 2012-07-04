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
			'json' => array('static/apps/slideshow/js/admin/slideshow.js'),
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
);
