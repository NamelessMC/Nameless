if (window.cookieconsent === undefined) {
  console.warn('Failed to initialise cookie consent, it may be blocked by your browser or a browser extension');
} else {
  window.cookieconsent.initialise({
    onStatusChange: function() {
      window.location.reload();
    },
    //"{x}"
  });
}
