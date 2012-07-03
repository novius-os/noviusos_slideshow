<?php

namespace Arcom\Diaporama;
use Nos\Orm\Model;

class Model_Diaporama extends Model
{
    protected static $_table_name = 'diaporama';
    protected static $_primary_key = array('diaporama_id');

	protected static $_properties = array(
		'diaporama_id',
		'diaporama_nom',
		'diaporama_created_at',
		'diaporama_updated_at'
	);
	
	protected static $_has_many = array(
        'images' => array(
            'key_from'          => 'diaporama_id',
            'model_to'          => '\Arcom\Diaporama\Model_Image',
            'key_to'            => 'diapimg_diaporama_id',
            'cascade_save'      => true,
            'cascade_delete'    => false,
        ),
    );

	protected static $_observers = array(
		'Orm\Observer_CreatedAt' => array(
			'events' => array('before_insert'),
			'mysql_timestamp' => true,
			'property'=>'diaporama_created_at'
		),
		'Orm\Observer_UpdatedAt' => array(
			'events' => array('before_save'),
			'mysql_timestamp' => true,
			'property'=>'diaporama_updated_at'
		)
	);
}
