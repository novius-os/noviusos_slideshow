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
<div class="<?php if (!empty($is_model)) {
    echo 'slideshow_model ';
} ?>slideshow_image" style="position: relative; border:1px solid #ccc; padding: 10px 10px 10px 20px; margin: 10px 0">
    <div class="handle" style="position: absolute; top: 0px; left: 0px; width: 10px; height: 100%; background: #ccc; cursor: move"></div>

    <input type="hidden" name="images[<?php echo $i; ?>][slidimg_id]" value="<?= (!empty($image) ? $image->slidimg_id : '') ?>"/>

    <div style="float:left">
<?php
if ($is_model) {
    $media = '<input type="text" class="media" name="images['.$i.'][media_id]" value="0" />';
}
if (!empty($media)) {
    echo $media;
}
?>
    </div>
    <div style="float:left;width:500px">
        <input type="text" placeholder="<?= e(__('Slide name')) ?>" style="width: 500px;" title="<?= e(__('Slide name')) ?>" name="images[<?= $i ?>][slidimg_title]" value="<?= (!empty($image) ? ($image->slidimg_title) : '') ?>"/>
        <br/>
<?php
if ($show_link) {
    ?>
        <p style="margin-top:5px">
            <a href="#" class="toggle_link_to"><?php echo ((!empty($image) && $image->slidimg_link_to_page_id) ? (strtr(__('This slide links to "{title}"'), array('{title}' => $image->page->page_title))) : __('Make a link')); ?></a>
        </p>
    <?php
}
?>
    </div>
    <br style="clear:both"/>
<?php
if ($show_link) {
    ?>
    <div class="link_to" style="overflow:hidden;height:0;margin-left:70px">
        <?= Nos\Page\Renderer_Selector::renderer(
        array(
            'input_name' => 'images['.$i.'][slidimg_link_to_page_id]',
            'width' => 510,
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
    <?php
}
?>
    <textarea name="images[<?php echo $i; ?>][slidimg_description]" placeholder="<?= e(__('Description')) ?>" style="width: 700px; margin-top: 10px"><?= (!empty($image) ? ($image->slidimg_description) : '') ?></textarea>

    <button data-icon="trash" class="close" style="padding-right: 0; position: absolute; top: 10px; right: 10px;">&nbsp;</button>
</div>
