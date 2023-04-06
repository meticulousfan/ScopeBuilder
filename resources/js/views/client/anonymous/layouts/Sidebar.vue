<template>
  <aside class="page-sidebar">
      <div class="sidebar-inner">
          <div class="sidebar-section">
              <div class="page-logo">
                  <img :src="root_path+'/ui_assets/img/logo.svg'" alt="Scopebuilder">
              </div>
          </div>

          <div class="sidebar-section">

              <nav class="sidebar-nav">
                  <ul>
                      <li>
                          <a href="javascript:void(0)" @click="opneSignupDialogue">
                              <svg class="link-icon">
                                  <use :xlink:href="root_path+'/ui_assets/img/icons-sprite.svg#user'"></use>
                              </svg>
                              <span class="link-text">{{ locale.create_account }}</span>
                          </a>
                      </li>
                      <li>
                          <a href="javascript:void(0)" @click="openLoginDialogue">
                              <svg class="link-icon">
                                  <use :xlink:href="root_path+'/ui_assets/img/icons-sprite.svg#user'"></use>
                              </svg>
                              <span class="link-text">{{ locale.login }}</span>
                          </a>
                      </li>
                  </ul>
              </nav>
          </div>
      </div>
  </aside>
</template>

<script>
  import lang from "../../../../../lang/en.json";
  const base_path = window.location.origin;
  
  export default {
    name: 'Sidebar',
    data: () => ({
      locale: lang,
      root_path: base_path
    }),
    methods: {
      opneSignupDialogue() {
        this.$parent.opneSignupDialogue();
      },
      openLoginDialogue() {
        this.$parent.openLoginDialogue();
      }
    }
  }

  </script>