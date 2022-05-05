import {getBlankModal} from "./tools";

const $ = require('jquery');

let $viewBtns;
const btnInitializedClass = 'initialized-class';

export function init() {
   $viewBtns = $('a[data-action="view-company-link"]:not(.' + btnInitializedClass + ')');

   if ($viewBtns.length === 0) return false;

   $viewBtns.on('click', function (event) {
      event.preventDefault();
      let url = $(this).attr('data-url');
      let $blankModal = getBlankModal();
      console.log($blankModal);
      $blankModal.modal('show');

   });
}

export function reInit() {
   init();
}
