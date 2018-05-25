/**
 *
 */

export default class Snackbar {

    /**
     *
     * @param settings_
     */
    static render(settings_) {
        let settings = Object.assign({}, {
            timeout: 4000,
            html: '',
        }, settings_);

        // Generate the div
        let snackbar = this.generateSnackbarComponent(settings);

        // Append it to the body
        document.body.appendChild(snackbar);

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

        // Create the main div
        let snackbar = document.createElement('div');
        snackbar.classList.add('snackbar');

        // Snackbar classes
        if (settings.classes) {

            // If we provided a string, make it iterable
            if (typeof settings.classes === 'string') {
                settings.classes = [settings.classes];
            }

            // Iterate the classes and add them
            settings.classes.forEach(function (classname) {
                snackbar.classList.add(classname);
            });
        }

        let snackbarBody = document.createElement('div');
        snackbarBody.classList.add('snackbar-body');
        snackbarBody.innerHTML = settings.html;

        snackbar.appendChild(snackbarBody);

        // Append the button
        if (settings.button) {
            snackbar.appendChild(settings.button);
        }

        return snackbar;
    }
}
