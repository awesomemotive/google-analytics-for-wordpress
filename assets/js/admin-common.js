jQuery(document).ready(function ($) {
  /**
   * Dismissable Notices
   * - Sends an AJAX request to mark the notice as dismissed
   */
  $('div.monsterinsights-notice').on('click', 'button.notice-dismiss', function (e) {
    e.preventDefault();
    $(this).closest('div.monsterinsights-notice').fadeOut();

    // If this is a dismissible notice, it means we need to send an AJAX request
    if ($(this).parent().hasClass('is-dismissible')) {
      $.post(
        monsterinsights_admin_common.ajax,
        {
          action: 'monsterinsights_ajax_dismiss_notice',
          nonce: monsterinsights_admin_common.dismiss_notice_nonce,
          notice: $(this).parent().data('notice')
        },
        function (response) {
        },
        'json'
      );
    }

  });

  $('div.wp-menu-name > .monsterinsights-menu-notification-indicator').click(function (event) {
    event.preventDefault();
    event.stopPropagation();

    location.href = monsterinsights.reports_url + '&open=monsterinsights_notification_sidebar';
  });
});

var submenu_item = document.querySelector('.monsterinsights-upgrade-submenu');
if (null !== submenu_item) {
  var anchorTag = submenu_item.parentNode;

  if ( anchorTag ) {
    anchorTag.setAttribute("target", "_blank");
    anchorTag.setAttribute("rel", "noopener");

    var li = anchorTag.parentNode;

    if (li) {
      li.classList.add('monsterinsights-submenu-highlight');
    }
  }
}

var automated_submenu_item = document.querySelector('.monsterinsights-automated-submenu');
if (null !== automated_submenu_item) {
  var anchorTag = automated_submenu_item.parentNode;

  if ( anchorTag ) {
    anchorTag.setAttribute("target", "_blank");
    anchorTag.setAttribute("rel", "noopener");
    anchorTag.setAttribute("style", "color:#1da867");
  }
}
