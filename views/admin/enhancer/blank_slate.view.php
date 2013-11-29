<?php
/**
 * NOVIUS OS - Web OS for digital communication
 *
 * @copyright  2011 Novius
 * @license    GNU Affero General Public License v3 or (at your option) any later version
 *             http://www.gnu.org/licenses/agpl-3.0.html
 * @link http://www.novius-os.org
 */
$id = uniqid('slideshow_');

Nos\I18n::current_dictionary('noviusos_slideshow::common');

$sites = \Nos\Tools_Context::sites();
if ($params['slideshow_count'] === 0) {
    $message = __('How frustrating, you have no slideshow to insert. But let’s not worry, shall we? Here is how it works:').
        strtr(
            __(
                '<ul><li><a>Add your first slideshow</a> (a new tab will open).</li>
<li>Once you’re done (it won’t take long), come back to this tab to insert your shiny new slideshow.</li></ul>'
            ),
            array(
                '<ul>' => '<ul style="margin:0.5em 0 0.5em 1em; list-style:disc none outside; line-height:1.2em;">'
            )
        );
} else if (count($sites) === 1) {
    $message = __('No slideshows are available in {{context}}. Go ahead, <a>add your first slideshow in this language</a>.');
} else {
    $message = __('No slideshows are available in {{context}}. Go ahead, <a>add your first slideshow in this context</a>.');
}
?>
<p>&nbsp;</p>
<p style="line-height:1.2em;">
    <?= strtr($message, array(
        '{{context}}' => \Nos\Tools_Context::contextLabel($params['_parent_context']),
        '<a>' => '<a href="#" id="'.$id.'">',
    ));
    ?>
</p>
<script type="text/javascript">
    require(
        ['jquery-nos'],
        function($) {
            $(function() {
                $('#<?= $id ?>').on('click', function(e) {
                    e.preventDefault();
                    $(this).nosDialog('close');
                    $(this).nosTabs('open', {
                        url: 'admin/noviusos_slideshow/slideshow/insert_update?context=<?= $params['_parent_context'] ?>'
                    });
                });
            });
        });
</script>
