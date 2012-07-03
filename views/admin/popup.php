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
	<form method="POST" action="admin/diaporama/preview">
		<div class="line myBody">
			<div class="unit col c1"></div>
			<div class="unit col c10 ui-widget">
				<div class="expander">
					<h3>Diaporama</h3>
					<div>

<?php
                    if ( !empty($diaporamas) )
                    {
                        echo '<select name="diaporama_id">';
                        foreach ( $diaporamas as $diaporama )
                        {
                            echo '<option value="', $diaporama->diaporama_id, '" ',
                                ( \Fuel\Core\Input::get('diaporama_id', 0) === $diaporama->diaporama_id ? 'selected="selected"' : '' ), '>',
                                $diaporama->diaporama_nom, '</option>';
                        }
                        echo '</select>';
                    }
                    else
                    {
                        echo '<p>Aucun diaporama disponible.</p>';
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
                        echo '<p>Le format d\'afficahge des diaporama n\est pas configuré, merci de contacter un adminisitrateur</p>';
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
	], function($nos) {
		$nos(function() {
			var div = $nos('#<?= $id ?>')
				.find('a[data-id=close]')
				.click(function(e) {
					div.closest('.ui-dialog-content').wijdialog('close');
					e.preventDefault();
				})
				.end()
				.find('form')
				.submit(function() {
					var self = this;
					$nos(self).ajaxSubmit({
						dataType: 'json',
						success: function(json) {
							div.closest('.ui-dialog-content').trigger('save.enhancer', json);
						},
						error: function(error) {
							$nos.notify('An error occured', 'error');
						}
					});
					return false;
				})
				.form();
		});
	});
</script>

