<template>
    <div class="grid justify-items-center">
        <div
            class=" bg-white shadow-xl rounded-md max-w-lg md:max-w-lg p-6 space-y-6 mt-24"
        >
            <h1 class="text-xl font-bold">
                Co-WIN Vaccination Registration
            </h1>

            <div
                v-show="success"
                class="text-white px-6 py-4 border-0 rounded relative mb-4 bg-green-500"
            >
                <span class="inline-block align-middle mr-8">
                    Registration Success
                </span>
                <button
                    class="absolute bg-transparent text-2xl font-semibold leading-none right-0 top-0 mt-4 mr-6 outline-none focus:outline-none"
                    v-on:click="closeAlert()"
                >
                    <span>×</span>
                </button>
            </div>

            <div class="mb-3 pt-0 relative">
                <label for="select_number" class="text-blueGray-600"
                    >Your Mobile Number(Without +91)</label
                >
                <input
                    required
                    v-model="select_number"
                    type="text"
                    name="select_number"
                    placeholder="Your Mobile Number"
                    class="px-3 py-3 placeholder-blueGray-300 text-blueGray-600 relative bg-wite bg-wite rounded text-sm border border-blueGray-300 outline-none focus:outline-none focus:ring w-full"
                />

                <span
                    v-if="errors && errors.number"
                    class="inline-block align-middle mr-8 mt-1 text-red-600"
                >
                    {{ errors.number[0] }}
                </span>
            </div>

            <div class="mb-3 pt-0">
                <label for="select_state" class="text-blueGray-600"
                    >Select Your State</label
                >
                <select
                    required
                    v-model="select_state"
                    name="select_state"
                    class="px-3 py-3 placeholder-blueGray-300 text-blueGray-600 relative bg-wite bg-wite rounded text-sm border border-blueGray-300 outline-none focus:outline-none focus:ring w-full"
                    id="select_state"
                    @change="getDistrict()"
                >
                    <option value="0" disabled selected>Select State...</option>
                    <option
                        v-bind:value="state.state_id"
                        :key="state.state_id"
                        v-for="state in states"
                        >{{ state.state_name }}</option
                    >
                </select>

                <span
                    v-if="errors && errors.state"
                    class="inline-block align-middle mr-8 mt-1 text-red-600"
                >
                    {{ errors.state[0] }}
                </span>
            </div>

            <div class="mb-3 pt-0">
                <label for="select_district" class="text-blueGray-600"
                    >Select Your District</label
                >
                <select
                    v-model="select_district"
                    name="select_district"
                    required
                    class="px-3 py-3 placeholder-blueGray-300 text-blueGray-600 relative bg-wite bg-wite rounded text-sm border border-blueGray-300 outline-none focus:outline-none focus:ring w-full"
                    id="select_district"
                >
                    <option value="0" disabled>Select District...</option>
                    <option
                        v-for="district in districts"
                        v-bind:value="district.district_id"
                        :key="district.district_id"
                        >{{ district.district_name }}</option
                    >
                </select>

                <span
                    v-if="errors && errors.district"
                    class="inline-block align-middle mr-8 mt-1 text-red-600"
                >
                    {{ errors.district[0] }}
                </span>
            </div>

            <div class="mb-3 pt-0">
                <button
                    class="bg-green-500 hover:bg-green-400 text-white active:bg-pink-600 font-bold uppercase text-sm px-6 py-3 rounded shadow hover:shadow-lg outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150"
                    type="button"
                    @click.prevent="saveDetails()"
                >
                    Submit
                </button>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    name: "pink-alert",
    data() {
        return {
            states: "",
            state: "",
            accessToken: "3sjOr2rmM52GzhpMHjDEE1kpQeRxwFDr4YcBEimi",
            select_state: "",
            id: "",
            select_district: "",
            districts: "",
            select_number: "",
            success: false,
            errors: null
        };
    },
    created: function() {
        this.getStates();
    },
    methods: {
        closeAlert: function() {
            this.success = false;
        },
        getStates: function() {
            axios.defaults.headers.common["Authorization"] =
                "3sjOr2rmM52GzhpMHjDEE1kpQeRxwFDr4YcBEimi";
            axios
                .get("https://cdn-api.co-vin.in/api/v2/admin/location/states", {
                    headers: {
                        Authorization: "Bearer " + this.accessToken
                    }
                })
                .then(
                    function(response) {
                        this.states = response.data.states;
                        // console.log(this.states);
                    }.bind(this)
                );
        },
        getDistrict: function() {
            // axios.defaults.headers.common["Authorization"] =
            //     "3sjOr2rmM52GzhpMHjDEE1kpQeRxwFDr4YcBEimi";
            this.id = this.select_state;
            axios
                .get(
                    "https://cdn-api.co-vin.in/api/v2/admin/location/districts/" +
                        this.id,
                    {
                        headers: {
                            Authorization: "Bearer " + this.accessToken
                        }
                    }
                )
                .then(
                    function(response) {
                        this.districts = response.data.districts;
                        // console.log(this.districts);
                    }.bind(this)
                );
        },
        saveDetails: function() {
            axios
                .post("/saveDetails", {
                    number: this.select_number,
                    state: this.select_state,
                    district: this.select_district
                })
                .then(
                    function(response) {
                        if (response.data) {
                            // console.log(response.data);
                            this.success = true;
                            this.errors = null;
                            this.select_state = "";
                            this.select_number = "";
                            this.select_district = "";
                        } else {
                            // console.log("No Data");
                        }
                    }.bind(this)
                )
                .catch(error => {
                    if (error.response.status == 422) {
                        this.errors = error.response.data.errors;
                        // console.log(this.errors);
                    }
                    // this.errors = error.response.data.errors;
                });
        }
    }
};
</script>
