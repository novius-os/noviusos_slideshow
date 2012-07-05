<?php
/**
 * NOVIUS OS - Web OS for digital communication
 *
 * @copyright  2011 Novius
 * @license    GNU Affero General Public License v3 or (at your option) any later version
 *             http://www.gnu.org/licenses/agpl-3.0.html
 * @link http://www.novius-os.org
 */
	$id = uniqid('temp_');

?>
<div id="<?= $id ?>">
	<form method="POST" action="admin/slideshow/preview">
		<div class="line myBody">
			<div class="unit col c1"></div>
			<div class="unit col c10 ui-widget">
				<div class="expander">
					<h3>Slideshow</h3>
					<div>

<?php
                    if ( !empty($slideshows) )
                    {
                        echo '<select name="slideshow_id">';
                        foreach ( $slideshows as $slideshow )
                        {
                            echo '<option value="', $slideshow->slideshow_id, '" ',
                                ( \Fuel\Core\Input::get('slideshow_id', 0) === $slideshow->slideshow_id ? 'selected="selected"' : '' ), '>',
                                $slideshow->slideshow_title, '</option>';
                        }
                        echo '</select>';
                    }
                    else
                    {
                        echo '<p>Aucun slideshow disponible.</p>';
                    }

?>
					</div>
				</div>
<?php
                    if ( !empty($sizes) )
                    {
                    	if (count($sizes) === 1)
                    	{
                    		echo '<input type="hidden" name="size" value="'.current(array_keys($sizes)).'" />';
                    	} else
                    	{
                    		echo '<div class="expander"><h3>Format</h3><div>';
                    		echo '<select name="size">';
                    		foreach ( $sizes as $key=>$s )
                    		{
                    		    echo '<option value="', $key, '" ',
                    		        ( \Fuel\Core\Input::get('size', 0) === $key ? 'selected="selected"' : '' ), '>',
                    		        $key, '</option>';
                    		}
                    		echo '</select></div>';
                    	}
                    }
                    else
                    {
                        echo '<p>Le format d\'afficahge des slideshow n\est pas configuré, merci de contacter un adminisitrateur</p>';
                    }
?>
			</div>
			<div class="unit lastUnit"></div>
		</div>

		<div class="line">
			<div class="unit col c1"></div>
			<div class="unit col c10 ui-widget">
				<button type="submit" data-icon="check">Save</button> or <a data-id="close" href="#">Cancel</a>
			</div>
			<div class="unit lastUnit"></div>
		</div>
	</form>
</div>
<script type="text/javascript">
require([
	'jquery-nos'
	], function($) {
		$(function() {
			var div = $('#<?= $id ?>')
				.find('a[data-id=close]')
				.click(function(e) {
					div.closest('.ui-dialog-content').wijdialog('close');
					e.preventDefault();
				})
				.end()
				.find('form')
				.submit(function() {
					var self = this;
					$(self).ajaxSubmit({
						dataType: 'json',
						success: function(json) {
							div.closest('.ui-dialog-content').trigger('save.enhancer', json);
						},
						error: function(error) {
							$.nosNotify('An error occured', 'error');
						}
					});
					return false;
				})
				.nosFormUI();
		});
	});
</script>

