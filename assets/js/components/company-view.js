import {getLoader} from "./tools";

const $ = require('jquery');

let $viewBtns;
const btnInitializedClass = 'initialized-class';
const activeClass = 'active';
const allDatasClass = 'all-datas';

export function init() {
   $viewBtns = $('a[data-action="view-company-link"]:not(.' + btnInitializedClass + ')');

   if ($viewBtns.length === 0) return false;

   $viewBtns.on('click', function (event) {
      event.preventDefault();
      let $thisBtn = $(this);
      let $container = $thisBtn.parents('.alc-api-etablisement-list--item');
      let $allDatasSection;

      if ($thisBtn.hasClass(activeClass)) {
         $allDatasSection = $container.find('.' + allDatasClass);
         hideResults($allDatasSection);
         $thisBtn.removeClass(activeClass);
         return true;
      }

      hideAllResults($viewBtns);

      let url = $(this).attr('data-url');
      $allDatasSection = $('<div></div>').addClass(allDatasClass).html(getLoader());

      $container.append($allDatasSection);
      $allDatasSection.fadeIn();

      $.ajax({url})
          .done(function (data) {
             $allDatasSection.html(data.renderHtml);
          })
          .fail(function () {
             console.error('Api Error');
          });

      $(this).addClass(activeClass);
   });

   $viewBtns.addClass(btnInitializedClass);
}

export function reInit() {
   init();
}

function hideResults($element)
{
   $element.fadeOut();
   $element.remove();
}

function hideAllResults()
{
   $('a[data-action="view-company-link"]').removeClass(activeClass);
   hideResults($('.' + allDatasClass));
}
