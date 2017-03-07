<?php

Nos\I18n::current_dictionary(array('noviusos_slideshow::common', 'nos::application', 'nos::common'));

return array(
    'data_mapping' => array(
        'id' => 'slideshow_id',
        'title' => array(
            'title' => __('Title'),
            'column' => 'slideshow_title',
        ),
        'thumbnail' => array(
            'value' => function ($item) {
                $images = $item->images;
                if (empty($images)) {
                    return false;
                }
                $image = current($images)->medias->image;
                if (empty($image)) {
                    return false;
                }
                return $image->urlResized(64, 64);
            },
        ),
        'thumbnailAlternate' => array(
            'value' => function ($item) {
                return 'static/novius-os/admin/vendor/jquery/jquery-ui-input-file-thumb/css/images/apn.png';
            }
        ),
        'slide_count' => array(
            'title' => __('Slides'),
            'cellFormatters' => array(
                'center' => array(
                    'type' => 'css',
                    'css' => array('text-align' => 'center'),
                ),
            ),
            'value' => function ($item) {
                return $item->is_new() ? 0 : \Nos\Slideshow\Model_Image::count(array(
                    'where' => array(array('slidimg_slideshow_id' => $item->slideshow_id)),
                ));
            },
            'sorting_callback' => function (&$query, $sortDirection) {
                $query->_join_relation('images', $join);
                $query->group_by($join['alias_from'].'.slideshow_id');
                $query->order_by(\Db::expr('COUNT(*)'), $sortDirection);
            },
            'width' => 100,
            'ensurePxWidth' => true,
            'allowSizing' => false,
        ),
        'context' => true,
    ),
    'i18n' => array(
        // Crud
        'notification item added' => __('Added! This is a nice new slideshow you have here.'),
        'notification item deleted' => __('The slideshow has been deleted.'),

        // General errors
        'notification item does not exist anymore' => __('This slideshow doesn’t exist any more. It has been deleted.'),
        'notification item not found' => __('We cannot find this slideshow.'),

        // Deletion popup
        'deleting item title' => __('Deleting the slideshow ‘{{title}}’'),

        # Delete action's labels
        'deleting button N items' => n__(
            'Yes, delete this slideshow',
            'Yes, delete these {{count}} slideshows'
        ),

        'N items' => n__(
            '1 slideshow',
            '{{count}} slideshows'
        ),
    ),
    'actions' => array(
        'Nos\Slideshow\Model_Slideshow.add' => array(
            'label' => __('Add a slideshow'),
        ),
        'Nos\Slideshow\Model_Slideshow.duplicate' => array(
            'action' => array(
                'action' => 'nosAjax',
                'params' => array(
                    'url' => '{{controller_base_url}}duplicate/{{_id}}',
                ),
            ),
            'label' => __('Duplicate'),
            'primary' => false,
            'icon' => 'circle-plus',
            'targets' => array(
                'grid' => true,
            ),
        ),
    ),
);
