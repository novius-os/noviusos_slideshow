<?php

namespace Arcom\Slideshow;
use Nos\Orm\Model;

class Model_Image extends Model
{
    protected static $_table_name = 'slideshow_image';
    protected static $_primary_key = array('slidimg_id');

    protected static $_has_one = array(
        'page' => array(
            'key_from'          => 'slidimg_link_to_page_id',
            'model_to'          => '\Nos\Model_Page',
            'key_to'            => 'page_id',
            'cascade_save'      => false,
            'cascade_delete'    => false,
        ),
    );
}
