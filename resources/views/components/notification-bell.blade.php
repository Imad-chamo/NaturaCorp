<div x-data="initNotifications({{$unreadCount ?? 0}})" style="position:relative;">
  <button @click="toggle"
          style="position:relative; display:flex; align-items:center; justify-content:center; width:32px; height:32px; background:transparent; border:1px solid transparent; border-radius:8px; cursor:pointer; transition:all 0.15s; color:#A8C4AB;"
          onmouseover="this.style.borderColor='#E2EAE3'; this.style.background='#F4F7F4'"
          onmouseout="this.style.borderColor='transparent'; this.style.background='transparent'">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 01-3.46 0"/>
    </svg>
    <template x-if="unreadCount > 0">
      <span x-text="unreadCount"
            style="position:absolute; top:-2px; right:-2px; min-width:16px; height:16px; background:#DC2626; color:#fff; font-size:9px; font-weight:700; border-radius:8px; display:flex; align-items:center; justify-content:center; padding:0 3px; border:2px solid #fff;"></span>
    </template>
  </button>

  <div x-show="open" @click.away="open = false"
       style="display:none; position:absolute; right:0; top:calc(100% + 8px); width:310px; background:#fff; border:1px solid #E2EAE3; border-radius:12px; box-shadow:0 8px 24px rgba(0,0,0,0.1); z-index:200; overflow:hidden;">

    <div style="display:flex; align-items:center; justify-content:space-between; padding:12px 16px; border-bottom:1px solid #E2EAE3;">
      <span style="font-size:12px; font-weight:600; color:#1A2B1E;">Notifications</span>
      <button @click="markAllAsRead"
              style="font-size:12px; color:#16A34A; background:transparent; border:none; cursor:pointer; font-family:'DM Sans',sans-serif; font-weight:500;"
              onmouseover="this.style.color='#15803D'" onmouseout="this.style.color='#16A34A'">
        Tout lire
      </button>
    </div>

    <ul style="max-height:280px; overflow-y:auto; margin:0; padding:0; list-style:none;">
      <template x-for="notification in notifications" :key="notification.id">
        <li @click="markAsRead(notification.id)"
            style="padding:11px 16px; border-bottom:1px solid #F4F7F4; cursor:pointer; transition:background 0.12s; display:flex; align-items:flex-start; justify-content:space-between; gap:10px;"
            onmouseover="this.style.background='#F9FBF9'" onmouseout="this.style.background='transparent'">
          <div>
            <p style="margin:0; font-size:13px; font-weight:500; color:#1A2B1E;" x-text="notification.titre"></p>
            <p style="margin:2px 0 0; font-size:12px; color:#6B9270;" x-text="notification.contenu"></p>
          </div>
          <template x-if="!notification.est_lu">
            <span style="width:7px; height:7px; border-radius:50%; background:#16A34A; flex-shrink:0; margin-top:5px;"></span>
          </template>
        </li>
      </template>
      <template x-if="notifications.length === 0">
        <li style="padding:24px 16px; text-align:center; font-size:13px; color:#A8C4AB;">Aucune notification</li>
      </template>
    </ul>
  </div>
</div>
