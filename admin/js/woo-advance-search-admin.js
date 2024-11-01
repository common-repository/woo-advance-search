(function($) {
  'use strict';
  $(window).load(function() {
    var clipboard;
    $('body').on('click', '.advance_search_for_woocommerce_save_btn', function() {
      var ProductCategory = $('input[id="advance_search_product_category"][type="checkbox"]:checked').val();
      var ProductTag = $('input[id="advance_search_product_tag"][type="checkbox"]:checked').val();
      var AdvanceSearchFilter = $('input[name="advance_search_filter"][type="radio"]:checked').val();
      var AdvanceSearchAjaxShowcase = $('input[name="advance_search_ajax_showcase"][type="radio"]:checked').val();
      var AdvanceSearchAjaxProDesLimit = $('#advanced_search_product_description_limit').val();
      var orderBy = $('.advance_search_filter_order_by_html option:selected').val();
      var customCss = $('#woo-advance-search-custom-id').val();
      var advanceSearchLiveAjax = $('input[id="advance_search_live_ajax"][type="checkbox"]:checked').val();
      $('.woo_advance_save_record_messgae').empty();
      $.ajax({
        type: 'POST',
        url: adminajaxjs.adminajaxjsurl,
        data: ({
          action: 'Save_advance_search_settings_free',
          ProductCategory: ProductCategory,
          ProductTag: ProductTag,
          AdvanceSearchFilter: AdvanceSearchFilter,
          orderBy: orderBy,
          customCss: customCss,
          advanceSearchLiveAjax: advanceSearchLiveAjax,
          AdvanceSearchAjaxShowcase: AdvanceSearchAjaxShowcase,
          AdvanceSearchAjaxProDesLimit:AdvanceSearchAjaxProDesLimit
        }),
        success: function(data) {
          $('.woo_advance_save_record_messgae').css('display', 'block').delay(2000).fadeOut('slow');
          $(data).appendTo('.woo_advance_save_record_messgae');

        },
      });
    });

    $('body').on('click', '#advance_search_open_preview', function() {
      var ProductCategory,
        ProductTag,
        productCategoryCss,
        ProductTagCss;
      $('#advance_search_open_preview').addClass('nav-tab-active');
      $('#advance_search_open_setting').removeClass('nav-tab-active');
      $('#advance_search_open_shortcode').removeClass('nav-tab-active');
      $('#advance_search_open_custom_css').removeClass('nav-tab-active');
      $('#woo-advance-search-overview-tab').removeClass('nav-tab-active');

      $('.woo-advance-search-preview-tab').css('display', 'block');
      $('.woo-advance-search-setting-tab').css('display', 'none');
      $('.woo-advance-search-shortcode-tab').css('display', 'none');
      $('.woo-advance-search-overview-tab').css('display', 'none');
      $('.advance_search_for_woocommerce_save_btn').css('display', 'block');

      ProductCategory = $('input[id="advance_search_product_category"][type="checkbox"]:checked').val();
      ProductTag = $('input[id="advance_search_product_tag"][type="checkbox"]:checked').val();
      if ('Active' == ProductCategory) {
        productCategoryCss = 'inline-block';
      } else {
        productCategoryCss = 'none';
      }
      if ('Active' == ProductTag) {

        ProductTagCss = 'inline-block';
      } else {
        ProductTagCss = 'none';
      }
      $('select#advance_search_category_preview_html').css('display', productCategoryCss);
      $('select#advance_search_category_tag_html').css('display', ProductTagCss);

    });
    $('body').on('click', '#advance_search_open_setting', function() {
      $('#advance_search_open_preview').removeClass('nav-tab-active');
      $('#advance_search_open_setting').addClass('nav-tab-active');
      $('#advance_search_open_shortcode').removeClass('nav-tab-active');
      $('#advance_search_open_custom_css').removeClass('nav-tab-active');
      $('#woo-advance-search-overview-tab').removeClass('nav-tab-active');

      $('.woo-advance-search-setting-tab').css('display', 'block');
      $('.woo-advance-search-preview-tab').css('display', 'none');
      $('.woo-advance-search-shortcode-tab').css('display', 'none');
      $('.woo-advance-search-custom-css').css('display', 'none');
      $('.woo-advance-search-overview-tab').css('display', 'none');
      $('.advance_search_for_woocommerce_save_btn').css('display', 'block');
    });

    $('body').on('click', '#advance_search_open_shortcode', function() {
      $('#advance_search_open_preview').removeClass('nav-tab-active');
      $('#advance_search_open_shortcode').addClass('nav-tab-active');
      $('#advance_search_open_setting').removeClass('nav-tab-active');
      $('#woo-advance-search-overview-tab').removeClass('nav-tab-active');
      $('#advance_search_open_custom_css').removeClass('nav-tab-active');

      $('.woo-advance-search-shortcode-tab').css('display', 'block');
      $('.woo-advance-search-setting-tab').css('display', 'none');
      $('.woo-advance-search-preview-tab').css('display', 'none');
      $('.woo-advance-search-custom-css').css('display', 'none');
      $('.woo-advance-search-overview-tab').css('display', 'none');
      $('.advance_search_for_woocommerce_save_btn').css('display', 'block');

    });

    $('body').on('click', '#advance_search_open_custom_css', function() {
      $('#advance_search_open_preview').removeClass('nav-tab-active');
      $('#advance_search_open_shortcode').removeClass('nav-tab-active');
      $('#advance_search_open_setting').removeClass('nav-tab-active');
      $('#woo-advance-search-overview-tab').removeClass('nav-tab-active');
      $('#advance_search_open_custom_css').addClass('nav-tab-active');


      $('.woo-advance-search-shortcode-tab').css('display', 'none');
      $('.woo-advance-search-setting-tab').css('display', 'none');
      $('.woo-advance-search-preview-tab').css('display', 'none');
      $('.woo-advance-search-overview-tab').css('display', 'none');
      $('.advance_search_for_woocommerce_save_btn').css('display', 'block');
      $('.woo-advance-search-custom-css').css('display', 'block');

    });

    $('body').on('click', '#woo-advance-search-overview-tab', function() {
      $('#advance_search_open_preview').removeClass('nav-tab-active');
      $('#advance_search_open_shortcode').removeClass('nav-tab-active');
      $('#advance_search_open_setting').removeClass('nav-tab-active');
      $('#advance_search_open_custom_css').removeClass('nav-tab-active');
      $('#woo-advance-search-overview-tab').addClass('nav-tab-active');

      $('.woo-advance-search-shortcode-tab').css('display', 'none');
      $('.woo-advance-search-setting-tab').css('display', 'none');
      $('.woo-advance-search-preview-tab').css('display', 'none');
      $('.woo-advance-search-custom-css').css('display', 'none');
      $('.advance_search_for_woocommerce_save_btn').css('display', 'none');
      $('.woo-advance-search-overview-tab').css('display', 'block');

    });

    $(document).on('click', '#advance_search_live_ajax', function() {
      var _selector = $(this);
      var _data;
      var _checkboxVal = 'unchecked';
      if (_selector.is(':checked')) {
        _checkboxVal = 'checked';
      }

      _data = {
        'action': 'woo_setting_ajax_checked',
        '_checkbox_val': _checkboxVal,
      };
      $.ajax({
        dataType: 'JSON',
        url: adminajaxjs.adminajaxjsurl,
        type: 'POST',
        data: _data,
        success: function(response) {
          if ('success_message_ajax' === response.data.message){
            if ('checked' === response.data.checkbox_val){
              $(response.data.html).insertAfter('.advance_ajax');
            } else {
              $('.showcase_ajax').remove();
            }

          }
        },
      });


    });

    clipboard = new Clipboard('.btn');

    clipboard.on('success', function(e) {
      e.clearSelection();
    });

    clipboard.on('error', function(e) {
    });
  });

  /**/

})(jQuery);
