/*jshint globalstrict: true*/
(function ($) {
    "use strict";
        /**
         * Counts chosen recipients
         *
         * @param setIt
         */
    var setNumRecords = function (setIt) {
            var email_checked = $('input[name="uEmail[]"]:checked'),
                num_checked = $('input[name="uEmail[]"]').length,
                selected_records = $('#selectedRecords'),
                selected_text = $('#selectedText'),
                total_records = $('#totalRecords');
            if (setIt <= 0 || setIt === undefined) {
                selected_records.text('');
                total_records.text('');
                selected_text.parent('li').hide();
                return;
            }
            selected_text.html(window.selected_users_in_group);
            total_records.text(num_checked);
            selected_records.text(email_checked.length + '/' + num_checked);
        },
        /**
         * Populates the recipients table
         *
         * @param data
         * @param el
         */
        fillUserTable = function (data, el) {
            $.each(data, function (i, n) {
                var str = '',
                    is_checked = (n.isChecked) ? 'checked="checked"' : '',
                mail_text = '<a href="mailto:' + n.uEmail + '">' + n.uEmail + '</a>',
                error_color = '',
                error_text = '<input type="checkbox" id="uEmail_' + n.uEmail + '" name="uEmail[]" class="ccm-flat-checkbox" value="' + n.uEmail + '" ' + is_checked + '>';
                str += '<tr class="' + i + '">';
                if(!testEmail(n.uEmail)){
                    is_checked = 'disabled="disabled"';
                    mail_text = '<span style="color: #d9534f">' + n.uEmail + '</span>';
                    error_color = ' style="color: #d9534f; padding-left: 0"';
                    error_text = window.invalid_email + '!&nbsp;';
                }
                str += '<td class="col-lg-1 col-md-1 col-sm-1 col-xs-12"' + error_color + '>' + error_text;
                str += '<input type="hidden" id="uAddress_' + n.uAddress + '" name="uAddress[]" value="' + n.uAddress + '">';
                str += '<input type="hidden" id="uFirstName_' + n.uFirstName + '" name="uFirstName[]" value="' + n.uFirstName + '">';
                str += '<input type="hidden" id="uLastName_' + n.uLastName + '" name="uLastName[]" value="' + n.uLastName + '">';
                str += '</td>';
                str += '<td class="col-lg-1 col-md-1 col-sm-1 col-xs-12"' + error_color + '>' + n.uName + '</td>';
                str += '<td class="col-lg-1 col-md-1 col-sm-1 col-xs-12"' + error_color + '>' + n.uAddress + '</td>';
                str += '<td class="col-lg-3 col-md-3 col-sm-3 col-xs-12"' + error_color + '>' + n.uFirstName + '</td>';
                str += '<td class="col-lg-3 col-md-3 col-sm-3 col-xs-12"' + error_color + '>' + n.uLastName + '</td>';
                str += '<td class="col-lg-4 col-md-4 col-sm-4 col-xs-12"' + error_color + '>' + mail_text + '</td>';
                str += '</tr>';
                el.append(str);
            });
        },
        /**
         * Shows attachments being sent
         *
         * @param nid
         */
        showAttachments = function (nid) {
            $.ajax({
                type: 'GET',
                url: window.get_newsletter_attachments,
                data: {
                    nl_id: nid
                },
                success: function (data) {
                    $('#attachments_for_newsletter').html($.parseJSON(data));
                }
            });

        },
        sortResults = function (prop, asc, data) {
            data.sort(function (a, b) {
                if (asc === 'asc') {
                    return (a[prop] > b[prop]) ? 1 : ((a[prop] < b[prop]) ? -1 : 0);
                }
                return (b[prop] > a[prop]) ? 1 : ((b[prop] < a[prop]) ? -1 : 0);
            });
        },
        getRecipients = function (prop, order_by) {
            var user_group = [],
                checked = [],
                user_list,
                user_list_body = $('#userList'),
                no_group = $('#userRecords'),
                prop_object = $('[data-prop="' + prop + '"]'),
                cm = (window.chosen_emails === 'null' || window.chosen_emails === undefined) ? [] : $.parseJSON(window.chosen_emails);
            $.each($('input[name^="chooseGroup"]'), function (i, n) {
                if (n.checked) {
                    user_group.push(n.value);
                }
            });
            $.each($('input[name="uEmail[]"]'), function (i, n) {
                if (n.checked) {
                    checked.push(n.value);
                }
            });
            if (order_by === 'asc') {
                prop_object.parent().removeClass('ccm-results-list-active-sort-asc');
                prop_object.parent().addClass('ccm-results-list-active-sort-desc');
                prop_object.attr('data-sort', 'desc');
                $('#isSortedBy').val('desc');
            } else {
                prop_object.parent().removeClass('ccm-results-list-active-sort-desc');
                prop_object.parent().addClass('ccm-results-list-active-sort-asc');
                prop_object.attr('data-sort', 'asc');
                $('#isSortedBy').val('asc');
            }
            $.ajax({
                type: 'POST',
                url: window.user_group_url,
                data: {
                    group_id: user_group,
                    was_checked: checked
                },
                beforeSend: function () {
                    jQuery.fn.dialog.showLoader();
                },
                success: function (data) {
                    jQuery.fn.dialog.hideLoader();
                    if (data === 'null' || data === undefined) {
                        no_group.html('<li><span id="selectedText">' + window.no_users_selected + '</span><span id="selectedRecords"></span></li>');
                        user_list_body.html('');
                        return false;
                    }
                    user_list = $.parseJSON(data);
                    if (user_list.hasOwnProperty('toesslab_receive_newsletter')) {
                        no_group.html('<li><span id="selectedText">' + window.no_users_attribute_newsletter + '</span><span id="selectedRecords"></span></li>');
                        user_list_body.html('');
                        return false;
                    }
                    if (user_list.length === 0) {
                        setNumRecords();
                        return false;
                    }
                    sortResults(prop, order_by, user_list);
                    user_list_body.html('');
                    if (cm.length > 0) {
                        $.each(user_list, function (i, n) {
                            n.isChecked = ($.inArray(n.uEmail, cm) > -1);
                        });
                    }
                    fillUserTable(user_list, user_list_body);
                    setNumRecords(user_list.length);
                }
            });
        },
        setUserAttributesLegacy = function (e, s, r, c, val) {
            var content = r.code.get(),
                new_content = '',
                target = e.currentTarget.id.replace(c + '_address_select_', ''),
                pattern;
            target = '{{' + target + '}}';
            pattern = new RegExp(target, 'g');
            if (s) {
                $('#content').focus();
                r.insert.text('{{' + val + '}}');
            } else {
                new_content = content.replace(pattern, '');
                r.code.set(new_content);
            }
        },
        setUserAttributes = function (e, s, r, c, val) {
            if (window.C5_VERSION === 0) {
                setUserAttributesLegacy(e, s, r, c, val);
                return false;
            }
            var content = r.getData(),
                new_content = '',
                target = e.currentTarget.id.replace(c + '_address_select_', ''),
                pattern;
            target = '{{' + target + '}}';
            pattern = new RegExp(target, 'g');
            if (s) {
                $('#content').focus();
                r.insertText('{{' + val + '}}');
            } else {
                new_content = content.replace(pattern, '');
                r.setData(new_content);
            }
        },
        setSocialLinksLegacy = function (s, r, val) {
            var content = r.code.get(),
                new_content = '',
                value = val.split('|'),
                fontAwesomeIcon = value[0].replace(new RegExp('\'', 'g'), '"'),
                link = '&nbsp;<a class="' + value[1] + '" href="' + value[3] + '">' + fontAwesomeIcon + value[2] + '</a>&nbsp;',
                tags = [];
            if (s) {
                $('[name="header_text"]').focus();
                r.insert.html(link, false);
            } else {
                tags = $(content).find('[class="' + value[1] + '"]');
                $.each(tags, function (i, n) {
                    new_content = content.replace(n.outerHTML, '');
                })
                r.code.set(new_content);
            }
        },
        setSocialLinks = function (s, r, val) {
            if (window.C5_VERSION === 0) {
                setSocialLinksLegacy(e, s, r, c, val);
                return false;
            }
            var content = r.getData(),
                new_content = '',
                value = val.split('|'),
                fontAwesomeIcon = value[0].replace(new RegExp('\'', 'g'), '"'),
                link = '&nbsp;<a class="' + value[1] + '" href="' + value[3] + '">' + fontAwesomeIcon + value[2] + '</a>&nbsp;',
                tags = [];
            if (s) {
                $('[name="header_text"]').focus();
                r.insertHtml(link, false);
            } else {
                tags = $(content).find('[class="' + value[1] + '"]');
                $.each(tags, function (i, n) {
                    new_content = content.replace(n.outerHTML, '');
                });
                r.setData(new_content);
            }
        },
        testEmail = function (test_email) {
            var pattern = /^([a-z\d!#$%&'*+\-\/=?\^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?\^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
            return (pattern.test(test_email));
        },
        textInputs = [],
        redactor,
        redactorInstances = [],
        focusNext = function (index) {
            $('#' + textInputs[index + 1]).focus();
        };
    /**
     * hides/shows the cronjob settings
     * hides explanations in new template
     */
    $(document).ready(function () {
        if(window.C5_VERSION > 0 && location.href.indexOf('new_newsletter') > -1) {
            redactor = window.CKEDITOR.instances;
            for(var i in redactor) {
                // ToDo redactor[i].config.extraPlugins = 'ck4_googlefontfamily';
                redactorInstances.push(redactor[i]);
            }
        } else {
            redactorInstances = window.redactorInstances;
        }
        if (window.location.href.indexOf('send_mailing') > -1) {
            getRecipients('uName', 'asc');
        }

        $('#ccm-tab-content-groups').removeClass('wait');
        var prop = $('[name="isSorted"]').val(),
        by = $('[name="isSortedBy"]').val();
        if ($('#per_job').val() === '1') {
            $('.only_per_job').show();
        } else {
            $('.only_per_job').hide();
        }
        if (window.location.pathname.indexOf('send_newsletter') > -1 && $('[name="newsletter"]').val() !== 'xxx') {
            showAttachments($('[name="newsletter"]').val());
            getRecipients(prop, by);
        }
        $.each($('[type="text"], [type="number"]'), function (i, n) {
            if ($(n).attr('id') !== undefined) {
                textInputs.push($(n).attr('id'));
            }
        });
    });

    $(document).on('click', '[id*="info_user_attributes_insert"], [id*="info_social_links_insert"], [id*="info_unsubscribe_link_insert"]', function (e) {
        e.preventDefault();
        var id = $(this).attr('id');
        $('#' + id + '_text').toggle();
    });
    $(document).on('click', '#GoTo_Send_new_newsletter', function (e) {
        e.preventDefault();
        window.location.href = window.url_send_mailing;
    });
    $(document).on('submit', '#newsletter_form', function (e) {
        e.preventDefault();
        var emails = [];
        $.each($('[name^="uEmail"]'), function (i, n) {
            if ($(n).is(':checked')) {
                emails.push(n.value)
            }
        });
        console.log(JSON.stringify(emails));
        $('#all_Emails').val(JSON.stringify(emails));
        $('[name^="u"]').remove();
        $('#newsletter_form')[0].submit();
        return false;
    });
    $(document).on('keypress', 'input', function (e) {
        var index = textInputs.indexOf($(this).attr('id'));
        if (e.which === 13) {
            focusNext(index);
            return false;
        }
    });
    /**
     * Changes the preview dialog content
     */
    $(document).on('change', '[id^="nl_newsletter_preview"]', function () {
        var tid = $(this).val(),
            nid = window.location.pathname.substring(window.location.pathname.lastIndexOf('/') + 1),
            container = $('#tpl'),
            dats;
        if (parseInt(tid, 10) <= 0) {
            return false;
        }
        if (isNaN(parseInt(nid, 10)) || tid === 'xxx') {
            tid = $(this).attr('data-id');
            nid = $(this).val();
            container = $('#tpl_list_' + tid);
            container.html('');
            return false;
        }
        $.ajax({
            type: 'GET',
            url: window.get_template,
            data: {
                t_id: tid,
                n_id: nid
            },
            success: function (data) {
                if (data === 'false') {
                    container.html('<h4>' + window.please_save_first + '!</h4>');
                    return false;
                }
                dats = $.parseJSON(data);
                container.html(dats.tpl);

                if (isNaN(parseInt(nid, 10))) {
                    $(this).val('xxx');
                }
            }
        });
    });
    /**
     * closes whatever
     */
    $(document).on('click', '.close', function () {
        var box = $(this).parent('div');
        box.hide();
    });
    /**
     * Changes total of attachment size
     */
    $(document).on('change', '[name*="_border_"][name$="_style"]', function () {
        var attr = $(this).attr('name').split('_'),
            attrName = attr[0] + '_' + attr[1] + '_' + attr[2],
            attrElColor = $('[name="' + attrName + '_color"]'),
            attrElWidth = $('[name="' + attrName + '_width"]');
        if ($(this).val() === 'none') {
            attrElWidth.hide();
            attrElWidth.parent().hide();
            attrElColor.parent().hide();
        } else {
            attrElWidth.show();
            attrElWidth.parent().show();
            attrElColor.parent().show();
        }
    });
    $(document).on('click', '#go_test_mail', function (e) {
        e.preventDefault();
        var test_email = $('#test_mail').val(),
            tpl = $('#newsletter_template_id').val(),
            tpl_send = $('#template').val(),
            nl = ($('#newsletter_id').val()),
            path = '',
            nl_send = $('#newsletter').val();
        if (window.location.pathname.indexOf('templates') > -1) {
            nl = '1';
            path = 'templates';
        }
        if (window.location.pathname.indexOf('send_mailing') > -1) {
            nl = nl_send;
            tpl = tpl_send;
            path = 'mailing';
        }
        if (window.location.pathname.indexOf('new_newsletter') > -1) {
            path = 'newsletters';
        }
        if (window.location.pathname.indexOf('test_email_settings') > -1) {
            path = 'mail_settings';
        }
        if ((tpl === '' || tpl === undefined) || (nl === '' || nl === undefined)) {
            $('#dialog_save_first').dialog({
                title: window.empty_newsletter_title,
                width: 500,
                height: 150,
                modal: true,
                position: {
                    my: 'center center',
                    at: 'center center'
                }
            });
            return false;
        }
        if (!testEmail(test_email)) {
            $('#dialog_valid_email').dialog({
                title: window.empty_newsletter_title,
                width: 500,
                height: 150,
                modal: true,
                position: {
                    my: 'center center',
                    at: 'center center'
                }
            });
        }
        $.ajax({
            type: 'POST',
            url: window.send_test_mail,
            data: {
                email_address: test_email,
                tpl: tpl,
                nl: nl,
                path: path
            },
            success: function (data) {
                var dats = $.parseJSON(data);
                if (dats.hasOwnProperty('failed')) {
                    $('#dialog_tpl_first_text').text(dats.failed);
                    $('#dialog_email_success').dialog({
                        title: window.empty_newsletter_title,
                        width: 500,
                        height: 150,
                        modal: true,
                        position: {
                            my: 'center center',
                            at: 'center center'
                        }
                    });
                } else {
                    $('#dialog_tpl_first_text').text(dats.success);
                    $('#dialog_email_success').dialog({
                        title: window.mail_success_title,
                        width: 500,
                        height: 150,
                        modal: true,
                        position: {
                            my: 'center center',
                            at: 'center center'
                        }
                    });
                }
            }
        });
    });

    /**
     * change attachment size display
     */
    $(document).on('change keyup blur', '#files_num, #file_size', function () {
        var filesnum = parseInt($('#files_num').val(), 10),
            filesize = parseFloat($('#file_size').val()),
            sum = filesnum * filesize;
        $('#all_attachment').html(filesnum);
        $('#all_attachment_size').html(Math.round(sum * 100) / 100);
    });
    $(document).on('change', '[id^="nl_template"]', function () {
        if ($(this).attr('id') === 'nl_template') {
            $('#nl_template_preview').val($(this).val());
            $('[id^="nl_template_preview"]').trigger('change');
        } else {
            $('#nl_template').val($(this).val());
        }
    });

    /**
     * Changes the preview dialog content
     */
    $(document).on('change', '[id^="nl_template_preview"]', function () {
        var tid = $(this).val(),
            nid = window.location.pathname.substring(window.location.pathname.lastIndexOf('/') + 1),
            container = $('#tpl'),
            dats;
        if (parseInt(tid, 10) <= 0) {
            return false;
        }
        if (isNaN(parseInt(nid, 10)) || tid === 'xxx') {
            tid = $(this).attr('data-id');
            nid = $(this).val();
            container = $('#tpl_list_' + tid);
            container.html('');
            return false;
        }
        $.ajax({
            type: 'GET',
            url: window.get_template,
            data: {
                t_id: tid,
                n_id: nid
            },
            success: function (data) {
                if (data === 'false') {
                    container.html('<h4>' + window.please_save_first + '!</h4>');
                    return false;
                }
                dats = $.parseJSON(data);
                container.html(dats.tpl);

                if (isNaN(parseInt(nid, 10))) {
                    $(this).val('xxx');
                }
            }
        });
    });

    /**
     * delete confirmation
     */
    $(document).on('click', '#delete_newsletter, #delete_template, #delete_newsletter_history', function (e) {
        e.preventDefault();
        var _this = this,
            sent_not_sent = $('#sent_not_sent_' + $(this).attr('data-id')).children('i').length;
        if (sent_not_sent === 0) {
            $('#is_being_sent_warning').html('<h5>' + window.being_sent_warning + '</h5>');
        } else {
            $('#is_being_sent_warning').html('');
        }
        $('#dialog_delete').dialog({
            modal: true,
            title: window.delete_title,
            width: 500,
            position: {
                my: 'center center',
                at: 'center center'
            },
            buttons: [
                {
                    text: window.delete_yes,
                    open: function() {
                        $(this).addClass('ui-button-danger');
                    },
                    click: function () {
                        window.location = $(_this).attr('href');
                    }
                },
                {
                    text: window.delete_no,
                    open: function() {
                        $(this).addClass('ui-button');
                    },
                    click: function () {
                        $(this).dialog('close');
                    }
                }
            ]
        });
    });

    /**
     * Gets template & newsletter and
     * shows the preview in a dialog
     */
    $(document).on('click', '#preview_go_fixed', function (e) {
        var container = $('#tpl_fixed'),
            dats,
            nid = $('[name="newsletter"]').val(),
            tid = $('[name="template"]').val();
        e.preventDefault();
        $.ajax({
            type: 'GET',
            url: window.get_template,
            data: {
                t_id: tid,
                n_id: nid
            },
            success: function (data) {
                if (data === 'false') {
                    container.html('<h4>' + window.please_save_first + '!</h4>');
                    return false;
                }
                dats = $.parseJSON(data);
                container.html(dats.tpl);
                $('#mail_template_border').dialog({
                    modal: true,
                    title: window.subject + ': \'' + dats.subject + '\'',
                    width: 800,
                    position: {
                        my: 'top',
                        at: 'top+50'
                    },
                    close: function () {
                        $(this).find('div').eq(0).html('');
                    }
                });
            }
        });

    });

    /**
     * Changes the * * * * part in the cronjob command display
     */
    $(document).on('change', '#time_unit, #mails_per', function () {
        var t_unit = $('#time_unit').val(),
            mails_per = $('#mails_per');
        $.ajax({
            type: 'GET',
            url: window.change_time_unit,
            data: {
                unit: t_unit
            },
            success: function (data) {
                var dats = $.parseJSON(data);
                $('#cron_job_path').html(dats.path);
                $('#alert_cron').show();
                mails_per.attr('max', dats.max_number);
                if (window.parseInt(mails_per.val(), 10) > window.parseInt(dats.max_number, 10)) {
                    mails_per.val(dats.max_number);
                }
            }
        });
    });

    /**
     * closes whatever
     */
    $(document).on('click', '.close', function () {
        var box = $(this).parent('div');
        box.hide();
    });

    /**
     * Sets the template id when changing the newsletter in Add/edit Newsletter
     */
    $(document).on('change', '[name="nl_template"]', function () {
        if ($(this).val() === 'xxx') {
            $('#newsletter_template_id').val('');
        }else {
            $('#newsletter_template_id').val($(this).val());
        }
    });

    /**
     * Sets the template id when changing the newsletter in Send Newsletter
     */
    $(document).on('change', '[name="newsletter"]', function () {
        var nlid = $(this).val();
        if (nlid === 'xxx') {
            $('#template_handle').val('');
            $('#attachments_for_newsletter').html('');
            return false;
        }
        $.ajax({
            type: 'GET',
            url: window.get_tpl_for_nl,
            data: {
                nl_id: nlid
            },
            success: function (data) {
                var dat = $.parseJSON(data);
                $('#mail_sent_alert').hide();
                $('#mail_sent_alert_failed').hide();
                $('#template').val(dat.id);
                $('#template_handle').val(dat.handle);
                $('#preview_go_fixed').show();
                showAttachments(nlid);
            }
        });


    });

    /**
     * Shows/hides the preview button in Send Newsletter
     */
    $(document).on('change', '[name="newsletter"], [name="template"]', function () {
        var this_one = $(this),
            the_other = this_one.parent().siblings('.choose_nl_tl').children('select');
        if (this_one.val() !== 'xxx' && the_other.val() !== 'xxx') {
            $('#preview_go_fixed').show();
        } else {
            $('#preview_go_fixed').hide();
        }

    });

    /**
     * Changes the user group
     */
    $(document).on('switchChange.bootstrapSwitch', 'input[name^="chooseGroup"]', function (event, state) {
        var prop = 'uName',
            by = 'asc';
        if ($(this).attr('data-first') === '1') {
            if (state) {
                $('input[name^="chooseGroup"][data-first!="1"]').bootstrapSwitch('state', false);
            }
        } else {
            if (state) {
                $('input[name^="chooseGroup"][data-first="1"]').bootstrapSwitch('state', false);
            }
        }
        if ($('[name="isSorted"]').val() !== '') {
            prop = $('[name="isSorted"]').val();
            by = $('[name="isSortedBy"]').val();
        }
        $('[data-search-checkbox="select-all"]').prop('checked', false);
        getRecipients(prop, by);
    });

    /**
     * Adds User attribute placeholders to the head content of a newsletter
     */
    $(document).on('switchChange.bootstrapSwitch', 'input[name^="head_address_select"]', function (event, state) {
        setUserAttributes(event, state, redactorInstances[0], 'head', this.value);
    });

    /**
     * Adds User attribute placeholders to the body content of a newsletter
     */
    $(document).on('switchChange.bootstrapSwitch', 'input[name^="content_address_select"]', function (event, state) {
        setUserAttributes(event, state, redactorInstances[1], 'content', this.value);
    });

    /**
     * Adds User attribute placeholders to the footer content of a newsletter
     */
    $(document).on('switchChange.bootstrapSwitch', 'input[name^="foot_address_select"]', function (event, state) {
        setUserAttributes(event, state, redactorInstances[2], 'foot', this.value);
    });

    /**
     * Adds Social links to the head content of a newsletter
     */
    $(document).on('switchChange.bootstrapSwitch', 'input[name^="head_social_links"]', function (event, state) {
        setSocialLinks(state, redactorInstances[0], this.value);
    });

    /**
     * Adds Social links to the body content of a newsletter
     */
    $(document).on('switchChange.bootstrapSwitch', 'input[name^="body_social_links"]', function (event, state) {
        setSocialLinks(state, redactorInstances[1], this.value);
    });

    /**
     * Adds Social links to the foot content of a newsletter
     */
    $(document).on('switchChange.bootstrapSwitch', 'input[name^="foot_social_links"]', function (event, state) {
        setSocialLinks(state, redactorInstances[2], this.value);
    });

    /**
     * Check all recipient box
     */
    $(document).on('click', '[data-search-checkbox="select-all"]', function () {
        var checkboxes = $('[name^="uEmail"]:not(:disabled)');
        checkboxes.prop('checked', !checkboxes.prop('checked'));
        console.log($('[name^="uEmail"]:checked').length);

        setNumRecords($('[name^="uEmail"]:checked').length);
    });

    /**
     * Sorts the recipients table
     */
    $(document).on('click', '.sort_it', function (e) {
        e.preventDefault();
        $(this).attr('data-is-sorted', 1);
        $('#isSorted').val($(this).attr('data-prop'));
        getRecipients($(this).attr('data-prop'), $(this).attr('data-sort'));
    });

    /**
     * Changes cronjob setting per job /directly
     */
    $(document).on('change', '#per_job', function () {
        if ($(this).val() === '1') {
            $('.only_per_job').show();
            $('#alert_install_job').show();
        } else {
            $('.only_per_job').hide();
            $('#alert_install_job').hide();
        }
    });

    /**
     * Shows/hides the owners email
     */
    $(document).on('switchChange.bootstrapSwitch', 'input[name="report_newsletter"]', function (event, state) {
        if (state) {
            $('#owner_email_show').show();
            $('#owner_email').focus();
            $('html, body').animate({
                scrollTop: $("#owner_email").offset().top
            }, 500);
        } else {
            $('#owner_email_show').hide();
        }
    });

    /**
     * Closes success messages (session)
     */
    $(document).on('click', '#ok', function () {
        $('.alert-success').remove();
    });

    /**
     * Displays the mail settings error
     */
    $('#mail_error_message').on('click', function () {
        $('#show_mail_error').toggle().css('display', 'inline-block');
    });

    /**
     * recipient checkbox & calls number of recipients func
     */
    $(document).on('click', '[id^="uEmail_"]', function () {
        var totalL = $('input[name="uEmail[]"]').length,
            checkedL = $('input[name="uEmail[]"]:checked').length;
        if (!$(this).is(':checked')) {
            $('[data-search-checkbox="select-all"]').prop('checked', false);
        }
        if (totalL == checkedL) {
            $('[data-search-checkbox="select-all"]').prop('checked', true);
        }
        setNumRecords($('input[name="uEmail[]"]:checked').length);
    });

    /**
     * Checks empty newsletter before saving
     */
    $(document).on('click', '#Send_new_newsletter', function (e) {
        e.preventDefault();
        var content = $('[name="content"]').val(),
            header = $('[name="header_text"]').val(),
            footer = $('[name="footer"]').val(),
            nl_handle = $('#nl_handle').val(),
            nl_template = $('#nl_template').val(),
            nl_subject = $('#nl_subject').val();
        if ((content.length === 0 && header.length === 0 && footer.length === 0) && (nl_handle.length > 0 && nl_subject.length > 0 && nl_template != 'xxx')) {
            $('#empty_newsletter').dialog({
                title: window.empty_newsletter_title,
                width: 500,
                height: 300,
                modal: true,
                buttons: [
                    {
                        text: window.empty_newsletter_yes,
                        open: function() {
                            $(this).addClass('ui-button-danger');
                        },
                        click: function () {
                            $('#new_newsletter').submit();
                        }
                    },
                    {
                        text: window.empty_newsletter_no,
                        click: function () {
                            $(this).dialog('close');
                        }
                    }
                ],
                position: {
                    my: 'center center',
                    at: 'center center'
                }
            });
        } else {
            $('#new_newsletter').submit();
        }
    });

    $(document).ajaxComplete(function () {
        var selector = 'more_f_n',
            selectedF = parseInt($('.ccm-file-selector-file-selected').length, 10),
            totalF = parseInt($('.ccm-file-selector').length, 10),
            max = parseInt(window.max_files, 10);
        if (max === totalF && $('#' + selector).length === 0) {
            $('#choosenFiles').append('<li id="' + selector + '"></li>');
        }
        $('#' + selector).text((max - selectedF) + ' ' + window.str_attach_more);
    });
}(jQuery));
