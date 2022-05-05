import initSearch from './components/search';
import * as companyView from './components/company-view';
const $ = require('jquery');

document.addEventListener('DOMContentLoaded', () => {
    initSearch();
    companyView.init();

    $(document).ajaxComplete(function (event, xhr, settings) {
        companyView.reInit();
    });
});
