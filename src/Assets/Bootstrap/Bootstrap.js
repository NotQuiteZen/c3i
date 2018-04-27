// Get DefaultModule
import { DefaultModule } from 'stein';
import { Config } from 'Lib/Config';
import _ from 'lodash-es';

import '../scss/app.scss';

import M from 'materialize-css';

/**
 *
 */
export class Bootstrap extends DefaultModule {
    constructor(modules) {
        super();

        // AutoInit
        M.AutoInit(document.body);

        // Expose to module
        this.M = M;

        // Subscribe DOMReady functions to the DOMReady event
        if (typeof this.DOMReady === 'function') {
            this.subscribe('DOMReady', this.DOMReady);
        }

        // on DOMContentLoaded, publish DOMReady
        document.addEventListener('DOMContentLoaded', () => this.publish('DOMReady'));

        let toasts = Config.get('App.toasts');
        if (toasts) {
            this.renderToasts(toasts);
        }
    }

    renderToasts(toasts) {
        _.each(toasts, (toast) => {
            this.M.toast(toast);
        });
    }

}
