<?php

namespace Arcom\Diaporama;
use Nos\Orm\Model;

class Model_Image extends Model
{
    protected static $_table_name = 'diaporama_image';
    protected static $_primary_key = array('diapimg_id');

    protected static $_properties = array(
        'diapimg_id',
        'diapimg_diaporama_id',
        'diapimg_position',
        'diapimg_nom',
        'diapimg_description',
        'diapimg_link_to_page_id',
        'diapimg_created_at',
        'diapimg_updated_at',
    );
    protected static $_has_one = array(
        'page' => array(
            'key_from'          => 'diapimg_link_to_page_id',
            'model_to'          => '\Nos\Model_Page',
            'key_to'            => 'page_id',
            'cascade_save'      => false,
            'cascade_delete'    => false,
        ),
    );
    protected static $_observers = array(
        'Orm\Observer_CreatedAt' => array(
            'events' => array('before_insert'),
            'mysql_timestamp' => true,
            'property'=>'diapimg_created_at'
        ),
        'Orm\Observer_UpdatedAt' => array(
            'events' => array('before_save'),
            'mysql_timestamp' => true,
            'property'=>'diapimg_updated_at'
        )
    );
}
