<template>
    <div class="b-table-custom">
        <b-container fluid >

            <!-- User Interface controls -->
            <b-table striped hover 
                :items="items"             
                :fields="fields"
                :current-page="currentPage"
                :per-page="perPage"
                stacked="md"
            >
                <template v-slot:cell(skype_zoom)="row">
                    {{ row.item.communication_app_name }}: {{ row.item.communication_app_username }}
                </template>

                <template v-slot:cell(class)="row">                    
                    <a :href="'member/schedulelist/'+row.item.id" alt="Summary List of Schedules" title="Summary List of Schedules"><img src="/images/iClass.jpg"></a>
                </template>   

                <template v-slot:cell(history)="row">
                    <a :href="'member/paymenthistory/'+row.item.id" alt="Payment History" title="Payment History"><img src="/images/iHistory.jpg"></a>
                </template>
                <template v-slot:cell(report_card)="row">
                    <a :href="'member/reportcardlist/'+row.item.id" alt="List of Report Card" title="List of Report Card"><img src="/images/iReportCard.jpg"></a>
                </template>

                <template v-slot:cell(writing_report)="row">
                    <a href="member/reportcarddatelist" alt="List of Monthly Report Card" title="List of Monthly Report Card"><img src="/images/iMonthlyRC.jpg"></a>
                </template>

                <template v-slot:cell(actions)="row">
                    <a :href="'member/account/'+row.item.id" >Account</a> |
                    
                    <a :href="'member/'+row.item.id+'/edit'" >Edit</a> | 
                    <form :action="'member/'+row.item.id" method="POST" onsubmit="return confirm('are you sure you want to delete?');"
                        style="display: inline-block;">
                        <input type="hidden" name="_method" value="delete" />
                        <input type="hidden" name="_token" :value="csrf_token"> 
                        <input type="submit" value="Delete" style="border: none; background: none; color: #c60000">
                    </form>

                </template>
            </b-table>

            <!--Pagination-->
            
            <b-row>
                <b-col sm="4" md="6" class="my-1">
                    <b-pagination
                        v-model="currentPage"
                        :total-rows="totalRows"
                        :per-page="perPage"
                        align="fill"
                        size="sm"
                        class="my-0"
                    ></b-pagination>
                </b-col>
                <b-col sm="5" md="6" class="my-1">
                    <b-form-group
                        label="Per page"
                        label-cols-sm="6"
                        label-cols-md="4"
                        label-cols-lg="3"
                        label-align-sm="right"
                        label-size="sm"
                        label-for="perPageSelect"
                        class="mb-0"
                    >
                        <b-form-select v-model="perPage" id="perPageSelect" size="sm" :options="pageOptions"></b-form-select>
                    </b-form-group>
                </b-col>

            </b-row>
            <!--Pagiation-->

        </b-container>
    </div>        
</template>

<script>
export default {
    props: {
        can_member_access: {
            type:Boolean
        },
        can_member_edit: {
			type: Boolean
        },
        can_member_view: {
            type:Boolean
        },
        can_member_delete: {
			type: Boolean
        },
        csrf_token: {
            type: String
        },
        members: {
            type: Array
        }
    },
    data() {
        return {
            items: this.members,
            //fields
            fields: [
                {
                    key: "id",
                    label: "ID",
                    sortable: true,
                    sortDirection: "asc"
                },
                {
                    key: "full_name",
                    label: "Name",
                    sortable: true,
                    sortDirection: "asc"
                },
                /*
                {
                    key: "attribute",
                    label: "Attribute",
                    sortable: false,
                    sortDirection: "asc"
                },
                {
                    key: "agent",
                    label: "Agent",
                    sortable: true,
                    sortDirection: "asc"
                },*/
                {
                    key: "email",
                    label: "E-Mail",
                    sortable: true,
                    sortDirection: "asc"
                },   

                {
                    key: "skype_zoom",
                    label: "Skype/Zoom",
                    sortable: false                    
                },
                {
                    key: "credits",
                    label: "Credit",
                    sortable: true,
                    sortDirection: "asc"
                },        
                {
                    key: "class",
                    label: "Class",
                    sortable: false,
                    sortDirection: "asc"
                },                           
                {
                    key: "main_tutor_name",
                    label: "Main Tutor",
                    sortable: true,
                    sortDirection: "asc"
                },
                {
                    key: "history",
                    label: "History",
                    sortable: false,
                    sortDirection: "asc"
                },                  
                {
                    key: "report_card",
                    label: "Report Card",
                    sortable: false,
                    sortDirection: "asc"
                },
                {
                    key: "writing_report",
                    label: "Writing Report",
                    sortable: false,
                    sortDirection: "asc"
                },       
                { key: "actions", label: "Actions" }                        
                /*            
                {
                    key: "first_name",
                    label: "First Name",
                    sortable: true,
                    sortDirection: "asc"
                },
                {
                    key: "last_name",
                    label: "Last Name",
                    sortable: true,
                    sortDirection: "asc"
                },
                         
                {
                    key: "age",
                    label: "Person age",
                    sortable: true,
                    class: "text-center"
                },
               
                {
                    key: "isActive",
                    label: "is Active",
                    formatter: (value, key, item) => {
                        return value ? "Yes" : "No";
                    },
                    sortable: true,
                    sortByFormatted: true,
                    filterByFormatted: true
                },
                 */ 
               
            ],
            totalRows: 1,
            currentPage: 1,
            perPage: 10,
            pageOptions: [10, 15, 20, 50, 100, 500],
            sortBy: "",
            sortDesc: false,
            sortDirection: "asc",
            filter: null,
            filterOn: [],
            infoModal: {
                id: "info-modal",
                title: "",
                content: ""
            }
        };
    },
    computed: {
        sortOptions() {
            // Create an options list from our fields
            return this.fields
                .filter(f => f.sortable)
                .map(f => {
                    return { text: f.label, value: f.key };
                });
        }
    },
    mounted() {
        // Set the initial number of items
        this.totalRows = this.items.length;
        console.log(this.items)
    },
    methods: {
        info(item, index, button) {
            this.infoModal.title = `Row index: ${index}`;
            this.infoModal.content = JSON.stringify(item, null, 2);
            this.$root.$emit("bv::show::modal", this.infoModal.id, button);
        },
        resetInfoModal() {
            this.infoModal.title = "";
            this.infoModal.content = "";
        },
        onFiltered(filteredItems) {
            // Trigger pagination to update the number of buttons/pages due to filtering
            this.totalRows = filteredItems.length;
            this.currentPage = 1;
        }
    }
};
</script>

<style>
.b-table-custom .table thead th {
    font-size: 11px;
    text-align: center
}
</style>
