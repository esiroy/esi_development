<template>
    <div id="MemberMiniTestComponent" class="MemberMiniTestComponent">

        <div class="card esi-card">
            <div class="card-header bg-darkblue text-white font-weight-bold small">
                Mini-Test Results
            </div>

            <div class="card-body p-0 m-0 b-0">

                <table class="table esi-table table-bordered table-striped">
				
                    <thead>
						
						<tr v-if="items.length > 0">							
							<td>#</td>
                            <td>Category</td>
							<td>Type</td>
							<td>Time Started</td>
							<td>Time Ended</td>
                            <td>Score</td>
                            <td>Actions</td>
                        </tr>
                    </thead>
                    <tbody>
						
						<tr v-if="items.length == 0">
							<td>No Result Found</td>
						</tr>

                        <tr v-for="(item, key) in items" :key="key">
							<td class="p-1">{{ item.id }}</td>
							<td class="p-1">
								<a href="" @click.prevent="showTestDetailModal(item)" >{{ item.name }}</a>
							</td>
							<td class="p-1" >{{ item.type }}</td>
                            <td class="p-1">{{ item.time_started }}</td>
							<td class="p-1">{{ item.time_ended }}</td>
							
                            <td class="p-1">{{ item.correct_answers }} / {{ item.total_questions }}</td>
                            <td class="p-1">
								<a href="" @click.prevent="showTestDetailModal(item)">View</a>
							
								<span v-if="usertype == 'ADMINISTRATOR'" > | <a href="" @click.prevent="confirmDelete(item, key)">Delete</a></span>
								
							</td>
                        </tr>
                    </tbody>

                </table>



            </div>
        </div>

		<b-pagination
			class="pt-4"
			v-model="currentPage" 
			@input="changePage(currentPage)"
			:total-rows="rows"
			:per-page="perPage"
			aria-controls="tutor-MiniTests"
		></b-pagination>




		<!--Note Modal -->
		<b-modal id="miniTestDetailModal" title="Mini-Test Result" size="xl" ok-only ok-title="Close">
			<div class="row">
				<div class="col-12" v-if="item !== null">

					<h5 class="text-primary font-weight-bold">Category: {{ item.name }}</h5>

					<h6 class="font-weight-bold">Member Score: 
						<span class="text-primary">{{ item.correct_answers }} / {{ item.total_questions }}</span>
					</h6>

					<h6 class="text-dark font-weight-bold">Type:
						<span class="text-primary">{{ item.type }}</span>
					</h6>

					<div class="mt-4">
						<div>
							<span class="font-weight-bold">Time Started: </span>
							<span class="text-primary">{{ item.time_started }}</span>
						</div>
						<div>
							<span class="font-weight-bold">Time Ended: </span>
							<span class="text-primary">{{ item.time_ended }}</span>
						</div>
					</div>

					<div class="mt-4">
						<table>

							<tr v-for="(row, index) in JSON.parse(item.member_answers)" :key="index">
								<td class="pb-4">

									<!--
									<span class="font-weight-bold">
										{{ index + 1 }} {{ "." }} {{ row.question}}
									</span>
									-->

									<span class="font-weight-bold float-left pr-1 small">{{ (index + 1)  +"." }} </span>
									<span class="question font-weight-bold d-inline-block" v-html="formatter(row.question)"></span>


									<div class="choices mt-2 ml-3">
										<span class="font-weight-bold"> Choices: </span>
										<span class="pl-4"  v-for="(choice, choiceIndex) in row.choices" :key="choiceIndex">
											{{ choiceIndex+1 }}{{ ".)"}} {{ choice.choice }} &nbsp;
										</span>

										<div class="font-weight-bold">
											Correct Answer: 
											<span class="text-orange" v-if="Array.isArray(row.correct_answer)">
												{{ row.correct_answer.join(", ")}}
											</span>
											<span v-else class="text-orange">
												{{ row.correct_answer }}
											</span>

										</div>
										

										<div v-if="row.your_answer === null" class="pt-2">
											<span class="font-weight-bold">Submitted Answer:  </span>
											<i class="fa fa-question text-secondary" aria-hidden="true"></i>  {{ "No Answer" }}
										</div>

										<div v-else class="pt-2">
											<div class="font-weight-bold">
												Submitted Answer: 

												<span class="text-primary" v-if="Array.isArray(row.your_answer)">
													{{ row.your_answer.join(", ")}}
												</span>
												<span v-else class="text-primary">
													{{ row.your_answer }}
												</span>

											</div>                                       
											<div v-if="row.is_correct == true" class="text-success font-weight-bold"> <i class="fa fa-check" aria-hidden="true"></i> Correct </div>
											<div v-else-if="row.is_correct == false" class="text-danger font-weight-bold"> <i class="fa fa-times" aria-hidden="true"></i> Incorrect </div>
										</div>
										

									</div>
									


								</td>

								
							</tr>
						</table>

					</div>



					<h6 class="font-weight-bold">Member Score: 
						<span class="text-primary">{{ item.correct_answers }} / {{ item.total_questions }}</span>
					</h6>
			

				</div>
			</div>
		</b-modal>
		<!--[END NOTE MODAL] -->


    </div>
</template>

<script>

import {StringHelpers} from "../../helpers/StringHelpers.js";


export default {
    name: "member-minitest-viewer-component",
    components: {},
    props: {
		usertype: String,
        tutorinfo: Object,
        memberinfo: Object,
        api_token: String,
        csrf_token: String
    },
    data() {
        return {
		
			item: null,
            items: [],

            //PAGE
            currentPage: 1,
            rows: 100,
            perPage: 1
        };
    },
    mounted: function() 
	{
       this.getMiniTests(1) 
    },
    methods: {
		formatter(text) {
			return StringHelpers.capitalizeFirstLetter(text)
		},
		confirmDelete(item, key) {
		
			this.$bvModal.msgBoxConfirm('Please confirm that you want to delete this mini-test result.', {
				title: 'Please Confirm',
				size: 'med',
				buttonSize: 'sm',

				okVariant: 'danger',
				okTitle: 'YES, DELETE THIS RESULT PERMANENTLY',

				cancelVariant: 'primary',
				cancelTitle: 'NO',
				footerClass: 'p-2',
				hideHeaderClose: false,
				centered: true
			})
			.then(value => {

				if (value == true ) {
					this.deleteResult(item, key);
				}
			})
			.catch(err => {
				// An error occurred
			})
		},	
		deleteResult(item, key) {
		
			axios.post("/api/deleteMemberMiniTestResult/"+ item.id +"?api_token=" + this.api_token, {
				method: "POST",
				id: item.id,

			}).then((response) => {
				if (response.data.success === true) {	
					this.items.splice(key, 1);
					//this.getMiniTests(this.currentPage);
					//this.$forceUpdate;
				} else {
					alert ("Error deleting please try again later")
				}
			})
			.catch(function (error) {
				console.log("Error " + error);
			});
			
		
		},
		changePage(page) 
		{		
			this.getMiniTests(page);
		},
		showTestDetailModal(item) 
		{
			this.$bvModal.show('miniTestDetailModal');
			this.item = item;
		},
        getMiniTests(page) 
		{
			axios.post("/api/getMemberMiniTestResult?page="+ page +"&api_token=" + this.api_token, 
			{
					method: "POST",
					memberID: this.memberinfo.user_id
			}).then(response => {

				if (response.data.success === true) 
				{	
					this.rows = response.data.items.total;
					this.perPage = response.data.items.per_page;
					this.currentPage = response.data.items.current_page;

					//add to items 
					this.items = response.data.items.data;

				} else {

					alert( response.data.items.message );
				}
			})
			.catch(function(error) {

				alert("Error " + error);
			});
			
        }
    },
    computed: {},
    updated: function() {}
};
</script>

<style scoped></style>
