<template>
  <b-nav-item-dropdown right no-caret>
    <template slot="button-content">
      <i class="icon-bell" /><b-badge pill variant="danger">
        {{ itemsCount }}
      </b-badge>
    </template>
    <b-dropdown-header tag="div" class="dropdown-menu-lg text-center">
      <strong>You have {{ itemsCount }} notifications</strong>
    </b-dropdown-header>
    <b-dropdown-item><i class="icon-user-follow text-success" /> New user registered</b-dropdown-item>
    <b-dropdown-item><i class="icon-user-unfollow text-danger" /> User deleted</b-dropdown-item>
    <b-dropdown-item><i class="icon-chart text-info" /> Sales report is ready</b-dropdown-item>
    <b-dropdown-item><i class="icon-basket-loaded text-primary" /> New client</b-dropdown-item>
    <b-dropdown-item><i class="icon-speedometer text-warning" /> Server overloaded</b-dropdown-item>
    <b-dropdown-header tag="div" class="text-center">
      <strong>Server</strong>
    </b-dropdown-header>
    <b-dropdown-item>
      <div class="text-uppercase mb-1">
        <small><b>CPU Usage</b></small>
      </div>
      <b-progress class="progress-xs" variant="info" :value="25" />
      <small class="text-muted">348 Processes. 1/4 Cores.</small>
    </b-dropdown-item>
    <b-dropdown-item>
      <div class="text-uppercase mb-1">
        <small><b>Memory Usage</b></small>
      </div>
      <b-progress class="progress-xs" variant="warning" :value="70" />
      <small class="text-muted">11444GB/16384MB</small>
    </b-dropdown-item>
    <b-dropdown-item>
      <div class="text-uppercase mb-1">
        <small><b>SSD 1 Usage</b></small>
      </div>
      <b-progress class="progress-xs" variant="danger" :value="90" />
      <small class="text-muted">243GB/256GB</small>
    </b-dropdown-item>
  </b-nav-item-dropdown>
</template>
<script>
export default {
  name: 'HeaderDropdownNotif',
  data: () => {
    return { itemsCount: 5 }
  }
}
</script>
