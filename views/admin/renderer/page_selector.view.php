<?php
Nos\I18n::current_dictionary('noviusos_slideshow::common');
?>

<p id="<?= $uniqid = uniqid('id_'); ?>">
    <a href="#" class="add_link_to" style="<?= empty($value) ? '' : 'display:none;' ?>"><?= __('Add a link'); ?></a>
    <div class="link_to" style="<?= empty($value) ? 'display:none;' : '' ?>">
        <?= $page_renderer ?>
    </div>
    <a href="#" class="remove_link_to link_to" style="<?= empty($value) ? 'display:none;' : '' ?>"><?= __('Remove the link'); ?></a>
</p>

<script type="text/javascript">
require(['jquery-nos'], function($) {
    "use strict";

    var $container = $('#<?= $uniqid ?>');
    var $add_link_to = $container.find('.add_link_to');
    var $link_to = $container.find('.link_to');
    var $remove_link_to = $container.find('.remove_link_to');

    $add_link_to.on('click', function (e) {
        e.preventDefault();

        $link_to.show().nosOnShow();
        $add_link_to.hide();
    });

    $remove_link_to.on('click', function (e) {
        e.preventDefault();

        $link_to.find(':radio').first().prop('checked', true);
        $link_to.hide();
        $add_link_to.show();
    });
});
</script>

