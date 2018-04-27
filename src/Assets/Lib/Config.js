// Get lodash
import _ from 'lodash-es';

/**
 * Config
 *
 */
export let Config = (function () {

    let config = window.JsConfig;

    class Config {

        /**
         *
         * @param value
         * @param path
         */
        set(value, path) {
            _.set(config, this._getPath(path), value);
        }

        /**
         *
         * @param path
         * @returns {*}
         */
        get(path) {
            if (path === null) {
                return config;
            }

            return _.get(config, this._getPath(path));
        }

        /**
         *
         * @param defaults
         * @param deep
         * @param path
         * @returns {*}
         */
        defaults(defaults, deep, path) {
            let conf = this.get(path);

            if (!deep) {
                return _.defaults(conf, defaults);
            }

            return _.defaultsDeep(conf, defaults);
        }

        /**
         * Normalize the path to controller.action if non provided
         * @param path
         * @returns {*}
         * @private
         */
        _getPath(path) {
            return path ? path : `${this.get('App.controller')}.${this.get('App.action')}`;
        }
    }

    return new Config;
})();
