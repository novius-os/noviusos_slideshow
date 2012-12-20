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
        'successfully added' => __('Done! The slideshow has been added.'),
        'successfully deleted' => __('The slideshow has been deleted.'),

        // General errors
        'item deleted' => __('This slideshow doesn’t exist any more. It has been deleted.'),
        'not found' => __('We cannot find this slideshow.'),

        // Deletion popup
        'delete an item' => __('Deleting the news slideshow ‘{{title}}’'),
        'you are about to delete, confim' => __('Last chance, there’s no undo. Do you really want to delete this slideshow?'),
    ),
    'actions' => array(
        'Nos\Slideshow\Model_Slideshow.add' => array(
            'label' => __('Add a slideshow'),
        ),
    ),
);