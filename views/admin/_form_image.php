<div class="<?php if ( !empty($is_model) ) { echo 'slideshow_model '; } ?>slideshow_image" style="position: relative; border:1px solid #ccc; padding: 10px 10px 10px 20px; margin: 10px 0">
    <div class="handle" style="position: absolute; top: 0px; left: 0px; width: 10px; height: 100%; background: #ccc; cursor: move"></div>

    <input type="hidden" name="images[<?php echo $i; ?>][slidimg_id]" value="<?php echo ( !empty($image) ? $image->slidimg_id : '' ); ?>" />
    <div style="float:left">
<?php
    if ( $is_model )
    {
        $media = '<input type="text" class="media" name="images['.$i.'][media_id]" value="0" />';
    }
    if ( !empty($media) )
    {
        echo $media;
    }
?>
    </div>
    <div style="float:left;width:500px">
        <input type="text" placeholder="Nom du slide" style="width: 500px;" title="Nom" name="images[<?php echo $i; ?>][slidimg_title]" value="<?php echo ( !empty($image) ? ($image->slidimg_title) : '' ); ?>" /> <br />
        <?php if ($show_link):?>
        <p style="margin-top:5px"><a href="#" class="toggle_link_to"><?php echo ( (!empty($image) && $image->slidimg_link_to_page_id) ? ('Cette slide pointe vers &laquo; '. $image->page->page_title . ' &raquo;' ) : 'Faire un lien' ); ?></a></p>
        <?php endif; ?>
    </div>
    <br style="clear:both" />
    <?php if ($show_link):?>
    <div class="link_to" style="overflow:hidden;height:0;margin-left:70px">
    <?= Nos\Widget_Page_Selector::widget(array(
        'input_name'=> 'images['.$i.'][slidimg_link_to_page_id]',
        'width'=>510,
        'selected' => array(
            'id' => !empty($image) ? ($image->slidimg_link_to_page_id) : null,
            'model' => 'Nos\\Model_Page',
        ),
        'treeOptions' => array(
           'lang' => 'fr_FR'
        ),
    ));?>
    </div>
    <?php endif; ?>
    <textarea name="images[<?php echo $i; ?>][slidimg_description]" placeholder="Description" style="width: 700px; margin-top: 10px" ><?php echo ( !empty($image) ? ($image->slidimg_description) : '' ); ?></textarea>

    <button data-icon="trash" class="close" style="padding-right: 0; position: absolute; top: 10px; right: 10px;">&nbsp;</button>
</div>
