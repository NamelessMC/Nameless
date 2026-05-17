{include file='header.tpl'}

<div class="nx-container py-16 text-center">
  <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-bg-elevated text-text-muted mb-4">
    {include file='components/icon.tpl' name='user' size=28}
  </div>
  <h1 class="font-display text-3xl font-bold tracking-tight mb-2">404</h1>
  <p class="text-text-secondary text-sm mb-6">User does not exist.</p>
  <div class="flex justify-center gap-2">
    <button class="nx-btn nx-btn--outline" onclick="javascript:history.go(-1)">Back</button>
    <a class="nx-btn nx-btn--primary" href="/">Home</a>
  </div>
</div>
</body>
</html>
