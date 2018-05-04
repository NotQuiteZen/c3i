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

        let snackbar = this.generateSnackbarComponent(settings);
        snackbar.appendTo(document.body);
        snackbar.addClass('show');

        if (settings.timeout !== 'Infinity') {

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
