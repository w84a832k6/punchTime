<template>
  <div class="container py-4">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="box">
          <div class="box-header d-flex justify-content-end">
              <b-button size="sm" variant="success" :to="`/users/create`"><b-icon icon="plus-square"></b-icon> 新增</b-button>
          </div>
          <table class="table table-bordered table-hover my-1">
            <thead class="bg-secondary">
              <tr class="text-light">
                <th>編號</th>
                <th>帳號</th>
                <th>名稱</th>
                <th>角色</th>
                <th>更新時間</th>
                <th>操作</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(value, key) in users" :key="key">
                <td>{{ value.id }}</td>
                <td>{{ value.name }}</td>
                <td>{{ value.alias }}</td>
                <td>
                  <span
                    class="border bg-primary rounded text-light text-truncate p-2"
                    >{{ value.type }}</span
                  >
                </td>
                <td>{{ value.updated_at | dateTimeFormatter() }}</td>
                <td>
                  <b-button
                    variant="outline-primary"
                    size="sm"
                    :to="`/users/${value.id}/edit`"
                  >
                    <b-icon icon="pencil-square"></b-icon>
                  </b-button>
                </td>
              </tr>
            </tbody>
          </table>
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
    users: { type: Array },
  },
};
</script>
