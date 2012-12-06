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
<div class="expander">
    <h3>Slideshow</h3>
    <div>
<?php
if ( !empty($params['slideshows']) ) {
    echo '<select name="slideshow_id">';
    foreach ($params['slideshows'] as $slideshow) {
        echo '<option value="', $slideshow->slideshow_id, '" ',
            ( \Fuel\Core\Input::get('slideshow_id', 0) === $slideshow->slideshow_id ? 'selected="selected"' : '' ), '>',
            $slideshow->slideshow_title, '</option>';
    }
    echo '</select>';
} else {
    echo '<p>Aucun slideshow disponible.</p>';
}
?>
    </div>
</div>
<?php
if ( !empty($params['sizes']) ) {
    if (count($params['sizes']) === 1) {
        echo '<input type="hidden" name="size" value="'.current(array_keys($params['sizes'])).'" />';
    } else {
        echo '<div class="expander"><h3>Format</h3><div>';
        echo '<select name="size">';
        foreach ($params['sizes'] as $key => $s) {
            echo '<option value="', $key, '" ',
                ( \Fuel\Core\Input::get('size', 0) === $key ? 'selected="selected"' : '' ), '>',
                $key, '</option>';
        }
        echo '</select></div>';
    }
} else {
    echo '<p>Le format d\'afficahge des slideshow n\est pas configuré, merci de contacter un adminisitrateur</p>';
}
