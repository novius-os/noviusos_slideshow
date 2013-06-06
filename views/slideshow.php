<?php
/**
 * NOVIUS OS - Web OS for digital communication
 *
 * @copyright  2011 Novius
 * @license    GNU Affero General Public License v3 or (at your option) any later version
 *             http://www.gnu.org/licenses/agpl-3.0.html
 * @link http://www.novius-os.org
 */

\Nos\Nos::main_controller()->addJavascript('static/apps/noviusos_slideshow/js/jquery.min.js');
\Nos\Nos::main_controller()->addJavascript('static/apps/noviusos_slideshow/js/jquery.flexslider.js');
\Nos\Nos::main_controller()->addCss('static/apps/noviusos_slideshow/css/flexslider.css');
if ($slides_preview) {
    \Nos\Nos::main_controller()->addJavascript('static/apps/noviusos_slideshow/js/jquery.novius_flexpreview.js');
    \Nos\Nos::main_controller()->addCss('static/apps/noviusos_slideshow/css/flexpreview.css');
}
$config = \Config::load('noviusos_slideshow::flexslider', true);
\Nos\Nos::main_controller()->addJavascriptInline(\View::forge('noviusos_slideshow::slideshow_js', array('config' => $config, 'slides_preview' => $slides_preview)));
?>
<div class="noviusos_slideshow noviusos_enhancer flex-nav-container <?=$class?>">
<div class="flexslider">
    <ul class="slides">
<?php
foreach ($slideshow->images as $image) {
    if ( empty($image->medias->image) ) {
        continue;
    }

    echo '<li ';
    if ($slides_preview) {
        echo 'data-thumb="', $image->medias->image->get_public_path_resized(300, 100), '"';
    }
    echo '>';

    // Image, avec ou sans lien
    if ( $show_link && !empty($image->slidimg_link_to_page_id) ) {
        echo '<a href="'. $image->page->url().'">';
    }
    echo '<img style="margin: 0 auto;" src="', $image->medias->image->get_public_path_resized($width, $height), '" alt="', htmlspecialchars($image->slidimg_title), '" title="', htmlspecialchars($image->slidimg_title), '" />';
    if ( $show_link && !empty($image->slidimg_link_to_page_id) ) {
        echo '</a>';
    }

    // Caption
    if ( !empty($image->slidimg_title) || !empty($image->slidimg_description) ) {
        echo '<p class="flex-caption">';
        if ( !empty($image->slidimg_title) ) {
            echo '<strong>', htmlspecialchars($image->slidimg_title), '</strong><br />';
        }
        if ( !empty($image->slidimg_description) ) {
            echo htmlspecialchars($image->slidimg_description);
        }
        echo '</p>';
    }
    echo '</li>';
}
?>
  </ul>
</div>
</div>
