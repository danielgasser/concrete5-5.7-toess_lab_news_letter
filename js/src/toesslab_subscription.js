/**
 * Created by toesslab on 25.05.17.
 */
(function ($) {
    $(document).ready(function () {
        $('#sub_select').selectable({
            filter: 'li',
            distance: 30,
            autoRefresh: true,
            selecting: function (e, ui) {
                var counter = $('#sub_select .ui-selecting').length + $('#sub_select .ui-selected').length;
                $('#sub_selected').text(counter);
                if (counter > 0) {
                    $('#sub_select_move').attr('disabled', false);
                    $('#sub_select_unselect').attr('disabled', false);
                }
            }
        });
        $('#un_sub_select').selectable({
            filter: 'li',
            distance: 30,
            autoRefresh: true,
            selecting: function (e, ui) {
                var counter = $('#un_sub_select .ui-selecting').length + $('#un_sub_select .ui-selected').length;
                $('#un_sub_selected').text(counter);
                if (counter > 0) {
                    $('#un_sub_select_move').attr('disabled', false);
                    $('#un_sub_select_unselect').attr('disabled', false);
                }
            }
        });
        $(document).on('click', '#sub_select_move, #un_sub_select_move', function (e) {
            e.preventDefault();
            subscribe($(this).attr('id'));
        });
        $(document).on('keyup', '#search_sub, #search_un_sub', function (e) {
            e.preventDefault();
            var id = $(this).attr('id'),
                value = $(this).val(),
                sub = (id.indexOf('un_') === -1) ? '' : 'un_';
            $('#' + sub + 'sub_select>li').removeClass('ui-selected');
            $('#' + sub + 'sub_select>li:contains(' + value + ')').addClass('ui-selected');
            $('#' + sub + 'sub_select_move').attr('disabled', false);
            $('#' + sub + 'sub_select_unselect').attr('disabled', false);
            $('#' + sub + 'sub_selected').text($('.ui-selected').length);
        });
        $(document).on('click', '#sub_select_unselect, #un_sub_select_unselect', function (e) {
            e.preventDefault();
            var id = 'sub_select>li.ui-selected',
                unsub = '';
            if ($(this).attr('id').indexOf('un_') > -1) {
                id = 'un_' + id;
                unsub = 'un_';
            }
            $.each($('#' + id), function (i, n) {
                $(n).removeClass('ui-selected');
            });
            if ($('#' + id).length === 0) {
                $('#' + unsub + 'sub_select_move').attr('disabled', true);
                $('#' + unsub + 'sub_select_unselect').attr('disabled', true);
            } else {
                $('#' + unsub + 'sub_select_move').attr('disabled', false);
                $('#' + unsub + 'sub_select_unselect').attr('disabled', false);
            }
            $('#' + unsub + 'sub_selected').text($('#' + id).length);

        });
        $(document).on('click', '#un_sub_select_select', function (e) {
            e.preventDefault();
            var id = '#un_sub_select>li.ui-selectee';
            $.each($(id), function (i, n) {
                $(n).addClass('ui-selected');
            });
            $('#un_sub_select_move').attr('disabled', false);
            $('#un_sub_select_unselect').attr('disabled', false);
            $('#un_sub_selected').text($(id).length);
        });
        $(document).on('click', '#sub_select_select', function (e) {
            e.preventDefault();
            var id = '#sub_select>li.ui-selectee';
            $.each($(id), function (i, n) {
                $(n).addClass('ui-selected');
            });
            $('#sub_select_move').attr('disabled', false);
            $('#sub_select_unselect').attr('disabled', false);
            $('#sub_selected').text($(id).length);
        });
        $(document).on('click', '#sub_select>li, #un_sub_select>li', function (e) {
            $(this).toggleClass('ui-selected');
            var counter = 0;
            if ($(this).parent().attr('id').indexOf('un') > -1) {
                counter = $('#un_sub_select .ui-selecting').length + $('#un_sub_select .ui-selected').length;
                $('#un_sub_selected').text(counter)
                if (counter === 0) {
                    $('#un_sub_select_move').attr('disabled', true);
                    $('#un_sub_select_unselect').attr('disabled', true);
                } else {
                    $('#un_sub_select_move').attr('disabled', false);
                    $('#un_sub_select_unselect').attr('disabled', false);
                }
            } else {
                counter = $('#sub_select .ui-selecting').length + $('#sub_select .ui-selected').length;
                $('#sub_selected').text(counter)
                if (counter === 0) {
                    $('#sub_select_move').attr('disabled', true);
                    $('#sub_select_unselect').attr('disabled', true);
                } else {
                    $('#sub_select_move').attr('disabled', false);
                    $('#sub_select_unselect').attr('disabled', false);
                }
            }
        })
    });

    var subscribe = function (id) {
            var altId = 'sub_select',
                emails;
            if (id.indexOf('un') === -1) {
                altId = 'un_' + altId;
            }
            $('.ui-selected').prependTo('#' + altId);
            $('#sub_fillListLength').text($('#sub_select>li').length)
            $('#un_sub_fillListLength').text($('#un_sub_select>li').length)
            $('#un_sub_selected').text($('#un_sub_select .ui-selecting').length + $('#un_sub_select .ui-selected').length)
            $('#sub_selected').text($('#sub_select .ui-selecting').length + $('#sub_select .ui-selected').length)
            emails = collectElements(altId);
            updateSubscriptions(altId, emails, false);
        },
        fillList = function (data) {
        var html = '';
        if (data.sub === null || data.unsub === null) return false;
        $.each(data, function (i, n) {
            html += '<div class="row">'
                + '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 checkbox form-group">'
                + '<label>'
                + '<input type="checkbox" id="emailIDs_' + n.uID + '" name="emailIDs[]" class="ccm-input-checkbox" value="' + n.uID + '" checked="checked">'
                + n.uEmail
                + '</label>'
                + '</div>'
                + '</div>';
        });
        $('#fillListLength').html(data.length);
        $('#fillList').html(html);
    },
    updateSubscriptions = function (id, emails, fromInput) {
        var sub = (id.indexOf('un_') === -1),
            un_sub_sub = (sub) ? window.sub_text : window.un_sub_text,
            sub_msg_text = emails.length + ' ' + window.dialog_msg_text_success + ' ' + un_sub_sub;
        if (fromInput) {
            emails = $('#mailFilter').val().split('\n');
        }
        if ($('#mailFilter').val().length === 0 && fromInput) {
            $('#dialog_msg').html(window.empty_text);
            $('#dialog_msg').dialog({
                title: window.dialog_msg_title_error,
                width: 500,
                height: 'auto',
                modal: true,
                position: {
                    my: 'center center',
                    at: 'center center'
                }
            });
            return false;
        }
        $.ajax({
            url: window.url,
            method: 'POST',
            data: {
                emailIDs: JSON.stringify(emails),
                sub: sub
            },
            beforeSend: function () {
                jQuery.fn.dialog.showLoader();
            },
            success: function (data) {
                jQuery.fn.dialog.hideLoader();
                var dats = $.parseJSON(data);
                if (dats.hasOwnProperty('error')) {
                    $('#dialog_msg').html(dats.error);
                    $('#dialog_msg').dialog({
                        title: window.dialog_msg_title_error,
                        width: 500,
                        height: 'auto',
                        modal: true,
                        position: {
                            my: 'center center',
                            at: 'center center'
                        }
                    });
                    return false;
                }
                fillList(dats.success);
                $('#dialog_msg').html(sub_msg_text);
                $('#dialog_msg').dialog({
                    title: window.dialog_msg_title_success,
                    width: 500,
                    height: 'auto',
                    modal: true,
                    position: {
                        my: 'center center',
                        at: 'center center'
                    }
                });
                $('li').removeClass('ui-selected');
                $('#sub_select_move').attr('disabled', true);
                $('#sub_select_unselect').attr('disabled', true);
                $('#un_sub_select_move').attr('disabled', true);
                $('#un_sub_select_unselect').attr('disabled', true);
                $('#un_sub_selected').text(0);
                $('#sub_selected').text(0);
            }
        })
    },
    collectElements = function (id) {
        var emails = [],
            el;
        if (id.indexOf('un_') > -1) {
            el = '#un_sub_select>li';
        } else {
            el = '#sub_select>li';
        }
        $.each($(el), function (i, n) {
            emails.push(n.innerText)
        });
        return emails;
    };

    $(document).on('click', '[id$="sub_members"]', function (e) {
        e.preventDefault();
        var emails = collectElements($(this).attr('id'));
        updateSubscriptions($(this).attr('id'), emails, true);
    });
}(jQuery));
