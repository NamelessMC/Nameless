{*
 *  Nexus · Command Palette
 *  Strg/Cmd+K opens. Fuzzy search over registered items.
 *  Items are seeded from the rendered navigation DOM (no Smarty-encoded JSON
 *  needed, which avoids brace-escape pitfalls).
*}
<template x-teleport="body">
<div x-show="$store.palette.visible"
     x-cloak
     class="fixed inset-0 z-[120] flex items-start justify-center pt-24 px-4"
     x-transition.opacity.duration.150ms
     @keydown.window.escape.prevent="$store.palette.close()">

  <div class="absolute inset-0 bg-black/55 backdrop-blur-md"
       @click="$store.palette.close()"></div>

  <div class="relative w-full max-w-xl nx-surface-elevated rounded-xl overflow-hidden animate-scale-in"
       @click.stop>

    <div class="flex items-center gap-3 px-4 py-3 border-b border-border-subtle">
      {include file='components/icon.tpl' name='search' size=18 class="text-text-muted"}
      <input id="nx-palette-input"
             type="text"
             x-model="$store.palette.query"
             @keydown.down.prevent="$store.palette.move(1)"
             @keydown.up.prevent="$store.palette.move(-1)"
             @keydown.enter.prevent="$store.palette.activate()"
             placeholder="Type a command or search…"
             class="flex-1 bg-transparent outline-none text-text-primary placeholder:text-text-muted text-base"
             autocomplete="off" />
      <span class="nx-kbd">ESC</span>
    </div>

    <div class="max-h-80 overflow-y-auto p-2"
         x-show="$store.palette.filtered.length">
      <template x-for="(item, i) in $store.palette.filtered" :key="i">
        <a :href="item.href || '#'"
           @click="if (item.action) { $event.preventDefault(); item.action(); }"
           @mouseenter="$store.palette.activeIndex = i"
           class="flex items-center gap-3 px-3 py-2.5 rounded-md text-sm cursor-pointer"
           :class="$store.palette.activeIndex === i ? 'bg-accent-subtle text-text-primary' : 'text-text-secondary hover:bg-bg-surface'">
          <span class="flex-1 truncate">
            <span x-text="item.title"></span>
            <span x-show="item.section" class="text-text-muted ml-2" x-text="item.section"></span>
          </span>
          <span x-show="item.shortcut" class="nx-kbd" x-text="item.shortcut"></span>
        </a>
      </template>
    </div>

    <div class="p-8 text-center text-text-muted text-sm"
         x-show="!$store.palette.filtered.length">
      No matches.
    </div>

    <div class="flex items-center justify-between gap-3 px-4 py-2 text-xs text-text-muted bg-bg-inset border-t border-border-subtle">
      <span class="flex items-center gap-1.5">
        <span class="nx-kbd">↑</span><span class="nx-kbd">↓</span> navigate
      </span>
      <span class="flex items-center gap-1.5">
        <span class="nx-kbd">↵</span> select
      </span>
    </div>
  </div>
</div>
</template>

{* Seed palette by walking the rendered nav DOM — no Smarty-encoded JSON. *}
{literal}
<script>
  (function () {
    function bootPalette() {
      if (!window.Alpine || !window.Alpine.store('palette')) return;
      var seen = new Set();
      var items = [];
      function push(el, section) {
        var href = el.getAttribute('href');
        var title = (el.textContent || '').trim().replace(/\s+/g, ' ');
        if (!href || !title || href === '#' || seen.has(href + '|' + title)) return;
        seen.add(href + '|' + title);
        items.push({ title: title, href: href, section: section });
      }
      document.querySelectorAll('header nav a[href]').forEach(function (a) { push(a, 'Navigation'); });
      document.querySelectorAll('header .nx-dropdown__panel a[href]').forEach(function (a) { push(a, 'Menu'); });
      window.Alpine.store('palette').register(items);
    }
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', bootPalette);
    else bootPalette();
  })();
</script>
{/literal}
