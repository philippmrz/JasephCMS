function vis() {
  if (document.getElementById('settings-visibility-switch').checked) {
    document.getElementById('settings-visibility-info').innerHTML = 'Everyone sees you as the author of your posts';
  } else {
    document.getElementById('settings-visibility-info').innerHTML = 'Your posts are posted anonymously.';
  }
}
