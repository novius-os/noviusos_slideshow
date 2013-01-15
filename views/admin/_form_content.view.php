<?php
/**
 * NOVIUS OS - Web OS for digital communication
 *
 * @copyright  2011 Novius
 * @license    GNU Affero General Public License v3 or (at your option) any later version
 *             http://www.gnu.org/licenses/agpl-3.0.html
 * @link http://www.novius-os.org
 */

\Config::load('noviusos_slideshow::slideshow', 'slideshow');

$form_id = 'slideshow_'.uniqid(true);

?>
<link rel="stylesheet" href="static/apps/noviusos_slideshow/css/admin.css" />

<div id="<?= $form_id ?>">
<?php

// Retourne un "bloc" (une image avec ses infos)
function slidimg($image = null, $is_model = false)
{
    static $i = 1;
    static $show_link = null;
    if ($show_link === null) {
        $show_link = \Config::get('slideshow.slides_with_link', false);
    }
    $media_id = 0;
    if (!empty($image) && !empty($image->medias->image) && !empty($image->medias->image->media_id)) {
        $media_id = $image->medias->image->media_id;
    }

    if ($is_model) {
        $media = '';
    } else {
        $media = \Nos\Renderer_Media::renderer(
            array(
                'name' => 'images['.$i.'][media_id]',
                'value' => $media_id,
                'renderer_options' => array(
                    'inputFileThumb' => array(
                        'title' => __('Image'),
                    ),
                ),
            )
        );
    }

    $view = \View::forge(
        'noviusos_slideshow::admin/_form_image',
        array(
            'i' => $i,
            'image' => $image,
            'show_link' => $show_link,
            'is_model' => $is_model,
        )
    );
    $view->set_safe('media', $media);
    $i++;

    return $view;
}
if (!$item->is_new()) {
    $count = \Nos\Model_Wysiwyg::count(array(
        'where' => array(
            array('wysiwyg_text', 'LIKE', '%&quot;slideshow_id&quot;:&quot;'.$item->id.'%'),
        ),
    ));
    if ($count == 0) {
        echo \View::forge('noviusos_slideshow::admin/warning_not_published', $view_params, false);
    }
}
?>
    <div class="line">
        <div class="col c8" style="position:relative;">
            <p style="height: 40px;">&nbsp;</p>
            <ul class="preview_container">
            </ul>
            <button type="button" class="primary preview_style" style="width: 178px; height: 168px;" data-icon="plus" data-id="add" data-params="<?= e(json_encode(array('where' => 'bottom'))) ?>"><?= __('Add a slide') ?></button>
            <br style="clear:both;" />
        </div>

        <div class="col c4 slides_container" style="display:none;padding-top:3px;">
            <p class="actions show_hide">
                <button type="button" data-icon="trash" data-id="delete" class="action"><?= ('Delete') ?></button>
                <img class="preview_arrow show_hide" src="static/apps/noviusos_slideshow/img/arrow-edition.png" />
            </p>
<?php
// Model pour ajouter une nouvelle image
echo slidimg(null, true);

// Liste des images actuelles
foreach ($item->images as $img) {
    echo slidimg($img, false);
}
?>
        </div>
    </div>
</div>

<script type="text/javascript">
require(['jquery-nos', 'static/apps/noviusos_slideshow/js/admin/insert_update.js'], function($, init_form) {
    $(function() {
        init_form('#<?= $form_id ?>', <?= Format::forge()->to_json(array(
            'mediaSelector' => array(
                'mode' => 'image',
                'inputFileThumb' => array(
                    'title' => __('Image'),
                    'file' => '',
                ),
            ),
            'textDelete' =>  __('Are you sure you want to delete this slide?'),
        )) ?>, <?= $crud['is_new'] ? 'true' : 'false'; ?>);
   });
});
</script>
