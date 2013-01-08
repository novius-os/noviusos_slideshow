<?php
/**
 * NOVIUS OS - Web OS for digital communication
 *
 * @copyright  2011 Novius
 * @license    GNU Affero General Public License v3 or (at your option) any later version
 *             http://www.gnu.org/licenses/agpl-3.0.html
 * @link http://www.novius-os.org
 */
?>
<div class="slideshow_image accordion fieldset field_enclosure <?= !empty($is_model) ? 'slideshow_model ' : '' ?>">
    <h3><a href="#"><?= __('Required information') ?></a></h3>
    <div style="overflow:visible;">


    <input type="hidden" name="images[<?php echo $i; ?>][slidimg_id]" value="<?= (!empty($image) ? $image->slidimg_id : '') ?>"/>

    <p>
        <span><label>
            <?= e(__('Image:')) ?>
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
        </label></span>
    </p>
    <p>
        <span><label>
            <?= e(__('Slide name:')) ?>
            <br />
            <input type="text" placeholder="<?= e(__('Slide name')) ?>" title="<?= e(__('Slide name')) ?>" name="images[<?= $i ?>][slidimg_title]" value="<?= (!empty($image) ? ($image->slidimg_title) : '') ?>" />
        </label></span>
    </p>
    <p>
        <span><label>
            <?= e(__('Description:')) ?>
            <br />
            <textarea name="images[<?php echo $i; ?>][slidimg_description]" rows="4" placeholder="<?= e(__('Description')) ?>"><?= (!empty($image) ? ($image->slidimg_description) : '') ?></textarea>
            <label></span>
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
        <?= Nos\Page\Renderer_Selector::renderer(
        array(
            'input_name' => 'images['.$i.'][slidimg_link_to_page_id]',
            'selected' => array(
                'id' => !empty($image) ? ($image->slidimg_link_to_page_id) : null,
                'model' => 'Nos\\Page\\Model_Page',
            ),
            'treeOptions' => array(
                'context' => \Input::get('context_main', Nos\Tools_Context::defaultContext()),
            ),
        )
    ); ?>
        </div>
        <a href="#" class="remove_link_to link_to" style="<?= $has_link ? '' : 'display:none;' ?>"><?= __('Remove the link'); ?></a>
    </p>
    <?php
}
?>
    </div>
</div>