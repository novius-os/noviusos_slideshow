<?php

return array(
    'controller_url'  => 'admin/slideshow/slideshow',
    'model' => 'Arcom\\Slideshow\\Model_Slideshow',
    'messages' => array(
        'successfully added' => __('Slideshow successfully added.'),
        'successfully saved' => __('Slideshow successfully saved.'),
        'successfully deleted' => __('The Slideshow has successfully been deleted!'),
        'you are about to delete, confim' => __('You are about to delete the slideshow <span style="font-weight: bold;">":title"</span>. Are you sure you want to continue?'),
        'you are about to delete' => __('You are about to delete the slideshow <span style="font-weight: bold;">":title"</span>.'),
        'exists in multiple lang' => __('This slideshow exists in <strong>{count} languages</strong>.'),
        'delete in the following languages' => __('Delete this slideshow in the following languages:'),
        'item deleted' => __('This slideshow has been deleted.'),
        'not found' => __('Slideshow not found'),
        'blank_state_item_text' => __('slideshow'),
    ),
    'tab' => array(
        'iconUrl' => 'static/apps/slideshow/img/slideshow-16.png',
        'labels' => array(
            'insert' => __('Add a slideshow'),
            'blankSlate' => __('Translate a slideshow'),
        ),
    ),
    'layout' => array(
        'title' => 'slideshow_title',
        'large' => true,
        'content' => array(
            'images' => array(
                'view' => 'slideshow::admin/_form_content',
                'params' => array(

                ),
            ),
        ),
        'menu' => array(

        ),
        'save' => 'save',
    ),
    'fields' => array(
        'slideshow_id' => array (
            'label' => 'ID: ',
            'form' => array(
                'type' => 'hidden',
            ),
        ),
        'slideshow_title' => array(
            'label' => 'Titre',
            'form' => array(
                'type' => 'text',
            ),
            'validation' => array(
                'required',
                'min_length' => array(2),
            ),
        ),
        'save' => array(
            'label' => 'Ajouter',
            'form' => array(
                'type' => 'submit',
                'tag'   => 'button',

                'value' => 'Save',
                'class' => 'primary',
                'data-icon' => 'check',
            ),
        ),
        'save' => array(
            'label' => '',
            'form' => array(
                'type' => 'submit',
                'tag' => 'button',
                'value' => __('Save'),
                'class' => 'primary',
                'data-icon' => 'check',
            ),
        ),
    ),
);