<?php
/**
 * NOVIUS OS - Web OS for digital communication
 *
 * @copyright  2011 Novius
 * @license    GNU Affero General Public License v3 or (at your option) any later version
 *             http://www.gnu.org/licenses/agpl-3.0.html
 * @link http://www.novius-os.org
 */

namespace Nos\Slideshow;

use Nos\Orm\Model;

class Model_Image extends Model
{
    protected static $_table_name = 'nos_slideshow_image';
    protected static $_primary_key = array('slidimg_id');

    protected static $_title_property = 'slidimg_title';
    protected static $_properties = array(
        'slidimg_id' => array(
            'default' => null,
            'data_type' => 'int unsigned',
            'null' => false,
        ),
        'slidimg_slideshow_id' => array(
            'default' => null,
            'data_type' => 'varchar',
            'null' => false,
        ),
        'slidimg_position' => array(
            'default' => null,
            'data_type' => 'int',
            'null' => false,
        ),
        'slidimg_title' => array(
            'default' => null,
            'data_type' => 'varchar',
            'null' => true,
            'convert_empty_to_null' => true,
        ),
        'slidimg_description' => array(
            'default' => null,
            'data_type' => 'text',
            'null' => true,
            'convert_empty_to_null' => true,
        ),
        'slidimg_link_to_page_id' => array(
            'default' => null,
            'data_type' => 'int',
            'null' => true,
            'convert_empty_to_null' => true,
        ),
    );

    protected static $_has_one = array(
        'page' => array(
            'key_from'          => 'slidimg_link_to_page_id',
            'model_to'          => '\Nos\Page\Model_Page',
            'key_to'            => 'page_id',
            'cascade_save'      => false,
            'cascade_delete'    => false,
        ),
    );
}
