/**
 * Created by https://toesslab.ch/
 * User: Daenu
 * Date: 11/18/15
 * Time: 1:26 AM
 * Project: toesslab - Newsletter
 * Description: Send Newsletters to registered users
 * File: /packages/toess_lab_news_letter/js/tourist/toesslab-tourist-new-newsletter-template.js
 */

$(function () {
    "use strict";
    var tour,
        steps = [{
            content: '<p><span class="h5">' + window.tourist_template_name_title + '</span>:<br/>' + window.tourist_template_name_text + '</p>',
            highlightTarget: true,
            nextButton: true,
            target: $('#tl_handle'),
            my: 'top center',
            at: 'bottom center'
        },
            {
                content: '<p><span class="h5">' + window.tourist_logo_title + '</span>:<br/>' + window.tourist_logo_text + '</p>',
                highlightTarget: true,
                nextButton: true,
                target: $('[data-file-selector="mail_logo"]'),
                my: 'top center',
                at: 'bottom center'
            },
            {
                content: '<p><span class="h5">' + window.tourist_header_title + '</span>:<br/>' + window.tourist_header_text + '</p>',
                highlightTarget: true,
                nextButton: true,
                target: $('#header'),
                my: 'bottom left',
                at: 'bottom center'
            },
            {
                content: '<p><span class="h5">' + window.tourist_content_title + '</span>:<br/>' + window.tourist_content_text + '</p>',
                highlightTarget: true,
                nextButton: true,
                target: $('#body'),
                my: 'bottom center',
                at: 'bottom center'
            },
            {
                content: '<p><span class="h5">' + window.tourist_footer_title + '</span>:<br/>' + window.tourist_footer_text + '</p>',
                highlightTarget: true,
                nextButton: true,
                target: $('#footer'),
                my: 'bottom center',
                at: 'bottom center'
            },
            {
                content: '<p><span class="h5">' + window.tourist_titles_title + '</span>:<br/>' + window.tourist_titles_text + '</p>',
                highlightTarget: true,
                nextButton: true,
                target: $('#titles'),
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
