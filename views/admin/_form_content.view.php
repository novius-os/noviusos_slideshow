<?php
/**
 * NOVIUS OS - Web OS for digital communication
 *
 * @copyright  2011 Novius
 * @license    GNU Affero General Public License v3 or (at your option) any later version
 *             http://www.gnu.org/licenses/agpl-3.0.html
 * @link http://www.novius-os.org
 */

\Config::load('noviusos_slideshow::slideshow', 'slideshow');
$show_link = \Config::get('slideshow.slides_with_link');

$form_id = 'slideshow_'.uniqid(true);

?>
<link rel="stylesheet" href="static/apps/noviusos_slideshow/css/admin.css" />

<div id="<?= $form_id ?>">
<?php

// Retourne un "bloc" (une image avec ses infos)
function slidimg($i, $image = null, $is_model = false, $show_link = false)
{
    $media_id = 0;
    if (!empty($image) && !empty($image->medias->image) && !empty($image->medias->image->media_id)) {
        $media_id = $image->medias->image->media_id;
    }

    if ($is_model) {
        $media = '';
    } else {
        $media = \Nos\Renderer_Media::renderer(
            array(
                'name' => 'images['.$i.'][media_id]',
                'value' => $media_id,
                'renderer_options' => array(
                    'inputFileThumb' => array(
                        'title' => __('Image'),
                    ),
                ),
            )
        );
    }

    $view = \View::forge(
        'noviusos_slideshow::admin/_form_image',
        array(
            'i' => $i,
            'image' => $image,
            'show_link' => $show_link,
            'is_model' => $is_model,
        )
    );
    $view->set_safe('media', $media);

    return $view;
}
?>
    <div class="line">
        <div class="col c8" style="position:relative;">
            <ul class="preview_container">
            </ul>
            <button class="primary" style="float:left; width: 168px; height: 168px; margin: 3px;" data-icon="plus" data-id="add" data-params="<?= e(json_encode(array('where' => 'bottom'))) ?>"><?= __('Add a slide') ?></button>
            <br style="clear:both;" />
        </div>

        <div class="col c4 fields_container" style="display:none;">
            <img class="preview_arrow show_hide" src="static/apps/noviusos_slideshow/img/arrow-edition.png" />
            <p class="actions show_hide">
                <button type="button" data-icon="trash" data-id="delete" class="action"><?= ('Delete') ?></button>
            </p>
<?php
// Model pour ajouter une nouvelle image
$field_index = 1;
echo slidimg($field_index++, null, true);

// Liste des images actuelles
foreach ($item->images as $img) {
    echo slidimg($field_index++, $img, false, $show_link);
}

// Ajouter une nouvelle image (champ vide)
//echo slidimg($field_index++, null, false, $show_link);
?>
        </div>
    </div>
</div>

<script type="text/javascript">
require(['jquery-nos', 'jquery-ui.sortable'], function ($) {
    $(function () {

        var $container = $('#<?= $form_id; ?>');
        var $preview_container = $container.find('.preview_container');
        var $fields_container = $container.find('.fields_container');
        var media_options = $fields_container.children(':last').find('input.media').data('media-options') || { mode: 'image'};
        media_options.inputFileThumb = media_options.inputFileThumb || {
            title: <?= \Format::forge()->to_json(__('Image')) ?>
        };
        media_options.inputFileThumb.file = null;
        var field_index = $fields_container.children().length + 1
        var $slide_model =  $fields_container.find('div.slideshow_model');
        $fields_container.show();

        // Add a field
        $container.on('click', '[data-id=add]', function onAdd(e) {
            e.preventDefault();

            var $form = $(this).closest('form'),
                $newimg = $slide_model.clone(true).removeClass('slideshow_model').find('*').removeAttr('id').end();

            // On doit vider les champs du nouveau bloc, et re-indexer leur nom (index du tableau $_POST['images'])
            // L'idée est que les attr('name') de chaque input/textarea d'un même bloc aient le même index
            field_index++;
            $newimg.find('textarea, input').val('').each(function () {
                var _name = $(this).attr('name');
                if (_name && _name.length > 0) {
                    var re = new RegExp(/^images\[([0-9]+)\]\[([a-z_]+)\]$/g),
                            m = re.exec(_name);
                    m && $(this).attr('name', 'images[' + field_index + '][' + m[2] + ']');
                }
            });

            // Supprime/Regenere le champs media
            /*
            var $media_input = $newimg.find('input.media').removeAttr('id').removeData().removeAttr('data-media-options').off();
            $media_input.closest('.ui-widget').replaceWith($media_input);
            $media_input.media(media_options);
            */
            $newimg.find('input.media').nosMedia(media_options);


            // Fix : Passer le bouton Add en inline-block...
            $newimg.find('div.ui-inputfilethumb-fileactions').children(':first').css('display', 'inline-block');

            $fields_container.append($newimg);
            on_field_added($newimg, {where: 'bottom'});
            on_focus_preview(get_preview($newimg));
            $(this).removeClass('ui-state-focus');
        });



        function on_field_added($field, params) {
            if ($field.is('.slideshow_model')) {
                return;
            }

            // Make checkbox fill a hidden field instead (we're sending an array, we don't want "missing" values)
            $field.find('input[type=checkbox]').each(function normaliseCheckboxes() {
                var $checkbox = $(this);
                var name     = $checkbox.attr('name');
                var $hidden   = $('<input type="hidden" value="" />');
                $hidden.insertAfter($checkbox);
                $checkbox.on('change', function() {
                    $hidden.attr('name', $(this).is(':checked') ? '' : name);
                }).trigger('change');
            });

            var $preview = get_preview($field);
            $preview_container[params.where == 'top' ? 'prepend' : 'append']($preview);
            generate_preview.call($field.get(0), {});
        }

        function get_preview($field) {

            var $preview = $field.data('preview');
            if ($preview) {
                return $preview;
            }

            // Generate a new preview
            $preview = $('<li class="ui-widget-content"></li>');

            $field.data('preview', $preview);
            $preview.data('field', $field);

            $preview.find('button.notransform').removeClass('notransform');
            $preview.nosFormUI().show().nosOnShow();
            $preview.find('input, select').on('click', function(e) {
                e.preventDefault();
            });

            return $preview;
        }

        function on_focus_preview(preview) {
            var $preview = $(preview);
            var $field = $preview.data('field');

            // Make the preview look "active"
            $preview_container.find('li').removeClass('ui-state-active');
            $preview.addClass('ui-state-active');

            // Show the appropriate field and position it
            show_field($field);
        }

        function show_field($field) {
            $fields_container.find('.show_hide').show();
            if ($field.is('.field_enclosure')) {
                $field.show();
                $field.nosOnShow();
            }
            $field.siblings('.field_enclosure').hide();
            set_field_padding();
        }

        function set_field_padding($focus) {

            $focus = $focus || $preview_container.find('li.ui-state-active');
            if ($focus.length > 0) {
                var diff = -29 + 29; // 29 = arrow height
                var pos = $focus.position();
                $fields_container.css({
                    paddingTop: Math.max(0, pos.top + diff) + 'px'
                });
            }
        }

        $preview_container.on('click', 'li', function onClickPreview(e) {
            e.preventDefault();
            on_focus_preview(this);
        });




        // Delete a preview + field
        function delete_preview() {
            var $preview = $(this);
            var $field = $preview.data('field');
            $field.remove();
            hide_field();
            $preview.addClass('ui-state-error').hide(500, function() {
                $preview.remove();
            });
        }

        // Delete listener
        $fields_container.on('click', '[data-id=delete]', function on_delete(e) {
            e.preventDefault();
            // Don't bubble to .preview container
            e.stopPropagation();

            var $self = $preview_container.find('li.ui-state-active').data('field'),
                media_id = $self.find('input.media').val();

            var remove_image = function () {
                // On en garde toujours au moins une image dans le dom
                if ($fields_container.children().length > 1) {
                    $self.remove();
                }
                else {
                    $self.find('textarea, input').val('');
                }
            };

            // On fait une confirmation que si on supprime une "vraie" image
            if (media_id && media_id > 0) {
                $container.nosAction('confirmationDialog', {
                    dialog : {
                        content: <?= Format::forge()->to_json('Are you sure you want to delete this image?') ?>,
                        title: <?= Format::forge()->to_json('Delete an image') ?>,
                        confirmed: remove_image
                    }
                });
            }
            else {
                remove_image();
            }
        });

        $fields_container.on('change', 'input[name*="media_id"]', function on_media_change(e, data) {
            var $field = $(this).closest('.field_enclosure');
            generate_preview.call($field.get(0), data ? data.item : {});
        });

        function find_field($context, field_name) {
            // * = matching a substring
            return $context.find('[name*=' + field_name + ']');
        }

        function generate_preview(item) {
            var $field = $(this).closest('.field_enclosure');
            var media_id = find_field($field, 'media_id').val();
            var $preview = $field.data('preview');
            var html  = '';

            var thumbnail = item.thumbnail ? item.thumbnail.replace(/64-64/g, '160-160') : '';
            if (thumbnail == '') {
                thumbnail = find_field($field, 'thumb').val()
            }
            if (thumbnail == '') {
                thumbnail = find_field($slide_model, 'thumb').val()
            }

            html += '<img src="' + thumbnail + '" />';

            $preview.html(html);
        }


        var $sortable =  $container.find('ul.preview_container').sortable();

        function blur() {
            var $preview = $preview_container.find('.ui-widget-content.ui-state-active');
            if ($preview.length == 0) {
                return;
            }
            $preview.removeClass('ui-state-active');
            hide_field($preview.data('field'));
        }

        function hide_field($field) {
            $fields_container.find('.show_hide').hide();
            if ($field) {
                $field.hide();
            }
        }


        $fields_container.children('.field_enclosure').each(function onEachFields() {
            var $field = $(this);
            on_field_added($field, {where: 'bottom'});
            $field.hide();
        });
        $fields_container.find('.show_hide').hide();


        $fields_container.on('click', '.add_link_to', function (e) {
            e.preventDefault();

            var $field = $(this).closest('.field_enclosure');
            var $link_to = $field.find('.link_to');
            $link_to.show().nosOnShow('show');
            $field.find('.add_link_to').hide();
        });
        $fields_container.on('click', '.remove_link_to', function (e) {
            e.preventDefault();

            var $field = $(this).closest('.field_enclosure');
            var $link_to = $field.find('.link_to');
            $link_to.find(':radio').first().prop('checked', true);
            $link_to.hide();
            $field.find('.add_link_to').show();
        });
    });
});
</script>

<script type="text/javascript">
    require(['jquery-nos', 'jquery-ui.sortable'], function ($) {
        $(function () {

            var $container = $('#<?= $form_id; ?>');

            // Changement titre du tab
            var tabInfos = {
                label: <?= \Format::forge()->to_json($item->is_new() ? __('Add a slideshow') : $item->slideshow_title) ?>,
                iconUrl:'static/apps/noviusos_slideshow/img/slideshow-16.png'
            };
            $container.nosOnShow('bind', function () {
                $container.nosTabs('update', tabInfos);
            });
        });
    });
</script>
