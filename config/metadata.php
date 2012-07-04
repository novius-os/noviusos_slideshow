<?php
return array(
    'name'      => 'Slideshow',
    'namespace' => 'Arcom\Slideshow',
    'version'   => '0.1-alpha',
    'provider'  => array(
        'name'  => 'Novius OS',
    ),
    'launchers' => array( // = item ajouté dans l'admin
        'slideshow' => array(
            'name'    => 'Slideshow',
            'url'     => 'admin/slideshow/appdesk',
            'iconUrl' => 'static/apps/slideshow/img/slideshow-32.png',
            'icon64'  => 'static/apps/slideshow/img/slideshow-64.png',
        ),
    ),
    'enhancers' => array(
        'slideshow' => array( // = bloc insérable dans un wyysiwig
            'title' => 'Slideshow',
            'id'    => 'slideshow',
            'desc'  => '',
            'enhancer' => 'slideshow/slideshow/main', // pour l'affichage en front
            'iconUrl' => 'static/apps/contact/img/slideshow-16.png', // icon du wysiwig
            'previewUrl' => 'admin/slideshow/preview', // preview pour le wysiwig
            'dialog' => array(
                'contentUrl'    => 'admin/slideshow/popup',
                'width'         => 450,
                'height'        => 180,
                'ajax'          => true,
            ),
        ),
    )
);
