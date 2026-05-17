{*
 *  Nexus · Toast container
 *  Rendered once in footer.tpl. Items are pushed via Alpine.store('toast').
*}
<div class="nx-toast-container" x-data>
  <template x-for="t in $store.toast.items" :key="t.id">
    <div class="nx-toast animate-slide-up"
         :class="{
           'border-l-4 border-l-success': t.variant === 'success',
           'border-l-4 border-l-danger':  t.variant === 'danger',
           'border-l-4 border-l-warning': t.variant === 'warning',
           'border-l-4 border-l-info':    t.variant === 'info',
         }">
      <div class="flex-1 min-w-0">
        <div class="font-medium text-text-primary text-sm" x-show="t.title" x-text="t.title"></div>
        <div class="text-text-secondary text-sm" x-show="t.body" x-text="t.body"></div>
      </div>
      <button class="nx-btn nx-btn--ghost nx-btn--icon nx-btn--sm"
              @click="$store.toast.dismiss(t.id)" aria-label="Dismiss">
        {include file='components/icon.tpl' name='close' size=14}
      </button>
    </div>
  </template>
</div>
