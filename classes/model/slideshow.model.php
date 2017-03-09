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
use Nos\Page\Model_Page;

class Model_Slideshow extends Model
{
    protected static $_table_name = 'nos_slideshow';
    protected static $_primary_key = array('slideshow_id');

    protected static $_title_property = 'slideshow_title';
    protected static $_properties = array(
        'slideshow_id' => array(
            'default' => null,
            'data_type' => 'int unsigned',
            'null' => false,
        ),
        'slideshow_title' => array(
            'default' => null,
            'data_type' => 'varchar',
            'null' => false,
        ),
        'slideshow_context' => array(
            'default' => null,
            'data_type' => 'varchar',
            'null' => false,
        ),
        'slideshow_created_at' => array(
            'data_type' => 'timestamp',
            'null' => false,
        ),
        'slideshow_updated_at' => array(
            'default' => 'CURRENT_TIMESTAMP',
            'data_type' => 'timestamp',
            'null' => false,
        ),
        'slideshow_created_by_id' => array(
            'default' => null,
            'data_type' => 'int unsigned',
            'null' => true,
            'convert_empty_to_null' => true,
        ),
        'slideshow_updated_by_id' => array(
            'default' => null,
            'data_type' => 'int unsigned',
            'null' => true,
            'convert_empty_to_null' => true,
        ),
    );

    protected static $_has_many = array(
        'images' => array(
            'key_from' => 'slideshow_id',
            'model_to' => '\Nos\Slideshow\Model_Image',
            'key_to' => 'slidimg_slideshow_id',
            'cascade_save' => false,
            'cascade_delete' => false,
            'conditions' => array(
                'order_by' => array('slidimg_position' => 'ASC'),
            ),
        ),
    );

    protected static $_behaviours = array(
        'Nos\Orm_Behaviour_Contextable' => array(
            'context_property'      => 'slideshow_context',
        ),
        'Nos\Orm_Behaviour_Author' => array(
            'created_by_property' => 'slideshow_created_by_id',
            'updated_by_property' => 'slideshow_updated_by_id',
        ),
    );

    protected static $_observers = array(
        'Orm\Observer_CreatedAt' => array(
            'mysql_timestamp' => true,
            'property' => 'slideshow_created_at'
        ),
        'Orm\Observer_UpdatedAt' => array(
            'mysql_timestamp' => true,
            'property' => 'slideshow_updated_at'
        )
    );

    /**
     * @param $targetContext : the context target wanted for the duplicated slideshow
     * @throws \Exception
     */
    public function duplicate($targetContext)
    {
        $clone = clone $this;
        $try = 1;
        do {
            try {
                $title_append = strtr(__(' (copy {{count}})'), array(
                    '{{count}}' => $try,
                ));
                $clone->slideshow_title = $this->title_item().$title_append;
                $clone->slideshow_context = $targetContext;
                if ($clone->save()) {
                    $this->duplicateSlideImages($this, $clone);
                }
                break;
            } catch (\Nos\BehaviourDuplicateException $e) {
                $try++;
                if ($try > 5) {
                    throw new \Exception(__(
                        'Slow down, slow down. You have duplicated this slideshow 5 times already. '.
                        'Edit them first before creating more duplicates.'
                    ));
                }
            }
        } while ($try <= 5);
    }

    /**
     * For any given image containing a link to another site's page
     * we find out if a translated page already exists on the target context.
     * If so, we update the id of the linked page before save. Otherwise is null.
     *
     * @param Model_Slideshow $slider : The original slideShow, images will be duplicated FROM
     * @param Model_Slideshow $duplicatedSlider : The duplicated slideShow, images will be duplicated TO
     */
    protected function duplicateSlideImages(Model_Slideshow $slider, Model_Slideshow $duplicatedSlider)
    {
        foreach ($slider->images as $image) {
            /**
             * @var $field Model_Image
             */
            $clonedImage = clone $image;
            $clonedImage->slidimg_slideshow_id = $duplicatedSlider->slideshow_id;

            if ($clonedImage->page) {
                $clonedImage->slidimg_link_to_page_id = null;
                $newContextPage = $clonedImage->page->find_context($duplicatedSlider->slideshow_context) ?: null;
                if($newContextPage) {
                    $clonedImage->slidimg_link_to_page_id = $newContextPage->page_id;
                }
            }
            $clonedImage->save();
        }
    }
}
