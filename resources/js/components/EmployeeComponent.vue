<template>
  <div class="container">
    <h3>Zaměstnanci</h3>

    <a v-on:click="fetchNewData" class="btn btn-primary my-2 me-1">Načíst data</a>
    <a v-on:click="fetchSalaries" class="btn btn-primary my-2">Načíst platy</a>

    <div class="row my-2">
      <div class="col-4">
        <input type="text" v-model="search" class="form-control" placeholder="Vyhledat" />
      </div>
      <!-- <div class="col-1">
        <button v-on:click="filterData()" class="btn btn-success" title="Search">
          Vyhledat
        </button>
      </div> -->
      <div class="col-2">
        <button v-on:click="this.resetFilter()" class="btn btn-warning" title="Search">
          Zrušit filtr
        </button>
      </div>
    </div>
    <div class="row my-2">
        <span v-if="!cEmployees.length">Zadanému filtru neodpovídá žádný záznam.</span>
    </div>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th scope="col" @click="sortList('id')" class="col-1">ID: <i v-if="sortedbyASC && (sortBy=='id')">▲</i><i v-if="!sortedbyASC && (sortBy=='id')">▼</i></th>
          <th scope="col" @click="sortList('name')" class="col-2">Jméno: <i v-if="sortedbyASC && (sortBy=='name')">▲</i><i v-if="!sortedbyASC && (sortBy=='name')">▼</i></th>
          <th scope="col" @click="sortList('surname')" class="col-2">Příjmení: <i v-if="sortedbyASC && (sortBy=='surname')">▲</i><i v-if="!sortedbyASC && (sortBy=='surname')">▼</i></th>
          <th scope="col" @click="sortList('date')" class="col-2">Datum: <i v-if="sortedbyASC && (sortBy=='date')">▲</i><i v-if="!sortedbyASC && (sortBy=='date')">▼</i></th>
          <th scope="col" @click="sortList('salary')" class="col-1">Plat: <i v-if="sortedbyASC && (sortBy=='salary')">▲</i><i v-if="!sortedbyASC && (sortBy=='salary')">▼</i></th>
          <th scope="col" class="col-2">Akce:</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(employee, index) in cEmployees" :key="employee.id">
          <td>
            <input type="text" class="w-100 border-0" v-model="cEmployees[index].id" />
          </td>
          <td>
            <input type="text" class="w-100 border-0" v-model="cEmployees[index].name" />
          </td>
          <td>
            <input
              type="text"
              class="w-100 border-0"
              v-model="cEmployees[index].surname"
            />
          </td>
          <td>
            <input type="text" class="w-100 border-0" v-model="cEmployees[index].date" />
          </td>
          <td>
            <input
              type="text"
              class="w-100 border-0"
              v-model="cEmployees[index].salary"
            />
          </td>
          <td>
            <button
              v-on:click="deleteEmployee(index)"
              class="btn btn-danger btn-sm me-1"
              title="Delete"
            >
              Vymazat
            </button>
            <button
              v-on:click="editEmployee(index)"
              class="btn btn-secondary btn-sm"
              title="Edit"
            >
              Editovat
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  data() {
    return {
      employees: [],
      search: null,
      sortedbyASC: true,
      sortBy: "",
    };
  },

  created() {
    this.fetchAllEmployee(),
    this.sortList('id'),
    this.sortedbyASC = true
  },

  methods: {
    fetchAllEmployee() {
      let self = this;
      axios.get("/api/employees/all").then((response) => {
        self.employees = response.data;
      });
    },

    fetchNewData() {
      let self = this;
      axios.get("/api/employees/reload").then((response) => {
        self.employees = response.data;
      });
    },

    fetchSalaries() {
      let self = this;
      axios.get("/api/employees/salaries").then((response) => {
        self.employees = response.data;
      });
    },

    deleteEmployee(index) {
      let employee = this.employees[index];
      axios.post("/api/employees/destroy/" + employee.id).then((response) => {
        this.employees = response.data;
      });
    },

    editEmployee(index) {
      let employee = this.employees[index];
      let self = this;
      let body = {
        id: employee.id,
        name: employee.name,
        surname: employee.surname,
        date: employee.date,
        salary: employee.salary,
      };
      axios.post("/api/employees/update/" + employee.id, body).then((response) => {
        this.employees = response.data;
      });
    },

    sortList(sortBy) {
      if (this.sortedbyASC) {
        this.employees.sort((x, y) => (x[sortBy] > y[sortBy] ? -1 : 1));
        this.sortedbyASC = false;
      } else {
        this.employees.sort((x, y) => (x[sortBy] < y[sortBy] ? -1 : 1));
        this.sortedbyASC = true;
      }
      this.sortBy = sortBy
    },

    resetFilter() {
      return (this.search = "");
    },

    /*  filterData() {
      let search = this.search;
      this.employees = this.employees.filter((employee) => {
        return search === null
          ? true
          : employee.id.toString().includes(search.toString()) ||
              employee.name.toString().includes(search.toString()) ||
              employee.surname.toString().includes(search.toString()) ||
              employee.date.toString().includes(search.toString()) ||
              employee.salary?.toString().includes(search.toString());
      });
    }, */
  },
  computed: {
    cEmployees: function () {
      let search = this.search;
      return this.employees.filter((employee) => {
        return search === null
          ? true
          : employee.id.toString().includes(search.toString()) ||
              employee.name.toString().includes(search.toString()) ||
              employee.surname.toString().includes(search.toString()) ||
              employee.date.toString().includes(search.toString()) ||
              employee.salary?.toString().includes(search.toString());
      });
    },
  },
};
</script>

<style></style>
