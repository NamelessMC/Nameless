# Nexus · Change Summary (Iteration 1)

This document summarises every file added or touched. The base NamelessMC code, all modules, the `core/` tree, and the `DefaultRevamp` theme are **untouched**.

## Created files (all under `custom/templates/Nexus/`)

### Theme bootstrap & build
- `template.php` — registers `Nexus_Template extends SmartyTemplateBase`, includes jQuery, jQuery-Cookie, Font-Awesome from `AssetTree`, loads our compiled CSS+JS, exposes all the JS variables `DefaultRevamp` consumers expect.
- `package.json` — devDeps: tailwindcss, @tailwindcss/forms, @tailwindcss/typography, postcss, autoprefixer, alpinejs, esbuild, npm-run-all.
- `tailwind.config.js` — token-aware (reads CSS variables), JIT, custom `nx-*` colors/shadows/radius, custom animations (`fade-in`, `scale-in`, `slide-up`, `shimmer`, `pulse-glow`), `3xl` breakpoint.
- `postcss.config.js`
- `.gitignore` — excludes `node_modules/`, `assets/css/*.css`, `assets/js/*.js`.

### Design system (source)
- `src/styles/tokens.css` — all colors, radius, spacing, shadow, type, motion as CSS custom properties. Dark + light variants.
- `src/styles/base.css` — element resets, headings, focus ring, scrollbars, code, kbd, hr.
- `src/styles/components.css` — `.nx-container`, `.nx-surface`, `.nx-card`, `.nx-btn` (+ variants), `.nx-input` / `.nx-textarea` / `.nx-select`, `.nx-checkbox` / `.nx-radio`, `.nx-badge`, `.nx-avatar`, `.nx-alert`, `.nx-modal-*`, `.nx-dropdown`, `.nx-tabs`, `.nx-skeleton`, `.nx-tooltip`, `.nx-pagination`, `.nx-table`, `.nx-toast*`, `.nx-kbd`, `.nx-spinner`, `.nx-divider`.
- `src/styles/overrides.css` — legacy Fomantic compatibility: `.ui.segment`, `.ui.message`, `.ui.button`, `.ui.form`, `.ui.header`, `.ui.divider`, `.ui.label`, `.ui.card`, `.ui.grid` (CSS-grid bridge), `.ui.container`, `.ui.list`, `.ui.modal`, `.forum_post`, `.ui.inverted.vertical.footer.segment`, `table.dataTable*`, `.select2-container`.
- `src/styles/main.css` — entry that imports tokens, runs `@tailwind base/components/utilities`, then imports base, components, overrides.

### Client JS
- `src/scripts/main.js` — Alpine bootstrap, theme/toast/modal/mobileNav/palette stores, fuzzy-search palette, `x-tooltip` + `x-clipboard` directives, Cmd-K + Escape shortcuts, loading-time injector, IE-warning auto-hide, nav-key active marker.

### Smarty partials
- `components/icon.tpl` — inline SVG sprite, ~50 named icons (search, menu, close, user, forum, bell, mail, shield, settings, sun, moon, plus, edit, trash, pin, lock, star, heart, reply, globe, discord, minecraft, github, twitter, youtube, eye, filter, sort, grid, list, calendar, clock, tag, external, arrow-*, info, alert, sparkle, palette, server, trophy, etc.).
- `components/command_palette.tpl` — Strg-K/Cmd-K palette, seeded from `$NAV_LINKS`, fuzzy filter, kbd hints.
- `components/toasts.tpl` — Alpine-store-driven toast container.

### Layout
- `header.tpl` — `<head>`, meta, OG/Twitter cards, pre-paint theme script, font preconnect, asset includes, skip-link.
- `navbar.tpl` — sticky glassmorphic top bar, primary nav (desktop), off-canvas drawer (mobile), Cmd-K trigger, theme toggle, user/account dropdowns, optional MC-server hero, IE/update/announcement/maintenance/widget-top region.
- `footer.tpl` — site footer (3 cols), social links, auto-language toggle, legacy global-warning modal, toast + palette includes, `$TEMPLATE_JS` injection, dark/light Ajax bridge.

### Public pages
- `index.tpl` — News-card feed or custom-home, widgets-left/right.
- `portal.tpl` — placeholder mirroring DefaultRevamp.
- `profile.tpl` — banner, avatar, groups, tabs (feed/about/custom), wall posts with reactions, replies, edit/delete modals, banner-change modal, block modal.
- `leaderboards.tpl` — Podium-style ranking with sidebar tab nav.
- `login.tpl` — split layout, OAuth providers, captcha hook, register CTA.
- `register.tpl` — dynamic field renderer (text/textarea/date/password/select/number/email/radio/checkbox), OAuth flow support, captcha, t&c, email-verify hook.
- `forgot_password.tpl`, `change_password.tpl`, `complete_signup.tpl`, `tfa.tpl`, `authme.tpl`, `authme_email.tpl`, `registration_disabled.tpl`, `user_not_exist.tpl` — all with consistent auth styling.
- `404.tpl`, `403.tpl`, `maintenance.tpl` — gradient text errors, action buttons.
- `cookies.tpl`, `terms.tpl`, `privacy.tpl` — surface card + prose styling.
- `custom.tpl` — wrapper for arbitrary custom content with widgets-left/right.
- `custom_basic.tpl` — bare-bones wrapper.
- `status.tpl` — server cards grid with copyable IP badges.
- `reactions_modal.tpl` — emoji-tabbed reaction details modal body.
- `user_popover.tpl` — hover card with avatar/groups/stats.

### User CP subpages
- `user/navigation.tpl` — Nexus-styled vertical nav.
- `user/index.tpl` — overview with detail list + optional chart canvas.
- `user/settings.tpl` — full settings form (profile, email, password, 2FA, avatar) split into surface cards.
- `user/{alert,alerts,connections,messaging,new_message,notification_settings,oauth,placeholders,sessions,tfa,view_message}.tpl` — initial baseline copied from `DefaultRevamp`; the override layer in `overrides.css` already styles them consistently. Targeted polish lives in I2.

### Forum
- `forum/forum_index.tpl` — category cards, per-subforum row with last-post avatar, search input.
- `forum/view_forum.tpl` — sticky discussion section, topic list, subforums list, new-topic CTA.
- `forum/_topic_row.tpl` — reusable topic-row partial.
- `forum/view_topic.tpl` — Reddit-style post stream with author sidebar, reactions, mod actions dropdown, share dropdown, quick-reply box, legacy report/delete/spam modals.
- `forum/new_topic.tpl`, `forum/forum_edit_post.tpl` — TinyMCE-host textarea + label chips.
- `forum/search.tpl`, `forum/search_results.tpl` — single-card search; result list with read-full CTA.
- `forum/following_topics.tpl` — user-cp following list with unfollow controls.
- `forum/merge.tpl`, `forum/move.tpl` — single-form moderator actions.
- `forum/view_forum_no_discussions.tpl` — empty-state with CTA.
- `forum/view_forum_confirm_redirect.tpl` — redirect confirmation card.
- `forum/profile_tab.tpl` — recent forum posts for the profile.

### Members
- `members/members.tpl` — list nav, fuzzy search, group filter, new-members avatar grid, dynamic list renderer (renderList JS rewired to Nexus DOM).

### Widgets
- `widgets/online_staff.tpl` · `widgets/online_users.tpl` · `widgets/server_status.tpl` · `widgets/statistics.tpl` · `widgets/minecraft_account.tpl` · `widgets/profile_posts.tpl` · `widgets/reactions.tpl` · `widgets/cookie_notice.tpl` · `widgets/widget_error.tpl`
- `widgets/forum/latest_posts.tpl`

### Docs
- `README.md` — install, build, activate, customize, smoke-test, troubleshooting.
- `docs/CHANGES.md` — this file.

## Files NOT touched
- `core/**` (NamelessMC core)
- `modules/**` (all modules)
- `custom/templates/DefaultRevamp/**` (untouched, still usable)
- `custom/panel_templates/**` (admin panel — Iteration 2)
- DB schema, controllers, language files
