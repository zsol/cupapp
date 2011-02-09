function replyTo(commentId) {
    var commentbox = document.getElementById('replay_comment_comment');
    commentbox.focus();
    if (commentbox.value != "")
	commentbox.value += "\n";
    commentbox.value += "#" + commentId + ": ";
}