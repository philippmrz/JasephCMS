function swapStyle() {
  if(document.getElementById('swapper').innerHTML == 'Normie Mode') {
    document.getElementById('swapper').innerHTML = 'Hacker Mode';
    document.getElementById('pagestyle').setAttribute('href', 'style/normal.css');
  } else {
    document.getElementById('swapper').innerHTML = 'Normie Mode';
    document.getElementById('pagestyle').setAttribute('href', 'style/hacker.css');
  }
}
