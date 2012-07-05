<?php

echo $fieldset->open('/admin/slideshow/form/edit/'.$slideshow->slideshow_id);
$form_id = \Arr::get($fieldset->get_config('form_attributes'), 'id', '');

// Retourne un "bloc" (une image avec ses infos)
function slidimg($i, $image = null, $is_model = false, $show_link = false) {
    $media_id = 0;
    if ( !empty($image) && !empty($image->medias->image) && !empty($image->medias->image->media_id) )
    {
        $media_id = $image->medias->image->media_id;
    }

    if ( $is_model )
    {
        $media = '';
    }
    else
    {
        $media = \Nos\Widget_Media::widget(array(
            'name' => 'images['.$i.'][media_id]',
            'value' => $media_id,
            'widget_options' => array(
                'inputFileThumb' => array(
                    'title' => 'Image',
                ),
            ),
        ));
    }

    $view = \View::forge('slideshow::admin/_form_image', array(
        'i'         => $i,
        'image'     => $image,
        'show_link' => $show_link,
        'is_model'  => $is_model,
    ));
    $view->set_safe('media', $media);

    return $view;
}

$content = array();

$content[] = '<style>.slideshow_model { display: none }</style>'; // TODO
$content[] = '<div class="slideshow_imageslist">';

// Model pour ajouter une nouvelle image
$content[] = slidimg(count($content), null, true);

// Liste des images actuelles
foreach ( $images as $img )
{
    $content[] = slidimg(count($content), $img,false,$show_link);
}

// Ajouter une nouvelle image
$content[] = slidimg(count($content),null,false,$show_link);

$content[] = '</div>';

// Bouton "Nouvelle image"
$content[] = '<div style="text-align: right;"><button data-icon="plus" class="slideshow_add_image">Ajouter une image</button></div>';

// Vue globale
echo \View::forge('form/layout_standard', array(
    'fieldset'  => $fieldset,
    'object' 	=> $slideshow,
    'medias' 	=> null,
    'title' 	=> 'slideshow_title',
    'id' 		=> 'slideshow_id',
    'save' 		=> 'save',
    'subtitle' 	=> array('slideshow_id'),
    'content'   => $content,
    'menu'      => array(
    ),
), false);

echo $fieldset->close();

?>
<script type="text/javascript">
require(['jquery-nos', 'jquery-ui.sortable'], function($) {
    $(function() {
        var $container      = $('#<?php echo $form_id; ?>'),
            $slideshow_list = $container.find('div.slideshow_imageslist'),
            media_options   = $slideshow_list.children(':last').find('input.media').data('media-options'),
            field_index     = $slideshow_list.children().length + 1;

        $slideshow_list.sortable({
            axis    : 'y',
            handle  : '.handle'
        });

        $container.on('click', 'div.slideshow_image button.close', function(e) {
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();

            var self        = this,
                media_id    = $(self).closest('div.slideshow_image').find('input.media').val();

            var remove_image = function() {
                // On en garde toujours au moins une image dans le dom
                if ( $slideshow_list.children().length > 1 )
                {
                    $(self).closest('div.slideshow_image').remove();
                }
                else
                {
                    $(self).closest('div.slideshow_image').find('textarea, input').val('');
                }
            };

            // On fait une confirmation que si on supprime une "vraie" image
            if ( media_id && media_id > 0 )
            {
                $container.nosConfirmationDialog({
                    content     : 'Etes-vous certain de vouloir supprimer cette image ?',
                    title       : 'Delete an image',
                    confirmed   : remove_image
                });
            }
            else
            {
                remove_image();
            }
        })
        .on('click', 'button.slideshow_add_image', function(e) {
            e.preventDefault();

            var $form       = $(this).closest('form'),
                $lastimg    = $slideshow_list.find('div.slideshow_model'),
                $newimg     = $lastimg.clone(true).removeClass('slideshow_model').find('*').removeAttr('id').end();

            // On doit vider les champs du nouveau bloc, et re-indexer leur nom (index du tableau $_POST['images'])
            // L'idée est que les attr('name') de chaque input/textarea d'un même bloc aient le même index
            field_index++;
            $newimg.find('textarea, input').val('').each(function()
            {
                var _name = $(this).attr('name');
                if ( _name && _name.length > 0 )
                {
                    var re = new RegExp(/^images\[([0-9]+)\]\[([a-z_]+)\]$/g),
                        m  = re.exec(_name);
                    m && $(this).attr('name', 'images['+field_index+']['+m[2]+']');
                }
            });

            // Supprime/Regenere le champs media
            /*
            var $media_input = $newimg.find('input.media').removeAttr('id').removeData().removeAttr('data-media-options').off();
            $media_input.closest('.ui-widget').replaceWith($media_input);
            $media_input.media(media_options);
            */
            $newimg.find('input.media').nosMedia(media_options);

            // Fix : Passer le bouton Add en inline-block...
            $newimg.find('div.ui-inputfilethumb-fileactions').children(':first').css('display', 'inline-block');

            $slideshow_list.append($newimg);
        });

        // Changement titre du tab
        var tabInfos = {
            label   : <?= \Format::forge()->to_json($slideshow->is_new()? __('Add a slideshow') : $slideshow->slideshow_title) ?>,
            iconUrl : 'static/apps/slideshow/img/slideshow-16.png',
        };
        $container.nosOnShow('bind', function() {
            $container.nosTabs('update', tabInfos);
        });
        $('.toggle_link_to').bind('click',function(){
            $target = $(this).parents('.slideshow_image').find('.link_to');
            if ($target.height()==0)
                $target.height(150);
            else
                $target.height(0);
            return false;
        });
    });
});
</script>
