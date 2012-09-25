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

class Model_Slideshow extends Model
{
    protected static $_table_name = 'slideshow';
    protected static $_primary_key = array('slideshow_id');

    protected static $_has_many = array(
        'images' => array(
            'key_from' => 'slideshow_id',
            'model_to' => '\Nos\Slideshow\Model_Image',
            'key_to' => 'slidimg_slideshow_id',
            'cascade_save' => true,
            'cascade_delete' => false,
        ),
    );

    protected static $_observers = array(
        'Orm\Observer_CreatedAt' => array(
            'events' => array('before_insert'),
            'mysql_timestamp' => true,
            'property' => 'slideshow_created_at'
        ),
        'Orm\Observer_UpdatedAt' => array(
            'events' => array('before_save'),
            'mysql_timestamp' => true,
            'property' => 'slideshow_updated_at'
        )
    );
}
