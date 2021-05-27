<template>
  <div v-if="user" class="navbar-panel">
    <nav class="navbar navbar-expand-lg">
      <button
        class="navbar-toggler"
        type="button"
        data-toggle="collapse"
        data-target="#navbarNav"
        aria-controls="navbarNav"
        aria-expanded="false"
        aria-label="Toggle navigation"
      >
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="nav">
          <template
            v-for="item in navItems.filter(
              (t) =>
                t.role === '' ||
                t.role.split('|').filter((x) => x === user.role)[0]
            )"
          >
            <template v-if="item.title">
              <SidebarNavTitle
                :key="item.id"
                :name="item.name"
                :classes="item.class"
                :wrapper="item.wrapper"
                :icon="item.icon"
              />
            </template>
            <template v-else-if="item.divider">
              <li :key="item.id" class="divider" />
            </template>
            <template v-else>
              <template v-if="item.children">
                <!-- First level dropdown -->
                <SidebarNavDropdown
                  :key="item"
                  :name="item.name"
                  :url="item.url"
                  :icon="item.icon"
                >
                  <template v-for="childL1 in item.children">
                    <template v-if="childL1.children">
                      <!-- Second level dropdown -->
                      <SidebarNavDropdown
                        :key="childL1.id"
                        :name="childL1.name"
                        :url="childL1.url"
                        :icon="childL1.icon"
                      >
                        <li
                          v-for="(childL2, index) in childL1.children"
                          :key="index"
                          class="nav-item"
                        >
                          <SidebarNavLink
                            :name="childL2.name"
                            :url="childL2.url"
                            :icon="childL2.icon"
                            :badge="childL2.badge"
                            :variant="item.variant"
                          />
                        </li>
                      </SidebarNavDropdown>
                    </template>
                    <template v-else>
                      <SidebarNavItem :key="childL1.id" :classes="item.class">
                        <SidebarNavLink
                          :name="childL1.name"
                          :url="childL1.url"
                          :icon="childL1.icon"
                          :badge="childL1.badge"
                          :variant="item.variant"
                        />
                      </SidebarNavItem>
                    </template>
                  </template>
                </SidebarNavDropdown>
              </template>
              <template v-else>
                <SidebarNavItem :key="item.id" :classes="item.class">
                  <SidebarNavLink
                    :name="item.name"
                    :url="item.url"
                    :icon="item.icon"
                    :badge="item.badge"
                    :variant="item.variant"
                  />
                </SidebarNavItem>
              </template>
            </template>
          </template>
        </ul>
      </div>
    </nav>
  </div>
</template>

<script>
import { mapGetters } from "vuex";

import SidebarNavDropdown from "./SidebarNavDropdown.vue";
import SidebarNavLink from "./SidebarNavLink.vue";
import SidebarNavTitle from "./SidebarNavTitle.vue";
import SidebarNavItem from "./SidebarNavItem.vue";
export default {
  name: "Navbar Panel",
  props: {
    navItems: {
      type: Array,
      required: true,
      default: () => [],
    },
  },
  computed: mapGetters({
    user: "auth/user",
  }),
  created() {
  },
  components: {
    SidebarNavDropdown,
    SidebarNavLink,
    SidebarNavTitle,
    SidebarNavItem,
  },
  methods: {
  },
};
</script>

<style lang="css">
.nav-link {
  cursor: pointer;
}
</style>
