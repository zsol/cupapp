function makeToggleAble( clickElementId, toggleElementId) {
  $("#" + clickElementId).click(function (){
    if ($("#" + toggleElementId).is(":hidden")) {
      $("#" + toggleElementId).slideDown("slow");
    } else {
      $("#" + toggleElementId).slideUp("slow");
    }
  });
}

$(document).ready(function() {
    makeToggleAble('message_log_header', 'message_log_messages');
    makeToggleAble('winner_box', 'winner_list');
});
