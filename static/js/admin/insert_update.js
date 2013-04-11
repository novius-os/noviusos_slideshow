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
                var $that = $(this);
                $.ajax({
                    url: 'admin/noviusos_slideshow/slideshow/image_fields',
                    dataType: 'json',
                    success: function(json) {
                        var $slide = $(json.slide);
                        $slide.nosFormUI();
                        $slides_container.append($slide);

                        // Open the media centre directly
                        setTimeout(function openMediaCentre() {
                            var $media = find_field($slide, 'media_id');
                            if ($media.data('inputFileThumb')) {
                                $media.data('inputFileThumb').choose();
                            } else {
                                setTimeout(openMediaCentre, 10);
                            }
                        }, 10);

                        on_field_added($slide);
                        on_focus_preview(get_preview($slide));
                        $that.removeClass('ui-state-focus');
                    }
                });
            });

            function on_field_added($field) {
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
                    media_id = $field.find('input[name*="media_id"]').val();

                var remove_image = function () {
                    delete_preview.call($preview);
                };

                // Only ask a confirmation when a media is actually selected
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
                var $media_id = find_field($field, 'media_id');
                var media_id = $media_id.val();
                var $preview = $field.data('preview');
                var media_options = $media_id.data('media-options') || {};

                var thumbnail = item.thumbnail ? item.thumbnail : (media_options.inputFileThumb ? media_options.inputFileThumb.file : '');
                if (thumbnail == '') {
                    thumbnail = find_field($field, 'thumb').val()
                }
                if (thumbnail == '') {
                    thumbnail = find_field($slide_model, 'thumb').val()
                }
                if (!thumbnail) {
                    var img = $field.find('img[src*=64-64]');
                    if (img.length) {
                        thumbnail = $(img).attr('src');
                    }
                }
                if (thumbnail) {
                    thumbnail = thumbnail.replace(/64-64/g, '160-160');
                } else {
                    thumbnail = "static/novius-os/admin/vendor/jquery/jquery-ui-input-file-thumb/css/images/apn.png"
                }

                $preview.find('.preview_content').html('<img src="' + thumbnail + '" />');
            }

            $container.find('ul.preview_container').sortable({
                update: reorderSlides
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

            $slides_container.children('.field_enclosure').each(function onEachFields() {
                var $field = $(this);
                on_field_added($field);
                $field.hide();
            });

            if (is_new) {
                $container.find('[data-id=add]').click();
            }
        };
    });
