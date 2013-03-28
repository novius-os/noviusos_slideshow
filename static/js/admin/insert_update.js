define(
    [
        'jquery-nos',
        'jquery-ui.sortable',
        'jquery-ui.resizable'
    ],
    function($) {
        "use strict";
        return function(id, options, is_new) {

            var $container = $(id);
            var $preview_container = $container.find('.preview_container');
            var $slides_container = $container.find('.slides_container');
            var media_options = $.extend(true, options.mediaSelector, $slides_container.children(':last').find('input.media').data('media-options') || {});
            media_options.inputFileThumb.file = null;
            var field_index = $slides_container.children('.field_enclosure').length ;
            var $slide_model =  $slides_container.find('div.slideshow_model');
            $slides_container.show();

            function reorderSlides() {
                $preview_container.find('li.preview').each(function() {
                    $(this).data('field').appendTo($slides_container);
                });
            }

            // Add a field
            $container.on('click', '[data-id=add]', function onAdd(e) {
                e.preventDefault();

                var $form = $(this).closest('form'),
                // Changer les trucs à partir d'ici.
                $newimg = $slide_model.clone().removeClass('slideshow_model').find('*').removeAttr('id').end().nosFormUI();

                // On doit vider les champs du nouveau bloc, et re-indexer leur nom (index du tableau $_POST['images'])
                $.ajax({
                    url: 'admin/noviusos_slideshow/slideshow/image_fields/',
                    dataType: 'json',
                    success: function(json) {
                        console.log(json);
                        /*$blank_slate.hide();
                        var $fields = $(json.fields).filter(function() {
                            return this.nodeType != 3; // 3 == Node.TEXT_NODE
                        });
                        if (params.where == 'top') {
                            $fields = $($fields.get().reverse());
                        }
                        var $previews = $(); // $([]) in jQuery < 1.4
                        $fields_container.append($fields);
                        $fields.nosFormUI();
                        $fields.each(function() {
                            var $field = $(this);
                            on_field_added($field, params);
                            $field.hide();
                            $previews = $previews.add($field.data('preview'));
                        });
                        apply_layout(json.layout);
                        init_all();
                        nos_fixed_content.scrollTop = old_scroll_top;
                        $previews.addClass('ui-state-hover');
                        setTimeout(function() {
                            $previews.removeClass('ui-state-hover');
                        }, 500);*/
                    }
                });


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

                $slides_container.append($newimg);
                $newimg.find('input.media').nosMedia(media_options);

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
                $preview = $('<li class="preview ui-widget-content"><div class="handle ui-widget-header">' +
                    '<img src="static/apps/noviusos_slideshow/img/move-handle-dark3.png">' +
                    '</div><span class="preview_content"></span></li>');

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
                    var diff = -40;
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

                var $preview = $preview_container.find('li.ui-state-active'),
                    $field = $preview.data('field'),
                    media_id = $field.find('input.media').val();

                var remove_image = function () {
                    delete_preview.call($preview);
                };

                // On ne fait une confirmation que si on supprime une "vraie" image
                if (media_id && parseInt(media_id) > 0) {
                    if (confirm($.nosCleanupTranslation(options.textDelete))) {
                        remove_image();
                    }
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

                $preview.find('.preview_content').html(html);
            }

            $container.find('ul.preview_container').sortable({
                update: function(event, ui) {
                    reorderSlides();
                }
            });

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

            if (is_new) {
                $container.find('[data-id=add]').click();
            }
        };
    });