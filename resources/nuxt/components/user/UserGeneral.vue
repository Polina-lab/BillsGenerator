<template>
  <div>
    <div class="row">
      <div class="col-md-3 col-12 user__block">
        <div class="user-photo" :class="{'active': user.is_enabled}">
          <input ref="uploadPhoto"
                 type="file"
                 hidden
                 @change="uploadAvatar"
          >
          <img :src="avatar" :alt="user.username">
        </div>
        <div class="user__active">
          <input id="userActive"
                 type="checkbox"
                 class="mr-2"
                 v-model="user.is_enabled"
                 :value="user.is_enabled"
                 @change="mutationUser">
          <label for="userActive">{{user.is_enabled ? 'Active' : 'Disabled'}}</label>
        </div>
        <i class="fas fa-upload text-success"
           title="Upload photo"
           @click.prevent="$refs.uploadPhoto.click"></i>
        <div v-if="$route.path === '/admin/users/create'"> 
          <a href="#" class="small"
             v-if="$route.path !== '/admin/users/create'"
             @click="change_pass = !change_pass">{{ $t('User.change_password') }}</a>
          <div v-if="change_pass || $route.path === '/admin/users/create'">
            <label for="password">{{ $t('User.password') }}</label>
            <input type="password"
                   class="form-control"
                   :class="{ 'is-invalid': !password }"
                   id="password"
                   v-model="password"
                   @change="mutationUser"
                   autocomplete="new-password">
            <small class="text-danger"
                   v-if="password.length < 5">password must be at least 5 characters</small>
            <label for="password_new">{{$t('User.confirm_password')}}</label>
            <input type="password"
                   id="password_new"
                   class="form-control"
                   :class="{ 'is-invalid': !password2 }"
                   v-model="password2"
                   autocomplete="new-password">
            <small class="text-danger"
                   v-if="password !== password2">Passwords must match</small>
          </div>
        </div>
      </div>
      <div class="col-md-9 col-12">
        <label for="roles">{{$t('User.roles')}}</label>
        <select id="roles"
                class="form-control form-control-sm"
                v-model="user.role_id"
                @change="mutationUser; changeRole(user.role_id)">
          <option :value="item.id"
                  v-for="(item, idx) in $store.state.permissions.roles"
                  :key="idx">
            {{item.name}}
          </option>
        </select>
        <MultiselectCols :items="permissions"
                         :selectedItems="user.permissions"
                         moduleName="users"
                         objName="userDetail"
                         keyName="permissions"
                         nameField="description"
                         :key="multiselectKey"/>
      </div>
    </div>
    <div class="row">
      <div class="col-md-4 col-12">
        <label for="firstname">{{ $t('User.firstname') }}<sup class="text-danger">*</sup></label>
        <input id="firstname"
               type="text"
               class="form-control"
               v-model="user.firstname"
               @change="mutationUser"
               autocomplete="given-name">
        <label for="lastname">{{$t('User.lastname')}}<sup class="text-danger">*</sup></label>
        <input id="lastname"
               type="text"
               class="form-control"
               v-model="user.lastname"
               @change="mutationUser"
               autocomplete="family-name">
        <!--        <label for="lang">{{$t('User.interface_lang')}} <sup class="text-danger">*</sup></label>-->
        <!--        &lt;!&ndash; Не меняется в состоянии (пускай приходит id)&ndash;&gt;-->
        <!--        <select id="lang"-->
        <!--                class="form-control"-->
        <!--                @change="mutationUser">-->
        <!--          <option v-for="(lang, idx) in $store.state.lang.langList" :value="lang.id">-->
        <!--            {{lang.name}}-->
        <!--          </option>-->
        <!--        </select>-->

        <!-- <label>{{$t('User.spoken_lang')}} </label> -->
        <!--DropdownInput class="status-multiselect"
                       :name="'Langs ('+user.lang_spoken.length+')'"
                       btnName="langSpoken">
          <div class="dropdown__items__item"
               v-for="(item, idx) in $store.state.lang.langList" :key="idx">
            <input type="checkbox"
                   class="form-check-input"
                   :checked="user.lang_spoken.includes(item.sys_name)"
                   :id="'langSpoken'+idx"
                   @change="changeLangs(item.id)">
            <label :for="'langSpoken'+idx">{{item.name}}</label>
          </div>
        </DropdownInput-->

        <!--label for="office">{{ $t('User.office')}}</label>
        <select name="office"
                id="office"
                class="form-control"
                v-model="user.office_id"
                @change="mutationUser">
          <option :value="office.id"
                  v-for="(office, idx) in $store.state.users.offices">{{office.name}}
          </option>
        </select-->
      </div>
      <div class="col-md-4 col-12">
        <label for="mobile">{{ $t("User.mobile") }}<sup class="text-danger">*</sup></label>
        <input id="mobile"
               type="text"
               class="form-control"
               v-mask="'+372 ##-##-####'"
               v-model="user.contact_tel_mob"
               @change="mutationUser"
               autocomplete="tel">
        <label for="landline">{{$t('User.landline')}}</label>
        <input id="landline"
               type="text"
               class="form-control"
               v-model="user.contact_tel_land"
               @change="mutationUser"
               autocomplete="tel">
        <label for="skype">{{ $t('User.skype')}}</label>
        <input id="skype"
               type="text"
               class="form-control"
               v-model="user.contact_skype"
               @change="mutationUser">
        <label for="email">{{ $t('User.email')}}<sup class="text-danger">*</sup></label>
        <input id="email"
               type="text"
               class="form-control"
               :class="{ 'is-invalid': userList.find((single_user) => single_user.email == user.email) }"
               v-model="user.email"
               @input="mutationUser"
               autocomplete="email">
      </div>
      <div class="col-md-4 col-12">
        <label for="address">{{ $t('User.post_address')}}</label>
        <input id="address"
               type="text"
               class="form-control"
               v-model="user.contact_address"
               @change="mutationUser">
        <label for="Isikukood">{{ $t('User.Isikukood')}}</label>
        <input id="Isikukood"
               type="text"
               class="form-control"
               v-model="user.personal_id"
               @change="mutationUser">
        <label for="birth">{{ $t('User.b_date')}}</label>
        <input id="birth"
               type="date"
               class="form-control"
               v-model="user.birth_date"
               @change="mutationUser"
               autocomplete="bday">
      </div>
    </div>
  </div>
</template>

<script>
  import DropdownInput from "@/components/elements/input/DropdownInput";
  import MultiselectCols from "@/components/elements/MultiselectCols";
  import mutationUser from "@/mixins/mutationUser";

  export default {
    name: "UserGeneral",
    mixins: [ mutationUser ],
    components: {MultiselectCols, DropdownInput},
    data() {
      return {
        serverUrl: process.env.serverUrl,
        change_pass: false,
        password: '',
        password2: '',
        multiselectKey: 0,
        isConfirmationModalVisible: false,
        actionOnUser: null,
        defaultUserRole: 3
      }
    },
    async fetch() {
      await this.$store.dispatch('permissions/fetchPermissions');
      await this.$store.dispatch('permissions/fetchRoles');
      await this.$store.dispatch('lang/fetchLanguages');
      await this.$store.dispatch('permissions/fetchRolesWithPermissions');
      await this.$store.dispatch('users/fetchUsersList');
     },

    beforeMount() {
      this.user.role_id = this.defaultUserRole;
      this.mutationUser();
      this.changeRole(this.user.role_id);
    },
    
    watch: {
      password2() {
        if (this.password === this.password2) {
          this.user.password = this.password;
          this.user.password2 = this.password2;
          this.mutationUser()
        }
      }
    },
    computed: {
      user() {
        return this.$store.getters['users/getUserDetail']
      },
      userList() {
        return this.$store.state.users.users;
      },
      avatar() {
        if (this.user.image && this.user.image.image_path) {
          return this.serverUrl+'/'+this.user.image.image_path
        } else {
          return this.serverUrl+'/img/user/profile.png'
        }
      },
      permissions() {
        return this.$store.getters['permissions/getPermissions'];
      },
    },
    methods: {
      uploadAvatar(e) {
        const avatar = e.target.files[0]
        let reader = new FileReader();
        reader.onload = (e) => {
          this.$store.commit('users/SET_OBJ', {
            name: 'userDetail',
            key: 'image',
            value: {data: reader.result, name: avatar.name}
          })
          this.avatar = reader.result
        };
        reader.readAsDataURL(avatar);
      },
      /*
      changeLangs(lang) {

        if (this.user.lang_spoken.includes(lang)) {
          this.user.lang_spoken = this.user.lang_spoken.filter(l => l !== lang)
        } else {
          this.user.lang_spoken.push(lang)
        }
        this.mutationUser()
      }

       */
      changeRole(selectedRoleID) {
        let rolePermissions = this.$store.state.permissions.permissionsTable[selectedRoleID-1].permissions;
        this.user.permissions = [];
        rolePermissions.forEach(permission => {
            this.user.permissions.push(permission.id);
        });
        this.multiselectKey+=1;
      },
      /**
       * Checks if required fields are filled
       * @returns {boolean} returns `true` if required fields are filled
       */
      isFieldsFilled(){
        let fields_to_check = ['firstname', 'lastname', 'contact_tel_mob', 'email', 'password', 'password2'];
        if (this.userList.find((single_user) => single_user.email == this.user.email).email) {
          return false;
        }
        return fields_to_check.every((field) => this.user[field] !== null);
      }
    }
  }
</script>

<style scoped >

  .user__block {
    position: relative;
  }

  .user-photo {
    width: 200px;
    height: 200px;
    border-radius: 50%;
    overflow: hidden;
    background: #ffffff;
    margin-top: 20px;
  }

  .user-photo img {
    width: 100%;
    height: auto;
    opacity: 0.2;
  }

  .user-photo.active img {
    opacity: 1;
  }

  #roles {
    max-width: 200px;
  }

  .user__active {
    display: flex;
    position: absolute;
    top: 5px;
    left: 15px;
  }

  .user__block label {
    margin-top: -1px;
  }

  .user__block i {
    position: absolute;
    top: 190px;
    left: 15px;
    cursor: pointer;
    font-size: 20px;
  }
</style>
