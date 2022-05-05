import {getLoader} from "./tools";

const $ = require('jquery');
const routes = require('../../../public/js/fos_js_routes.json');
import Routing from '../../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';
Routing.setRoutingData(routes);

let $form, $resultsContainer;

export default function initSearch() {
    $form = $('#home-search-form');

    if ($form.length === 0) return false;

    $resultsContainer = $('#search-results');

    $form.submit(function (event) {
        event.preventDefault();

        $resultsContainer.html(getLoader());

        let formDatas = $form.serializeArray().reduce(function(obj, item) {
            obj[item.name] = item.value;
            return obj;
        }, {});

        let term = formDatas['search_company[text]'];

        $.ajax({
            url: Routing.generate('api_search_company', {term: term})
        })
            .done(function (data) {
                $resultsContainer.html(data.renderHtml);
            })
            .fail(function () {
                console.error('Api Error');
            });
    });
}
