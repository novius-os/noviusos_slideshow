<?php

use Nos\I18n;

I18n::load('local::diaporama');

return array(
	'query' => array(
		'model'     => 'Arcom\Diaporama\Model_Diaporama',
        'order_by'  => array('diaporama_created_at' => 'ASC'),
		'limit'     => 20,
	),
	'search_text'   => 'diaporama_nom',
	'selectedView'  => 'default',
	'views' => array(
		'default' => array(
			'name' => __('Default view'),
			'json' => array('static/apps/diaporama/js/admin/diaporama.js'),
		),
	),
	'i18n' => array(
	),
	'dataset' => array(
		'id' 		=> 'diaporama_id',
		'nom' 		=> 'diaporama_nom',
		'actions' => array(

		),
	),
	'inputs' => array(
		'diaporama_created_at' => function($value, $query) {
			list($begin, $end) = explode('|', $value.'|');
			if ($begin) {
				if ($begin = Date::create_from_string($begin, '%Y-%m-%d')) {
					$query->where(array('diaporama_created_at', '>=', $begin->format('mysql')));
				}
			}
			if ($end) {
				if ($end = Date::create_from_string($end, '%Y-%m-%d')) {
					$query->where(array('diaporama_created_at', '<=', $end->format('mysql')));
				}
			}
			return $query;
		},
	),
);
