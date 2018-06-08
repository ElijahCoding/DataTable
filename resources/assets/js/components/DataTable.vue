<template>
  <div class="panel panel-default">
      <div class="panel-heading">{{ response.table }}</div>

      <div class="panel-body">
        <div class="table-responsive">
          <table class="table table-stripe">
            <thead>
              <tr>
                <th v-for="column in response.displayable">
                  <span  @click="sortBy(column)">{{ column }}</span>

                  <span
                  v-if="sort.key === column"
                  class="arrow"
                  :class="{ 'arrow--asc': sort.order === 'asc', 'arrow--desc': sort.order === 'desc' }"
                  >
                  </span>
                </th>

                <th>&nbsp;</th>
              </tr>
            </thead>

            <tbody>
              <tr v-for="record in filteredRecords">
                <td v-for="columnValue, column in record">{{ columnValue }}</td>
                <td>Edit</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
  </div>
</template>

<script>
    export default {
        props: [
          'endpoint'
        ],

        data () {
          return {
            response: {
              table: '',
              displayable: [],
              records: []
            },

            sort: {
              key: 'id',
              order: 'asc'
            }
          }
        },

        mounted() {
          this.getRecords()
        },

        computed: {
          filteredRecords () {
            let data = this.response.records

            if (this.sort.key) {

              data = _.orderBy(data, (i) => {
                let value = i[this.sort.key]

                if (!isNaN(parseFloat(value))) {
                  return parseFloat(value)
                }

                return String(i[this.sort.key]).toLowerCase()
              }, this.sort.order)
            }

            return data
          }
        },

        methods: {
          getRecords () {
            return axios.get(`${this.endpoint}`).then((response) => {
              this.response = response.data.data
            })
          },

          sortBy (column) {
            this.sort.key = column
            this.sort.order = this.sort.order === 'asc' ? 'desc' : 'asc'
          }
        }
    }
</script>

<style lang="scss">
    .sortable {
        cursor: pointer;
    }

    .match {
        color: #4fc08d;
    }

    .well {
        border-radius: 0;
    }

    .arrow {
        display: inline-block;
        vertical-align: middle;
        width: 0;
        height: 0;
        margin-left: 5px;
        opacity: 0.66;

        &--asc {
            border-left: 4px solid transparent;
            border-right: 4px solid transparent;
            border-bottom: 4px solid #222;
        }

        &--desc {
            border-left: 4px solid transparent;
            border-right: 4px solid transparent;
            border-top: 4px solid #222;
        }
    }
</style>
