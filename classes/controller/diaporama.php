<?php

namespace Arcom\Diaporama;
use Nos\Controller_Front_Application;

class Controller_Diaporama extends Controller_Front_Application
{
	/**
	 * méthode qui gère l'affichage en front du diaporama
	 * @param  array  $args tableau de paramètres, récupérés depuis le wysywig, en provenance de
	 * la popup de config. dans notre cas, on attend diaporama_id et $size
	 */
	public function action_main($args = array())
	{
	    if ( empty($args) || empty($args['diaporama_id']) )
	    {
	        return false;
	    }

	    \Config::load('diaporama::diaporama', 'diaporama');
		$config = \Config::get('diaporama');

	    if (empty($args['size']))
	    {
	    	$size = current($config['sizes']);
	    } else {
	    	$size = $config['sizes'][$args['size']];
	    }
	    $diaporama = Model_Diaporama::find($args['diaporama_id'], array(
		'related' => array(
		    'images' => array(
			'order_by' => array('diapimg_position' => 'asc'),
		    ),
		),
	    ));

	    return \View::forge($this->config['views']['index'], array(
		    'diaporama' => $diaporama,
		    'class'		=> $size['class'],
		    'height'	=> $size['img_height'],
		    'width'		=> $size['img_width'],
		    'show_link' => $config['slides_with_link'],
		    'slides_preview' => $config['slides_preview']
	    ), false);
	}
}
