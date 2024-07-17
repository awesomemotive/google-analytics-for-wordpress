var MonsterInsights_Popular_Posts = {

  init: function () {
    document.addEventListener("DOMContentLoaded", this.grab_widgets_with_ajax);
  },

  grab_widgets_with_ajax: function () {
    var xhr = new XMLHttpRequest();
    var url = monsterinsights_pp.ajaxurl;
    var widgets_jsons = document.querySelectorAll('.monsterinsights-popular-posts-widget-json'),
      i,
      widgets_length = widgets_jsons.length;

    var params = 'action=monsterinsights_popular_posts_get_widget_output&post_id=' + monsterinsights_pp.post_id;
    params += '&_ajax_nonce=' + monsterinsights_pp.nonce;

    for (i = 0; i < widgets_length; ++i) {
      params += '&data[]=' + widgets_jsons[i].innerHTML
    }
    xhr.open('POST', url);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
      if (xhr.status === 200) {
        let rendered_widgets = JSON.parse(xhr.responseText);
        for (i = 0; i < widgets_length; ++i) {
          widgets_jsons[i].parentElement.innerHTML = rendered_widgets[i];
        }
      }
    };
    xhr.send(params);
  },
};

MonsterInsights_Popular_Posts.init();
