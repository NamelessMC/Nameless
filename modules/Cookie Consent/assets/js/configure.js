function configureCookies() {
  $.removeCookie('cookieconsent_status', { path: '/' });
  window.location.reload();
}
