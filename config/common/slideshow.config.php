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
        'successfully added' => __('Slideshow successfully added.'),
        'successfully saved' => __('Slideshow successfully saved.'),
        'successfully deleted' => __('The slideshow has successfully been deleted!'),

        // General errors
        'item deleted' => __('This slideshow has been deleted.'),
        'not found' => __('Slideshow not found'),

        // Deletion popup
        'delete an item' => __('Delete a slideshow'),
        'you are about to delete, confim' => __('You are about to delete the slideshow <span style="font-weight: bold;">":title"</span>. Are you sure you want to continue?'),
        'you are about to delete' => __('You are about to delete the slideshow <span style="font-weight: bold;">":title"</span>.'),
    ),
    'actions' => array(
        'Nos\Slideshow\Model_Slideshow.add' => array(
            'label' => __('Add a slideshow'),
        ),
    ),
);