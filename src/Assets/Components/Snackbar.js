import $ from 'jquery';
import _ from 'lodash-es';

/**
 *
 */

export default class Snackbar {

    /**
     *
     * @param settings_
     */
    static render(settings_) {
        let settings = _.defaults(settings_, {
            timeout: 4000,
            html: '',
        });

        // Generate the div
        let snackbar = this.generateSnackbarComponent(settings);

        // Append it tot he body
        snackbar.appendTo(document.body);

        // Added the show class for the animation
        // Delegation because
        setTimeout(() => {
            snackbar.addClass('show');
        }, 1);

        // Do we have a timeout?
        if (settings.timeout !== 'Infinity' && !isNaN(settings.timeout)) {

            // Set the timeout to remove the snackbar
            setTimeout(() => {
                snackbar.removeClass('show');
                setTimeout(() => snackbar.remove(), 200);
            }, settings.timeout);
        }
    }

    /**
     * @param settings
     * @returns {*}
     */
    static generateSnackbarComponent(settings) {
        let snackbar = $('<div />').addClass('snackbar');
        let snackbarBody = $('<div />').addClass('snackbar-body').html(settings.html);

        if (settings.classes) {
            snackbar.addClass(settings.classes);
        }

        if (settings.button) {
            snackbar.append(settings.button);
        }

        return snackbar.prepend(snackbarBody);
    }
}
