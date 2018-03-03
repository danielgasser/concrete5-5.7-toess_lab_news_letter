/**
 * Created by https://toesslab.ch/
 * User: Daenu
 * Date: 11/18/15
 * Time: 1:26 AM
 * Project: toesslab - Newsletter
 * Description: Send Newsletters to registered users
 * File: /packages/toess_lab_news_letter/js/tourist/toesslab-tourist-new-newsletter.js
 */

$(function () {
    "use strict";
    var tour,
        steps = [{
            content: '<p><span class="h5">' + window.tourist_newsletter_name_title + '</span>:<br/>' + window.tourist_newsletter_name_text + '</p>',
            highlightTarget: true,
            nextButton: true,
            target: $('#nl_handle'),
            my: 'top center',
            at: 'bottom center',
            bind: ['onChange'],
            setup: function (tour, options) {
                $('#nl_handle').on('change', this.onChange);
            },
            teardown: function (tour, options) {
                $('#nl_handle').off('change', this.onChange);

            },
            onChange: function (tour) {
                tour.next();
                return false;
            }
        },
            {
                content: '<p><span class="h5">' + window.tourist_subject_title + '</span>:<br/>' + window.tourist_subject_text + '</p>',
                highlightTarget: true,
                nextButton: true,
                target: $('#nl_subject'),
                my: 'top center',
                at: 'bottom center',
                bind: ['onChange'],
                setup: function (tour, options) {
                    $('#nl_subject').on('change', this.onChange);
                },
                teardown: function (tour, options) {
                    $('#nl_subject').off('change', this.onChange);

                },
                onChange: function (tour) {
                    tour.next();
                    return false;
                }
            },
            {
                content: '<p><span class="h5">' + window.tourist_template_name_title + '</span>:<br/>' + window.tourist_template_name_text + '</p>',
                highlightTarget: true,
                nextButton: true,
                target: $('#nl_template'),
                my: 'bottom left',
                at: 'bottom center',
                bind: ['onChange'],
                setup: function (tour, options) {
                    $('#nl_template').on('change', this.onChange);
                },
                teardown: function (tour, options) {
                    $('#nl_template').off('change', this.onChange);

                },
                onChange: function (tour) {
                    $('[data-tab="header"]').trigger('click');
                    tour.next();
                    return false;
                }
            },
            {
                content: '<p><span class="h5">' + window.tourist_header_title + '</span>:<br/>' + window.tourist_header_text + '</p>',
                highlightTarget: true,
                nextButton: true,
                target: $('[for="header_text"]'),
                my: 'bottom center',
                at: 'bottom center'
            },
            {
                content: '<p><span class="h5">' + window.tourist_content_title + '</span>:<br/>' + window.tourist_content_text + '</p>',
                highlightTarget: true,
                nextButton: true,
                target: $('[for="content"]'),
                my: 'bottom center',
                at: 'bottom center'
            },
            {
                content: '<p><span class="h5">' + window.tourist_footer_title + '</span>:<br/>' + window.tourist_footer_text + '</p>',
                highlightTarget: true,
                nextButton: true,
                target: $('[for="footer"]'),
                my: 'bottom center',
                at: 'bottom center'
            },
            {
                content: '<p><span class="h5">' + window.tourist_attachment_title + '</span>:<br/>' + window.tourist_attachment_text + '</p>',
                highlightTarget: true,
                nextButton: true,
                target: $('[name="attachment_0"]'),
                my: 'bottom center',
                at: 'bottom center'
            },
            {
                content: '<p><span class="h5">' + window.tourist_message_title + '</span>:<br/>' + window.tourist_message_text + '</p>',
                highlightTarget: true,
                nextButton: true,
                target: $('#test_mail'),
                my: 'bottom left',
                at: 'bottom center'
            },
            {
                content: '<p><span class="h5">' + window.tourist_save_title + '</span>:<br/>' + window.tourist_save_text + '</p>',
                highlightTarget: true,
                nextButton: true,
                target: $('#Send'),
                my: 'bottom center',
                at: 'bottom center'
            }];

    tour = new window.Tourist.Tour({
        steps: steps,
        tipClass: 'Bootstrap',
        tipOptions: {
            showEffect: 'slidein'
        }
    });
    tour.on('start', function () {
        window.ConcreteHelpLauncher.close();
    });
    window.ConcreteHelpGuideManager.register('add-widget', tour);
});
