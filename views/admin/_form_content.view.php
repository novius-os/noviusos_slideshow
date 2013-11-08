<?php
/**
 * NOVIUS OS - Web OS for digital communication
 *
 * @copyright  2011 Novius
 * @license    GNU Affero General Public License v3 or (at your option) any later version
 *             http://www.gnu.org/licenses/agpl-3.0.html
 * @link http://www.novius-os.org
 */

Nos\I18n::current_dictionary(array('noviusos_slideshow::common', 'nos::common'));

$form_id = 'slideshow_'.uniqid(true);

?>
<link rel="stylesheet" href="static/apps/noviusos_slideshow/css/admin.css" />

<div id="<?= $form_id ?>">
<?php
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
            <button type="button" class="ui-priority-primary preview_style" style="width: 178px; height: 168px;" data-icon="plus" data-id="add" data-params="<?= e(json_encode(array('where' => 'bottom'))) ?>"><?= __('Add a slide') ?></button>
            <br style="clear:both;" />
        </div>

        <div class="col c4 slides_container" style="display:none;padding-top:3px;">
            <p class="actions show_hide">
                <button type="button" data-icon="trash" data-id="delete" class="action"><?= __('Delete') ?></button>
                <img class="preview_arrow show_hide" src="static/apps/noviusos_slideshow/img/arrow-edition.png" />
            </p>
<?php
// Liste des images actuelles
foreach ($item->images as $img) {
    echo \Request::forge('noviusos_slideshow/admin/slideshow/render_image_fieldset')->execute(array($img));
}
?>
        </div>
    </div>
</div>

<script type="text/javascript">
require(['jquery-nos', 'static/apps/noviusos_slideshow/js/admin/insert_update.js'], function($, init_form) {
    $(function() {
        init_form('#<?= $form_id ?>', <?= Format::forge()->to_json(array(
            'textDelete' =>  __('Are you sure you want to delete this slide?'),
        )) ?>, <?= $crud['is_new'] ? 'true' : 'false'; ?>);
   });
});
</script>
