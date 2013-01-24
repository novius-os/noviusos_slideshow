<?php
/**
 * NOVIUS OS - Web OS for digital communication
 *
 * @copyright  2011 Novius
 * @license    GNU Affero General Public License v3 or (at your option) any later version
 *             http://www.gnu.org/licenses/agpl-3.0.html
 * @link http://www.novius-os.org
 */


Nos\I18n::current_dictionary('noviusos_slideshow::common');

?>
<div class="slideshow_image accordion fieldset field_enclosure <?= !empty($is_model) ? 'slideshow_model ' : '' ?>">
    <h3><a href="#"><?= __('Properties') ?></a></h3>
    <div style="overflow:visible;">


    <input type="hidden" name="images[<?php echo $i; ?>][_id]" value="<?= $i ?>"/>
    <input type="hidden" name="images[<?php echo $i; ?>][slidimg_id]" value="<?= e(!empty($image) ? $image->slidimg_id : '') ?>"/>

    <p>
        <label><?= e(__('Image:')) ?> <span style="font-size: 1.5em; line-height: 1em; font-weight: bold">*</span></label>
        <br />
<?php

$thumbnail = !empty($image->medias->image) ? $image->medias->image->get_public_path_resized(160, 160) : '';

echo '<input type="hidden" name="images['.$i.'][thumb]" value="'.(!empty($thumbnail) ? $thumbnail : 'static/novius-os/admin/vendor/jquery/jquery-ui-input-file-thumb/css/images/apn.png').'" />';

if ($is_model) {
    $media = '<input type="text" class="media" name="images['.$i.'][media_id]" value="0" />';
}
if (!empty($media)) {
    echo $media;
}
?>
    </p>
    <p>
        <label>
            <?= e(__('Slide name:')) ?>
            <br />
            <input type="text" title="<?= e(__('Slide name')) ?>" name="images[<?= $i ?>][slidimg_title]" value="<?= e(!empty($image) ? ($image->slidimg_title) : '') ?>" />
        </label>
    </p>
    <p>
        <label>
            <?= e(__('Description:')) ?>
            <br />
            <textarea name="images[<?php echo $i; ?>][slidimg_description]" rows="4"><?= e(!empty($image) ? ($image->slidimg_description) : '') ?></textarea>
        <label>
    </p>
<?php
if ($show_link) {
    ?>
    <p>
        <label><?= e(__('Links to:')) ?></label>
        <br />
        <?php
        $has_link = !empty($image) && $image->slidimg_link_to_page_id;
        ?>
        <a href="#" class="add_link_to" style="<?= $has_link ? 'display:none;' : '' ?>"><?= __('Add a link'); ?></a>
        <div class="link_to" style="<?= $has_link ? '' : 'display:none;' ?>">
            <div class="transform_renderer_page_selector" data-renderer_page_selector="<?= htmlspecialchars(\Format::forge()->to_json(
                array(
                    'input_name' => 'images['.$i.'][slidimg_link_to_page_id]',
                    'selected' => array(
                        'id' => !empty($image) ? ($image->slidimg_link_to_page_id) : 0,
                        'model' => 'Nos\Page\Model_Page',
                    ),
                    'treeOptions' => array(
                        'context' => \Input::get('context_main', Nos\Tools_Context::defaultContext()),
                    ),
                    // Default
                    'urlJson' => 'admin/noviusos_page/inspector/page/json',
                    'reloadEvent' => 'Nos\Page\Model_Page',
                    'columns' => array(
                        array(
                            'dataKey' => 'title',
                        )
                    ),
                    'height' => '150px',
                    'width' => null,
                    'contextChange' => true,
                ))) ?>">
                <table class="nos-treegrid"></table>
            </div>
        </div>
        <a href="#" class="remove_link_to link_to" style="<?= $has_link ? '' : 'display:none;' ?>"><?= __('Remove the link'); ?></a>
    </p>
    <?php
}
?>
    </div>
</div>