define(
    [
        'jquery-nos',
        'jquery-ui.sortable',
        'jquery-ui.resizable'
    ],
    function($) {
        "use strict";
        return function(id, options) {

            var $container = $(id);
            var $preview_container = $container.find('.preview_container');
            var $slides_container = $container.find('.slides_container');
            var media_options = $.extend(true, options.mediaSelector, $slides_container.children(':last').find('input.media').data('media-options') || {});
            media_options.inputFileThumb.file = null;
            var field_index = $slides_container.children('.field_enclosure').length ;
            var $slide_model =  $slides_container.find('div.slideshow_model');
            $slides_container.show();

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

                $slides_container.append($newimg);
                on_field_added($newimg);
                init_links_to($newimg, field_index);
                on_focus_preview(get_preview($newimg));
                $(this).removeClass('ui-state-focus');
            });

            function on_field_added($field) {
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
                $preview_container.append($preview);
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
                $slides_container.find('.show_hide').show();
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
                    var diff = -24; // 29 = arrow height
                    var pos = $focus.position();
                    $slides_container.css({
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
            $slides_container.on('click', '[data-id=delete]', function on_delete(e) {
                e.preventDefault();
                // Don't bubble to .preview container
                e.stopPropagation();

                var $self = $preview_container.find('li.ui-state-active').data('field'),
                media_id = $self.find('input.media').val();

                var remove_image = function () {
                    // On en garde toujours au moins une image dans le dom
                    if ($slides_container.children().length > 1) {
                        $self.remove();
                    } else {
                        $self.find('textarea, input').val('');
                    }
                };

                // On fait une confirmation que si on supprime une "vraie" image
                if (media_id && media_id > 0) {
                    $container.nosAction('confirmationDialog', {
                        dialog : $.extend(true, options.dialogDelete, {
                            confirmed: remove_image
                        })
                    });
                } else {
                    remove_image();
                }
            });

            $slides_container.on('change', 'input[name*="media_id"]', function on_media_change(e, data) {
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

            $container.find('ul.preview_container').sortable();

            function blur() {
                var $preview = $preview_container.find('.ui-widget-content.ui-state-active');
                if ($preview.length == 0) {
                    return;
                }
                $preview.removeClass('ui-state-active');
                hide_field($preview.data('field'));
            }

            function hide_field($field) {
                $slides_container.find('.show_hide').hide();
                if ($field) {
                    $field.hide();
                }
            }
            $slides_container.find('.show_hide').hide();

            $slides_container.on('click', '.add_link_to', function (e) {
                e.preventDefault();

                var $field = $(this).closest('.field_enclosure');
                var $link_to = $field.find('.link_to');
                $link_to.show().nosOnShow();
                $field.find('.add_link_to').hide();
            });

            $slides_container.on('click', '.remove_link_to', function (e) {
                e.preventDefault();

                var $field = $(this).closest('.field_enclosure');
                var $link_to = $field.find('.link_to');
                $link_to.find(':radio').first().prop('checked', true);
                $link_to.hide();
                $field.find('.add_link_to').show();
            });


            function init_links_to($field, index) {
                // Don't transform the model
                if ($field.is('.slideshow_model')) {
                    return;
                }
                var $this = $field.find('.transform_renderer_page_selector');
                // Clone
                var params = $.extend(true, {}, $this.data('renderer_page_selector'), {
                    input_name: 'images[' + index + '][slidimg_link_to_page_id]'
                });
                var id = 'id_' + (new Date().getTime()).toString(16);
                $this.attr('id', id);
                require(['jquery-nos-inspector-tree-model-radio'], function() {
                    $this.nosInspectorTreeModelRadio(params);
                });
            }

            $slides_container.children('.field_enclosure').each(function onEachFields() {
                var $field = $(this);
                on_field_added($field);
                init_links_to($field, find_field($field, '_id').val());
                $field.hide();
            });
        };
    });