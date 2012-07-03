<?php
\Nos\Nos::main_controller()->addJavascript('static/apps/diaporama/js/jquery.flexslider.js');
\Nos\Nos::main_controller()->addCss('static/apps/diaporama/css/flexslider.css');
if ($slides_preview)
{
    \Nos\Nos::main_controller()->addJavascript('static/apps/diaporama/js/jquery.novius_flexpreview.js');
    \Nos\Nos::main_controller()->addCss('static/apps/diaporama/css/flexpreview.css');
}
 ?>
<script type="text/javascript" charset="utf-8">
<?php
    \Config::load('diaporama::flexslider', 'flexslider');
    $config = \Config::get('flexslider');
?>
    $(window).load(function()
    {
        $('.flexslider').flexslider(<?php echo json_encode($config); ?>)<?php if ($slides_preview) echo '.novius_flexpreview()';?>;
    });
</script>
<div class="flex-nav-container <?=$class?>">
<div class="flexslider">
    <ul class="slides">
<?php

    foreach ( $diaporama->images as $image )
    {
        if ( empty($image->medias->image) )
        {
            continue;
        }

        echo '<li ';
        if ($slides_preview) echo 'data-thumb="', $image->medias->image->get_public_path_resized(300, 100), '"';
        echo '>';
        if ($show_link && $image->diapimg_link_to_page_id) echo '<a href="'. $image->page->get_href().'">';
        echo '<img style="margin: 0 auto;" src="', $image->medias->image->get_public_path_resized($width,$height), '" alt="', htmlspecialchars($image->diapimg_nom), '" title="', htmlspecialchars($image->diapimg_nom), '" />';
        if ($show_link && $image->diapimg_link_to_page_id) echo '</a>';
        if ( !empty($image->diapimg_nom) || !empty($image->diapimg_description) )
        {
            echo '<p class="flex-caption">';
            if ( !empty($image->diapimg_nom) )
            {
                echo '<strong>', htmlspecialchars($image->diapimg_nom), '</strong><br />';
            }
            if ( !empty($image->diapimg_description) )
            {
                echo htmlspecialchars($image->diapimg_description);
            }
            echo '</p>';
        }
        echo '</li>';
    }

?>
  </ul>
</div>
</div>
