/**
 * Created by https://toesslab.ch/
 * User: Daenu
 * Date: 11/18/15
 * Time: 1:26 AM
 * Project: toesslab - Newsletter
 * Description: Send Newsletters to registered users
 * File: /packages/toess_lab_news_letter/js/tourist/toesslab-tourist-send-newsletter.js
 */

$(function () {
    "use strict";
    var tour,
        steps = [{
            content: '<p><span class="h5">' + window.tourist_newsletter_name_title + '</span>:<br/>' + window.tourist_newsletter_name_text + '</p>',
            highlightTarget: true,
            nextButton: true,
            target: $('#ns_handle'),
            my: 'top center',
            at: 'bottom center'
        },
            {
                content: '<p><span class="h5">' + window.tourist_group_title + '</span>:<br/>' + window.tourist_group_text + '</p>',
                highlightTarget: true,
                nextButton: true,
                target: $('#chooseGroup'),
                my: 'top center',
                at: 'bottom center'
            },
            {
                content: '<p><span class="h5">' + window.tourist_choose_newsletter_title + '</span>:<br/>' + window.tourist_choose_newsletter_text + '</p>',
                highlightTarget: true,
                nextButton: true,
                target: $('#newsletter'),
                my: 'bottom left',
                at: 'bottom center'
            },
            {
                content: '<p><span class="h5">' + window.tourist_choose_template_title + '</span>:<br/>' + window.tourist_choose_template_text + '</p>',
                highlightTarget: true,
                nextButton: true,
                target: $('#template'),
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
