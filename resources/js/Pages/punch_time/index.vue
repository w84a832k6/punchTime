<template>
  <div class="container py-4">
    <div class="row justify-content-center">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header">
            <div class="row d-flex justify-content-end">
              <b-button class="mr-1" :href="'/punch_time/export?pre_month=0'"><b-icon icon="download"></b-icon> 下載本月記錄</b-button>
              <b-button :href="'/punch_time/export?pre_month=1'"><b-icon icon="download"></b-icon> 下載上月記錄</b-button>
            </div>
            <div class="row d-flex justify-content-around my-2">
              <div class="col">
                <div class="text-center">
                  <b-button
                    class="col-6 align-self-start"
                    @click="punchOn"
                    :disabled="showOffReason"
                    >上班</b-button
                  >
                </div>
                <div
                  class="form-group has-error align-self-center"
                  v-show="showOnReason"
                >
                  <div class="message text-center">
                    請填寫未打卡原因，並補打上班卡
                  </div>
                  <div class="input-group">
                    <input
                      type="text"
                      v-model="onReason"
                      class="input-group-addon"
                      style="width: 100%"
                    />
                  </div>
                </div>
              </div>
              <div class="col">
                <div class="text-center">
                  <b-button
                    class="col-6"
                    @click="punchOff"
                    :disabled="showOnReason"
                    >下班</b-button
                  >
                </div>
                <div
                  class="form-group has-error align-self-center"
                  v-show="showOffReason"
                >
                  <div class="message text-center">
                    請填寫未打卡原因，並補打下班卡
                  </div>
                  <div class="input-group">
                    <input
                      type="text"
                      v-model="offReason"
                      class="input-group-addon"
                      style="width: 100%"
                    />
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="box-body">
            <div class="accordion my-2" role="tablist">
              <b-card no-body class="mb-1">
                <b-card-header header-tag="header" class="p-1" role="tab">
                  <b-button block v-b-toggle.accordion-1 variant="light"
                    >本月打卡記錄</b-button
                  >
                </b-card-header>
                <b-collapse
                  id="accordion-1"
                  visible
                  accordion="my-accordion"
                  role="tabpanel"
                >
                  <b-card-body>
                    <div class="row justify-content-center">
                      <h3>目前上班時數：{{ nowTime }}</h3>
                    </div>
                    <table class="table table-bordered table-hover my-1">
                      <thead class="bg-secondary table-striped">
                        <tr class="text-light">
                          <th>上班打卡時間</th>
                          <th>打卡IP</th>
                          <th>原由</th>
                          <th class="table-border-left">下班打卡時間</th>
                          <th>打卡IP</th>
                          <th>原由</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(value, key) in punchTimeRecord" :key="key">
                          <td>{{ value.on_time }}</td>
                          <td>{{ value.on_ip }}</td>
                          <td>{{ value.on_remark }}</td>
                          <td class="table-border-left">
                            {{ value.off_time }}
                          </td>
                          <td>{{ value.off_ip }}</td>
                          <td>{{ value.off_remark }}</td>
                        </tr>
                      </tbody>
                    </table>
                  </b-card-body>
                </b-collapse>
              </b-card>
              <b-card no-body class="mb-1">
                <b-card-header header-tag="header" class="p-1" role="tab">
                  <b-button block v-b-toggle.accordion-2 variant="light"
                    >上月打卡記錄</b-button
                  >
                </b-card-header>
                <b-collapse
                  id="accordion-2"
                  accordion="my-accordion"
                  role="tabpanel"
                >
                  <b-card-body>
                    <table class="table table-bordered table-hover my-1">
                      <thead class="bg-secondary table-striped">
                        <tr class="text-light">
                          <th>上班打卡時間</th>
                          <th>打卡IP</th>
                          <th>原由</th>
                          <th class="table-border-left">下班打卡時間</th>
                          <th>打卡IP</th>
                          <th>原由</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr
                          v-for="(value, key) in lastMonthPunchTimeRecord"
                          :key="key"
                        >
                          <td>{{ value.on_time }}</td>
                          <td>{{ value.on_ip }}</td>
                          <td>{{ value.on_remark }}</td>
                          <td class="table-border-left">
                            {{ value.off_time }}
                          </td>
                          <td>{{ value.off_ip }}</td>
                          <td>{{ value.off_remark }}</td>
                        </tr>
                      </tbody>
                    </table>
                  </b-card-body>
                </b-collapse>
              </b-card>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  metaInfo: {
    title: "打卡系統",
  },
  props: {
    punchTime: {
      type: Array,
      default: function () {
        return [];
      },
    },
    lastMonthPunchTime: {
      type: Array,
      default: function () {
        return [];
      },
    },
  },
  data: function () {
    return {
      punchTimeRecord: this.punchTime,
      lastMonthPunchTimeRecord: this.lastMonthPunchTime,
      showOnReason: false,
      showOffReason: false,
      onReason: "",
      offReason: "",
    };
  },
  methods: {
    punchOn() {
      if (this.showOnReason && this.onReason === "") {
        Swal.fire("請填寫原由");
      } else {
        let reason = this.onReason !== "" ? this.onReason : "";
        axios.post("/punch_time/on", { on_remark: reason }).then((response) => {
          if (response.data.code === 1) {
            Swal.fire(response.data.message).then(() => {
              axios.get("/").then((response) => {
                this.punchTimeRecord = response.data.punchTime;
                this.lastMonthPunchTimeRecord =
                  response.data.lastMonthPunchTime;
                this.showOnReason = false;
                this.showOffReason = false;
              });
            });
          } else {
            this.showOffReason = true;
            Swal.fire(response.data.message);
          }
        });
      }
    },
    punchOff() {
      if (this.showOffReason && this.offReason === "") {
        Swal.fire("請填寫原由");
      } else {
        let reason = this.offReason !== "" ? this.offReason : "";
        axios
          .patch("/punch_time/off", { off_remark: reason })
          .then((response) => {
            if (response.data.code === 1) {
              Swal.fire(response.data.message).then(() => {
                axios.get("/").then((response) => {
                  this.punchTimeRecord = response.data.punchTime;
                  this.lastMonthPunchTimeRecord =
                    response.data.lastMonthPunchTime;
                  this.showOnReason = false;
                  this.showOffReason = false;
                });
              });
            } else {
              this.showOnReason = true;
              Swal.fire(response.data.message);
            }
          });
      }
    },
    downloadExcel(pre_month)
    {
      return axios.get(`/punch_time/export?pre_month=${pre_month}`);
    }
  },
  computed: {
    nowTime() {
      if (this.punchTimeRecord.length > 0 && this.punchTimeRecord[0].off_time === null) {
        let now = new Date();
        let on_time = new Date(this.punchTimeRecord[0].on_time);

        let days = parseInt(Math.abs(now - on_time) / 1000 / 60 / 60 / 24);
        let hours =
          parseInt(Math.abs(now - on_time) / 1000 / 60 / 60) - days * 24;
        let minutes =
          parseInt(Math.abs(now - on_time) / 1000 / 60) -
          days * 24 * 60 -
          hours * 60;
        let seconds =
          parseInt(Math.abs(now - on_time) / 1000) -
          days * 24 * 60 * 60 -
          hours * 60 * 60 -
          minutes * 60;

        return days + "天" + hours + "小時" + minutes + "分鐘" + seconds + "秒";
      } else {
        return "未打卡";
      }
    },
  },
};
</script>