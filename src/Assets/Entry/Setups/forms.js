import { Bootstrap } from '../../Bootstrap/Bootstrap';
import { Config } from '../../Lib/Config';

let pageConfig = Config.defaults({console_message: 'This is overridden by Setups/forms.ctp call of JsConfig->set()'});

class SetupsForms extends Bootstrap {
    DOMReady() {
    }
}

new SetupsForms();
