import { Bootstrap } from 'Bootstrap/Bootstrap';
import { Config } from 'Lib/Config';

let conf = Config.defaults({console_message: 'This is overridden by Setups/index.ctp call of JsConfig->set()'});

class SetupsIndex extends Bootstrap {
    DOMReady() {
        console.log(conf.console_message);
    }
}

new SetupsIndex();
