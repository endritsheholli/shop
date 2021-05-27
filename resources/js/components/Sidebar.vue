<template>
  <div v-if="user" class="sidebar">
    <!-- <SidebarHeader />
    <SidebarForm />-->
    <!-- <div class="sidebarheader">
      <div class="sidebar-img">
        <img :src="user.img_path ? user.img_path : user.photo_url" />
        <!- <img v-if="user.img_path" src="../../img/noimg.jpg" /> --
      </div>
      <div class="sidebar-user">
        <p style="font-weight: 600;">{{user.shop_name.name ? user.shop_name.name : 'Marketi'}}</p>
        <p>{{user.name}} {{user.last_name}}</p>
        <small>{{user.phone}}</small>
      </div>
    </div> -->
    <nav class="sidebar-nav">
      <div slot="header" />
      <ul class="nav">
        <template
          v-for="item in navItems.filter(t => t.role === '' || t.role.split('|').filter(x => x === user.role)[0])"
        >
          <template v-if="item.group">
            <span class="nav-item-group mt-3">{{ item.group }}</span>
          </template>
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
              <SidebarNavDropdown :key="item" :name="item.name" :url="item.url" :icon="item.icon">
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
      <slot />
    </nav>
    <!-- <SidebarFooter />
    <SidebarMinimizer />-->
  </div>
</template>

<script>
import { mapGetters } from "vuex";
import SidebarFooter from "./SidebarFooter.vue";
import SidebarForm from "./SidebarForm.vue";
import SidebarHeader from "./SidebarHeader.vue";
import SidebarMinimizer from "./SidebarMinimizer.vue";
import SidebarNavDropdown from "./SidebarNavDropdown.vue";
import SidebarNavLink from "./SidebarNavLink.vue";
import SidebarNavTitle from "./SidebarNavTitle.vue";
import SidebarNavItem from "./SidebarNavItem.vue";
export default {
  name: "Sidebar",
  props: {
    navItems: {
      type: Array,
      required: true,
      default: () => []
    }
  },
  computed: mapGetters({
    user: "auth/user"
  }),
  created() {
    console.log("iuser", this.user);
  },
  components: {
    SidebarFooter,
    SidebarForm,
    SidebarHeader,
    SidebarMinimizer,
    SidebarNavDropdown,
    SidebarNavLink,
    SidebarNavTitle,
    SidebarNavItem
  },
  methods: {
    handleClick(e) {
      e.preventDefault();
      e.target.parentElement.classList.toggle("open");
    }
  }
};
</script>

<style lang="css">
.nav-link {
  cursor: pointer;
}
.sidebarheader {
  display: flex;
  justify-content: flex-start;
  padding-top: 3rem;
}
.sidebar-img {
  width: 70px;
  height: 70px;
}
.sidebar-img img {
  border-radius: 5px;
  width: 100%;
  height: 100%;
  object-fit: contain;
}
.sidebar-user {
  padding: 0 1rem;
}
.sidebar-user p {
  margin: 0;
}
.sidebar-user small {
  margin-top: -5px;
  display: block;
}
</style>
