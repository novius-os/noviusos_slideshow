<?php

Nos\I18n::current_dictionary(array('noviusos_slideshow::common', 'nos::application', 'nos::common'));

return array(
    'data_mapping' => array(
        'id' => 'slideshow_id',
        'title' => array(
            'title' => __('Title'),
            'column' => 'slideshow_title',
        ),
    ),
    'i18n' => array(
        // Crud
        'notification item added' => __('All good! Your new slideshow has been added.'),
        'notification item deleted' => __('The slideshow has been deleted.'),

        // General errors
        'notification item does not exist anymore' => __('This slideshow doesn’t exist any more. It has been deleted.'),
        'notification item not found' => __('We cannot find this slideshow. Are you sure it exists?'),

        // Deletion popup
        'deleting item title' => __('Deleting the slideshow ‘{{title}}’'),
        'deleting confirmation' => __('Last chance, there’s no undo. Do you really want to delete this slideshow?'),

        # Delete action's labels
        'deleting button 1 item' => __('Delete this slideshow'),

        '1 item' => __('1 slideshow'),
        'N items' => __('{{count}} slideshows'),
    ),
    'actions' => array(
        'Nos\Slideshow\Model_Slideshow.add' => array(
            'label' => __('Add a slideshow'),
        ),
    ),
);