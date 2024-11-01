(function($) { 
  $(document).ready(function() {
    var wooAdSearch,
        settingAarray;
    settingAarray = $.parseJSON($('input[name="setting_data_array"]').val());
    if ($.isEmptyObject(settingAarray)) {
      return false;
    } else {
      if ('' === settingAarray.Advance_Search_Live_Ajax) {
        return false;
      } else {
        wooAdSearch = {
          init: function() {
            $(document).on('focus keyup', '.woo_advance_default_preview_set_search_text', this.wooAdvancedSearchLiveAjaxData);
            $(document).on('change', '.advance_search_category_preview_html', this.wooAdvancedSearchLiveAjaxDataCategory);
            $(document).on('change', '.advance_search_category_tag_html', this.wooAdvancedSearchLiveAjaxDataTag);

          },
          wooAdvancedSearchLiveAjaxData: function(event) {
            event.stopPropagation();
            wooAdSearchloopvariable();
          },
          wooAdvancedSearchLiveAjaxDataCategory: function(event) {
            event.stopPropagation();
            wooAdSearchloopvariable();
          },
          wooAdvancedSearchLiveAjaxDataTag: function(event) {
            event.stopPropagation();
            wooAdSearchloopvariable();
          },
        };
        wooAdSearch.init();
        wooAdSearchOutsideContainerSuggesionsOff();
      }

    }

  });

  /**
   * function to return close suggestion while click outside
   */
  function wooAdSearchOutsideContainerSuggesionsOff() {
    $(document).mouseup(function(e) {
      if (0 === $(e.target).closest('.Advance_search_for_woo_display_main').length) {
        $('.autocomplete_suggesions').hide();
        $('.autocomplete_suggesions').empty();

      }

    });
  }

  /**
   * function to return html of response
   * @param productHtmlVariable
   */
  function wooAdSearchHtml(productHtmlVariable) {
    $('.autocomplete_suggesions').empty();
    $.each(productHtmlVariable, function() {
      var productName = this.product_name;
      var productImage = this.product_image;
      var productLink = this.product_link;
      var productImageSetting = this.product_image_setting;
      var productPrice = this.product_price;
      var productDescription = this.product_description;

      var _html;
      var firstElement = $('<li/>', {class: 'wasLiveAjax'});
      var thirdInnerElement,
          secondInnerHtml,
          fourthInnerElement,
          fifthElement,
          childElement,
          sixthElement;
      secondInnerHtml = $('<a/>', {href: productLink, text: productName, class: 'wasLiveAjaxname'});
      childElement = $('<div/>', {class: 'wasLiveAjaxChildElement'});
      $(secondInnerHtml).appendTo(childElement);
      if ('without_image' !== productImageSetting) {
        thirdInnerElement = $('<figure/>', {class: 'wasLiveAjaxfigure'});
        $(thirdInnerElement).appendTo(firstElement);
        fourthInnerElement = $('<img/>', {src: productImage, class: 'wasLiveAjaximage'});
        $(fourthInnerElement).appendTo(thirdInnerElement);
      }
      fifthElement = $('<a/>', {html: productPrice, class: 'wasLiveAjaxprice'});
      $(fifthElement).appendTo(childElement);
      sixthElement = $('<p/>', {text: productDescription, class: 'wasLiveAjaxDesc'});
      $(sixthElement).appendTo(childElement);
      $(childElement).appendTo(firstElement);
      $(firstElement).appendTo('.autocomplete_suggesions');

    });
  }

  /**
   * Function to return ajax call
   * @param _action
   * @param selectorVal
   * @param categoryValue
   * @param categoryTag
   * @returns {boolean}
   */
  function wooAdSearchFilterAjax(_action, selectorVal, categoryValue, categoryTag) {
    var _data;
    if ('' === selectorVal) {
      return false;
    } else {
      if (3 < selectorVal.length) {
        $('.Default_search_preview_tab').addClass('loading');
        _data = {
          'action': _action,
          'selectorVal': selectorVal,
          'categoryValue': categoryValue,
          'categoryTag': categoryTag,
        };
        $.ajax({
          dataType: 'JSON',
          url: adminajaxjs.adminajaxjsurl,
          type: 'POST',
          data: _data,
          success: function(response) {
            if ('woo-advanced-search_live_ajax_data' === response.data.message) {
              $('.Default_search_preview_tab').removeClass('loading');
              wooAdSearchHtml(response.data.product_html_variable);

            }
          },
        });
      } else {
        $('.autocomplete_suggesions').empty();
      }

    }
  }

  /**
   * function to return loop variable of ajax calls
   */
  function wooAdSearchloopvariable() {
    var categoryValue,
        selectorVal,
        categoryTag;
    $('.autocomplete_suggesions').show();
    categoryValue = $('.advance_search_category_preview_html').val();
    selectorVal = $('.woo_advance_default_preview_set_search_text').val();
    categoryTag = $('.advance_search_category_tag_html').val();

    wooAdSearchFilterAjax('woo_advanced_search_live_ajax_data', selectorVal, categoryValue, categoryTag);
  }
})(jQuery);
