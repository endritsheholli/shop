<template>
  <b-nav-item-dropdown right no-caret>
    <template slot="button-content">
      <i class="icon-list" /><b-badge pill variant="warning">
        {{ itemsCount }}
      </b-badge>
    </template>
    <b-dropdown-header tag="div" class="dropdown-menu-lg text-center">
      <strong>You have {{ itemsCount }} pending tasks</strong>
    </b-dropdown-header>
    <b-dropdown-item>
      <div class="small mb-1">
        Upgrade NPM &amp; Bower <span class="float-right"><strong>0%</strong></span>
      </div>
      <b-progress class="progress-xs" variant="info" :value="0" />
    </b-dropdown-item>
    <b-dropdown-item>
      <div class="small mb-1">
        ReactJS Version <span class="float-right"><strong>25%</strong></span>
      </div>
      <b-progress class="progress-xs" variant="danger" :value="25" />
    </b-dropdown-item>
    <b-dropdown-item>
      <div class="small mb-1">
        VueJS Version <span class="float-right"><strong>50%</strong></span>
      </div>
      <b-progress class="progress-xs" variant="warning" :value="50" />
    </b-dropdown-item>
    <b-dropdown-item>
      <div class="small mb-1">
        Add new layouts <span class="float-right"><strong>75%</strong></span>
      </div>
      <b-progress class="progress-xs" variant="info" :value="75" />
    </b-dropdown-item>
    <b-dropdown-item>
      <div class="small mb-1">
        Angular 2 Cli Version <span class="float-right"><strong>100%</strong></span>
      </div>
      <b-progress class="progress-xs" variant="success" :value="100" />
    </b-dropdown-item>
    <b-dropdown-item class="text-center">
      <strong>View all tasks</strong>
    </b-dropdown-item>
  </b-nav-item-dropdown>
</template>
<script>
export default {
  name: 'HeaderDropdownTasks',
  data: () => {
    return { itemsCount: 15 }
  }
}
</script>
