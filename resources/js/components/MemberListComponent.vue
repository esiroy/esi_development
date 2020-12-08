<template>
    <div class="b-table-custom">
        <b-container fluid >
            <!-- User Interface controls -->
            <b-table striped hover :items="items" :fields="fields">
                <template v-slot:cell(skype_zoom)="row">
                    {{ row.item.communication_app_name }}: {{ row.item.communication_app_username }}
                </template>

                <template v-slot:cell(class)="row">                    
                    <a :href="'member/schedulelist/'+row.item.id" alt="Summary List of Schedules" title="Summary List of Schedules"><img src="/images/iClass.jpg"></a>
                </template>   

                <template v-slot:cell(history)="row">
                    <a href="/member/paypmenthistory" alt="Payment History" title="Payment History"><img src="/images/iHistory.jpg"></a>
                </template>
                <template v-slot:cell(report_card)="row">
                    <a href="/member/reportcardlist" alt="List of Report Card" title="List of Report Card"><img src="/images/iReportCard.jpg"></a>
                </template>

                <template v-slot:cell(writing_report)="row">
                    <a href="/member/reportcarddatelist" alt="List of Monthly Report Card" title="List of Monthly Report Card"><img src="/images/iMonthlyRC.jpg"></a>
                </template>

                <template v-slot:cell(actions)="row">
                    <a :href="'member/account/'+row.item.id">Account</a> | 
                    <a :href="'member/edit/'+row.item.id">Edit</a> | 
                    <a :href="'member/delete'+row.item.id">Delete</a>
                </template>

            </b-table>
        </b-container>
    </div>        
</template>

<script>
export default {
    props: {
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
            perPage: 15,
            pageOptions: [5, 10, 15],
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
