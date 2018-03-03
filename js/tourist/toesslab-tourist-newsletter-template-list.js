/**
 * Created by https://toesslab.ch/
 * User: Daenu
 * Date: 11/18/15
 * Time: 1:26 AM
 * Project: toesslab - Newsletter
 * Description: Send Newsletters to registered users
 * File: /packages/toess_lab_news_letter/js/tourist/toesslab-tourist-newsletter-template-list.js
 */

$(function () {
    "use strict";
    var tour,
        steps = [{
            content: '<p><span class="h5">' + window.tourist_edit_template_title + '</span>:<br/>' + window.tourist_edit_template_text + '</p>',
            highlightTarget: true,
            nextButton: true,
            target: $('.edit').first(),
            my: 'top center',
            at: 'bottom center'
        },
            {
                content: '<p><span class="h5">' + window.tourist_new_template_title + '</span>:<br/>' + window.tourist_new_template_text + '</p>',
                highlightTarget: true,
                nextButton: true,
                target: $('#add_new'),
                my: 'top center',
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
