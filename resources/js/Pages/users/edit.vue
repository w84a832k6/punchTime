<template>
  <div class="container py-4">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="box">
          <b-form @submit.stop.prevent="onSubmit">
            <b-form-group id="input-group-1" label="帳號:" label-for="input-1">
              <b-form-input
                id="input-1"
                name="name"
                v-model="user.name"
                placeholder="請輸入帳號"
                v-validate="{ required: true, min: 4, max: 12 }"
                :state="validateState('name')"
                aria-describedby="input-1-live-feedback"
              ></b-form-input>
              <b-form-invalid-feedback id="input-1-live-feedback">{{
                veeErrors.first("name")
              }}</b-form-invalid-feedback>
            </b-form-group>

            <b-form-group id="input-group-2" label="暱稱:" label-for="input-2">
              <b-form-input
                id="input-2"
                name="alias"
                v-model="user.alias"
                placeholder="請輸入暱稱"
                v-validate="{ required: true }"
                :state="validateState('alias')"
                aria-describedby="input-2-live-feedback"
              ></b-form-input>
              <b-form-invalid-feedback id="input-2-live-feedback">{{
                veeErrors.first("alias")
              }}</b-form-invalid-feedback>
            </b-form-group>

            <b-form-group id="input-group-3" label="密碼:" label-for="input-3">
              <b-form-input
                type="password"
                id="password"
                name="password"
                aria-describedby="password-help-block"
                v-model="password"
                :state="validateState('password')"
                v-validate="{
                  regex: /^[A-Za-z0-9\u4e00_-]+$/,
                  min: 6,
                  max: 15,
                }"
                ref="password"
              ></b-form-input>
              <b-form-text id="password-help-block">
                密碼請輸入6~15位字元
              </b-form-text>
              <b-form-invalid-feedback id="password-live-feedback">{{
                veeErrors.first("password")
              }}</b-form-invalid-feedback>
            </b-form-group>

            <b-form-group
              id="input-group-4"
              label="驗證密碼:"
              label-for="input-4"
            >
              <b-form-input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                v-model="password_confirmation"
                :state="validateState('password_confirmation')"
                v-validate="{ confirmed: password }"
                data-vv-as="password"
              ></b-form-input>
              <b-form-invalid-feedback
                id="password_confirmation-live-feedback"
                >{{
                  veeErrors.first("password_confirmation")
                }}</b-form-invalid-feedback
              >
            </b-form-group>

            <b-form-group label="啟用與否:" v-slot="{ ariaDescribedby }">
              <b-form-radio-group
                id="radio-group-1"
                v-model="user.enable"
                :options="options"
                :aria-describedby="ariaDescribedby"
                name="enable-options"
              ></b-form-radio-group>
            </b-form-group>

            <b-form-group v-if="currentUser.type === 'admin'" label="權限:" v-slot="{ ariaDescribedby }">
              <b-form-radio-group
                id="radio-group-2"
                v-model="user.type"
                :options="typeOptions"
                :aria-describedby="ariaDescribedby"
                name="type-options"
              ></b-form-radio-group>
            </b-form-group>

            <b-button type="submit" variant="primary">送出</b-button>
          </b-form>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import filter from "@/mixins/filter.js";

export default {
  metaInfo: {
    title: "帳號管理",
  },
  mixins: [filter],
  props: {
    user: {
      type: Object,
      default: function () {
        return {
          id: 0,
          name: "",
          alias: "",
          enable: "y",
          type: "user",
        };
      },
    },
    currentUser: {
      type: Object,
    },
    isCreate: { type: Boolean, default: false },
  },
  methods: {
    onSubmit() {
      let userData = this.user;
      if (this.password !== "" && this.password_confirmation !== "") {
        userData.password = this.password;
        userData.password_confirmation = this.password_confirmation;
      }

      if (this.isCreate) {
        axios
          .post("/users/store", userData)
          .then(function (response) {
            Swal.fire(response.data).then(() => {
              window.location = "/users";
            });
          })
          .catch((error) => {
            if (error.response) {
              Swal.fire(error.response.data.message);
            } else {
              Swal.fire(error);
            }
          });
      } else {
        axios
          .patch(`/users/${this.user.id}/update`, userData)
          .then(function (response) {
            Swal.fire(response.data).then(() => {
              window.location = "/users";
            });
          })
          .catch((error) => {
            if (error.response) {
              Swal.fire(error.response.data.message);
            } else {
              Swal.fire(error);
            }
          });
      }
    },
    validateState(ref) {
      if (
        this.veeFields[ref] &&
        (this.veeFields[ref].dirty || this.veeFields[ref].validated)
      ) {
        return !this.veeErrors.has(ref);
      }
      return null;
    },
  },
  data: () => ({
    options: [
      { text: "啟用", value: "y" },
      { text: "停用", value: "n" },
    ],
    typeOptions: [
      { text: "管理員", value: "admin" },
      { text: "使用者", value: "user" },
    ],
    password: "",
    password_confirmation: "",
  }),
};
</script>
