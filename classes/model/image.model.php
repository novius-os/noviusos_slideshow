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
