{*
 *  Nexus · Inline SVG Icon
 *  Usage: {include file='components/icon.tpl' name='search' size=18}
 *  All paths are 24×24 stroke icons (Phosphor / Lucide style).
 *  Set `class` to add Tailwind classes. Default stroke uses currentColor.
*}
{if !isset($size)}{assign var=size value=18}{/if}
{if !isset($class)}{assign var=class value=""}{/if}
{if !isset($strokeWidth)}{assign var=strokeWidth value=1.6}{/if}

<svg xmlns="http://www.w3.org/2000/svg" width="{$size}" height="{$size}" viewBox="0 0 24 24" fill="none"
     stroke="currentColor" stroke-width="{$strokeWidth}" stroke-linecap="round" stroke-linejoin="round"
     class="nx-icon {$class}" aria-hidden="true" focusable="false">
  {if $name eq 'search'}      <circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/>
  {elseif $name eq 'menu'}    <path d="M4 6h16M4 12h16M4 18h16"/>
  {elseif $name eq 'close'}   <path d="M6 6l12 12M18 6 6 18"/>
  {elseif $name eq 'chevron-down'}  <path d="m6 9 6 6 6-6"/>
  {elseif $name eq 'chevron-right'} <path d="m9 6 6 6-6 6"/>
  {elseif $name eq 'chevron-left'}  <path d="m15 6-6 6 6 6"/>
  {elseif $name eq 'arrow-right'}   <path d="M5 12h14M13 5l7 7-7 7"/>
  {elseif $name eq 'arrow-left'}    <path d="M19 12H5M11 5l-7 7 7 7"/>
  {elseif $name eq 'sun'}    <circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M5 5l1.5 1.5M17.5 17.5 19 19M2 12h2M20 12h2M5 19l1.5-1.5M17.5 6.5 19 5"/>
  {elseif $name eq 'moon'}   <path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8Z"/>
  {elseif $name eq 'user'}   <circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 4-7 8-7s8 3 8 7"/>
  {elseif $name eq 'users'}  <path d="M16 18v-1c0-2.2-1.8-4-4-4H8c-2.2 0-4 1.8-4 4v1"/><circle cx="10" cy="7" r="3"/><path d="M22 18v-1c0-1.9-1.3-3.5-3-3.9"/><path d="M17 5.1A3 3 0 0 1 17 11"/>
  {elseif $name eq 'home'}   <path d="m3 11 9-7 9 7v9a1 1 0 0 1-1 1h-5v-7H10v7H5a1 1 0 0 1-1-1z"/>
  {elseif $name eq 'forum'}  <path d="M21 14a2 2 0 0 1-2 2H7l-4 4V6a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
  {elseif $name eq 'bell'}   <path d="M6 8a6 6 0 0 1 12 0c0 7 3 8 3 8H3s3-1 3-8"/><path d="M10 21a2 2 0 0 0 4 0"/>
  {elseif $name eq 'mail'}   <rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/>
  {elseif $name eq 'shield'} <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/>
  {elseif $name eq 'settings'}<circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.7 1.7 0 0 0 .3 1.8l.1.1a2 2 0 0 1-2.8 2.8l-.1-.1a1.7 1.7 0 0 0-1.8-.3 1.7 1.7 0 0 0-1 1.5V21a2 2 0 0 1-4 0v-.1a1.7 1.7 0 0 0-1-1.5 1.7 1.7 0 0 0-1.8.3l-.1.1a2 2 0 0 1-2.8-2.8l.1-.1a1.7 1.7 0 0 0 .3-1.8 1.7 1.7 0 0 0-1.5-1H3a2 2 0 0 1 0-4h.1a1.7 1.7 0 0 0 1.5-1 1.7 1.7 0 0 0-.3-1.8l-.1-.1a2 2 0 0 1 2.8-2.8l.1.1a1.7 1.7 0 0 0 1.8.3h.1a1.7 1.7 0 0 0 1-1.5V3a2 2 0 0 1 4 0v.1a1.7 1.7 0 0 0 1 1.5 1.7 1.7 0 0 0 1.8-.3l.1-.1a2 2 0 0 1 2.8 2.8l-.1.1a1.7 1.7 0 0 0-.3 1.8v.1a1.7 1.7 0 0 0 1.5 1H21a2 2 0 0 1 0 4h-.1a1.7 1.7 0 0 0-1.5 1z"/>
  {elseif $name eq 'log-out'}<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="m16 17 5-5-5-5"/><path d="M21 12H9"/>
  {elseif $name eq 'log-in'} <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><path d="m10 17 5-5-5-5"/><path d="M15 12H3"/>
  {elseif $name eq 'plus'}   <path d="M12 5v14M5 12h14"/>
  {elseif $name eq 'check'}  <path d="m5 12 5 5L20 7"/>
  {elseif $name eq 'edit'}   <path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z"/>
  {elseif $name eq 'trash'}  <path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><path d="M19 6 18 20a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
  {elseif $name eq 'pin'}    <path d="M12 17v5"/><path d="M9 11V5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v6l3 3v2H6v-2z"/>
  {elseif $name eq 'lock'}   <rect x="4" y="11" width="16" height="10" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/>
  {elseif $name eq 'star'}   <path d="m12 3 2.7 6.1 6.6.6-5 4.5 1.5 6.5L12 17.3 6.2 20.7l1.5-6.5-5-4.5 6.6-.6z"/>
  {elseif $name eq 'heart'}  <path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1-1.1a5.5 5.5 0 1 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8Z"/>
  {elseif $name eq 'reply'}  <path d="M9 17 4 12l5-5"/><path d="M4 12h11a5 5 0 0 1 5 5v2"/>
  {elseif $name eq 'globe'}  <circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3a14 14 0 0 1 0 18M12 3a14 14 0 0 0 0 18"/>
  {elseif $name eq 'discord'}<path d="M18.9 5.5A16 16 0 0 0 14.7 4l-.2.4a14 14 0 0 1 4 1.4 14 14 0 0 0-9 0 14 14 0 0 1 4-1.4L13.3 4a16 16 0 0 0-4.2 1.5C5.6 10 4.7 14.3 5.1 18.6a16 16 0 0 0 4.8 2.4l.4-.5a11 11 0 0 1-2-1c.2-.1.4-.3.6-.4a11 11 0 0 0 10.2 0c.2.1.4.3.6.4a11 11 0 0 1-2 1l.4.5a16 16 0 0 0 4.8-2.4c.5-5-.9-9.3-3.9-13.1ZM10.3 16c-.9 0-1.7-.8-1.7-1.9s.7-1.9 1.6-1.9c1 0 1.7.9 1.7 2s-.7 1.8-1.6 1.8Zm5.4 0c-.9 0-1.6-.8-1.6-1.9s.7-1.9 1.6-1.9 1.7.9 1.7 2-.7 1.8-1.7 1.8Z"/>
  {elseif $name eq 'minecraft'}<rect x="4" y="4" width="16" height="16" rx="2"/><path d="M4 12h16M12 4v16"/>
  {elseif $name eq 'github'} <path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.9c0-1 .1-1.4-.5-2 3-.3 5.5-1.5 5.5-6a4.6 4.6 0 0 0-1.3-3.2 4.3 4.3 0 0 0-.1-3.2s-1.1-.3-3.5 1.3a12.3 12.3 0 0 0-6.2 0C7 2.3 5.9 2.6 5.9 2.6a4.3 4.3 0 0 0-.1 3.2A4.6 4.6 0 0 0 4.5 9c0 4.5 2.5 5.7 5.5 6-.6.6-.6 1.2-.5 2V21"/>
  {elseif $name eq 'twitter'}<path d="M22 5.8c-.7.4-1.5.6-2.4.8a4.1 4.1 0 0 0 1.8-2.3 8.3 8.3 0 0 1-2.6 1A4.1 4.1 0 0 0 12 9.1c0 .3 0 .6.1.9A11.7 11.7 0 0 1 3.4 5a4.1 4.1 0 0 0 1.3 5.5 4 4 0 0 1-1.9-.5v.1a4.1 4.1 0 0 0 3.3 4 4.1 4.1 0 0 1-1.8.1 4.1 4.1 0 0 0 3.8 2.8A8.3 8.3 0 0 1 2 18.7a11.7 11.7 0 0 0 6.3 1.8c7.5 0 11.7-6.3 11.7-11.7v-.5A8.4 8.4 0 0 0 22 5.8Z"/>
  {elseif $name eq 'youtube'}<path d="M22.5 7.2a3 3 0 0 0-2-2.2C18.6 4.5 12 4.5 12 4.5s-6.6 0-8.5.5a3 3 0 0 0-2 2.2A31.2 31.2 0 0 0 1 12a31.2 31.2 0 0 0 .5 4.8 3 3 0 0 0 2 2.2c1.9.5 8.5.5 8.5.5s6.6 0 8.5-.5a3 3 0 0 0 2-2.2A31.2 31.2 0 0 0 23 12a31.2 31.2 0 0 0-.5-4.8z"/><path d="M10 15V9l5 3z"/>
  {elseif $name eq 'eye'}    <path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/>
  {elseif $name eq 'eye-off'}<path d="M9.9 5a10.4 10.4 0 0 1 2.1-.2c6 0 10 7.2 10 7.2a18.4 18.4 0 0 1-2.2 3.2M6.7 6.7C3.3 9 2 12 2 12s4 7.2 10 7.2a10.4 10.4 0 0 0 4-.8M14.1 14.1a3 3 0 1 1-4.2-4.2M1 1l22 22"/>
  {elseif $name eq 'filter'} <path d="M22 3H2l8 9.5V19l4 2v-8.5z"/>
  {elseif $name eq 'sort'}   <path d="M7 4v16M3 8l4-4 4 4M17 20V4M21 16l-4 4-4-4"/>
  {elseif $name eq 'grid'}   <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>
  {elseif $name eq 'list'}   <path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/>
  {elseif $name eq 'calendar'}<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>
  {elseif $name eq 'clock'}  <circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>
  {elseif $name eq 'tag'}    <path d="M20.6 13.4 13.4 20.6a2 2 0 0 1-2.8 0L3 13V3h10l7.6 7.6a2 2 0 0 1 0 2.8Z"/><circle cx="7.5" cy="7.5" r="1"/>
  {elseif $name eq 'external'}<path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><path d="M15 3h6v6M10 14 21 3"/>
  {elseif $name eq 'arrow-up'}<path d="M12 19V5M5 12l7-7 7 7"/>
  {elseif $name eq 'arrow-down'}<path d="M12 5v14M5 12l7 7 7-7"/>
  {elseif $name eq 'info'}   <circle cx="12" cy="12" r="9"/><path d="M12 8v.01M11 12h1v4h1"/>
  {elseif $name eq 'alert'}  <path d="M10.3 3.9 2.1 18a2 2 0 0 0 1.7 3h16.4a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0Z"/><path d="M12 9v4M12 17h.01"/>
  {elseif $name eq 'sparkle'}<path d="M12 3v3M12 18v3M3 12h3M18 12h3M5.6 5.6l2.1 2.1M16.3 16.3l2.1 2.1M5.6 18.4l2.1-2.1M16.3 7.7l2.1-2.1"/>
  {elseif $name eq 'palette'}<circle cx="13.5" cy="6.5" r=".5"/><circle cx="17.5" cy="10.5" r=".5"/><circle cx="8.5" cy="7.5" r=".5"/><circle cx="6.5" cy="12.5" r=".5"/><path d="M12 2A10 10 0 1 0 22 12c0-1.1-.9-2-2-2h-2a2 2 0 0 1-2-2 2 2 0 0 0-2-2z"/>
  {elseif $name eq 'server'} <rect x="2" y="3" width="20" height="6" rx="1"/><rect x="2" y="15" width="20" height="6" rx="1"/><path d="M6 6h.01M6 18h.01"/>
  {elseif $name eq 'trophy'} <path d="M6 9H4a2 2 0 0 1-2-2V5a1 1 0 0 1 1-1h3M18 9h2a2 2 0 0 0 2-2V5a1 1 0 0 0-1-1h-3M4 22h16M10 14.7V19a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-4.3M18 2H6v9a6 6 0 1 0 12 0V2Z"/>
  {else}<circle cx="12" cy="12" r="9"/>
  {/if}
</svg>
