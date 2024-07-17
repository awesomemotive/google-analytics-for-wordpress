jQuery(document).ready(function () {
  if (0 === jQuery('#monsterinsights-metabox-page-insights').length) {
    return;
  }

  jQuery('#monsterinsights_show_page_insights').click(function (event) {
    event.preventDefault();
    jQuery('#monsterinsights-page-insights-content').slideDown('slow');
    jQuery('#monsterinsights_show_page_insights').fadeOut('slow');
  });

  jQuery('#monsterinsights_hide_page_insights').click(function (event) {
    event.preventDefault();
    jQuery('#monsterinsights-page-insights-content').slideUp('slow', function () {
      jQuery('#monsterinsights_show_page_insights').fadeIn('slow');
    });
  });

  jQuery('.monsterinsights-page-insights__tabs-tab').click(function (event) {
    event.preventDefault();
    let tab_target = jQuery(this).data('tab');

    jQuery('.monsterinsights-page-insights__tabs-tab.active').removeClass('active');
    jQuery(this).addClass('active');

    jQuery('.monsterinsights-page-insights-tabs-content__tab.active').removeClass('active');
    jQuery('#' + tab_target).addClass('active');
  });
});
