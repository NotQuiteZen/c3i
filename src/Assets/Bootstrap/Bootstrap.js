// Get DefaultModule
import { DefaultModule } from 'stein';
import { Config } from '../Lib/Config';
import DaemoniteMaterialInitialize from 'daemonite-material-initializer';

import { each } from 'lodash-es';
import '../scss/app.scss';

import { Snackbar } from 'daemonite-material-additions';

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

        let snackbars = Config.get('App.snackbars');
        if (snackbars) {
            this.renderSnackbars(snackbars);
        }

        let initModules = Object.assign({}, {
            Datepicker: {}
        }, modules);

        this.DaemoniteMaterialInitialize = new DaemoniteMaterialInitialize(initModules);
    }

    renderSnackbars(snackbars) {
        each(snackbars, (snackbar) => {
            Snackbar.render(snackbar);
        });
    }

}
