<?php
return array(
    'name'      => 'Diaporama',
    'namespace' => 'Arcom\Diaporama',
    'version'   => '0.1-alpha',
    'provider'  => array(
        'name'  => 'Novius OS',
    ),
    'launchers' => array( // = item ajouté dans l'admin
        'diaporama' => array(
            'name'    => 'Diaporama',
            'url'     => 'admin/diaporama/index',
            'iconUrl' => 'static/apps/diaporama/img/diaporama-32.png',
            'icon64'  => 'static/apps/diaporama/img/diaporama-64.png',
        ),
    ),
    'enhancers' => array(
        'diaporama' => array( // = bloc insérable dans un wyysiwig
            'title' => 'Diaporama',
            'id'    => 'diaporama',
            'desc'  => '',
            'enhancer' => 'diaporama/diaporama/main', // pour l'affichage en front
            'iconUrl' => 'static/apps/contact/img/diaporama-16.png', // icon du wysiwig
            'previewUrl' => 'admin/diaporama/preview', // preview pour le wysiwig
            'dialog' => array(
                'contentUrl'    => 'admin/diaporama/popup',
                'width'         => 450,
                'height'        => 180,
                'ajax'          => true,
            ),
        ),
    )
);
