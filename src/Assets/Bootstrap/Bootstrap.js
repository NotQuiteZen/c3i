// Get DefaultModule
import { DefaultModule } from 'stein';
import { Config } from 'Lib/Config';
import {each} from 'lodash-es';

import '../scss/app.scss';

/**
 *
 */
export class Bootstrap extends DefaultModule {
    constructor(modules) {
        super();

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
        each(toasts, (toast) => {
            console.log('Toast:', toast);
        });
    }

}
