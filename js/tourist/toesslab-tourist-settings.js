/**
 * Created by https://toesslab.ch/
 * User: Daenu
 * Date: 11/18/15
 * Time: 1:26 AM
 * Project: toesslab - Newsletter
 * Description: Send Newsletters to registered users
 * File: /packages/toess_lab_news_letter/js/tourist/toesslab-tourist-settings.js
 */
$(function () {
    "use strict";
    var tour,
        steps = ($('#per_job').val() === '1') ? window.steps_job : window.steps_direct;

    tour = new Tourist.Tour({
        steps: ($('#per_job').val() === '1') ? window.steps_job : window.steps_direct,
        tipClass: 'Bootstrap',
        tipOptions: {
            showEffect: 'slidein'
        }
    });
    tour.on('start', function () {
        steps = ($('#per_job').val() === '1') ? window.steps_job : window.steps_direct;
        window.ConcreteHelpLauncher.close();
    });
    window.ConcreteHelpGuideManager.register('add-widget', tour);
});
