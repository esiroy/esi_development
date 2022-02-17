<template>    
    <div id="updateMemberFormContainer">        
        <form name="updateMemberFormEntry" @submit.prevent="handleSubmit">
            <!-- [start] member information section-->
            <div id="information-section" class="section">

                <div class="card-title bg-gray p-1">
                    <div class="pl-2 font-weight-bold small">Personal Information</div>
                </div>

                <div id="agent-row" class="row pt-2">
                    <div class="col-6">
                        <div class="row">
                            <div class="col-4 small pr-0">
                                <label for="agent" class="px-0 pl-2 col-md-12 col-form-label"><span>&nbsp;</span>Agent <div class="float-right">:</div></label>
                            </div>
                            <div class="col-6">
                                <input type="text" name="agent" class="form-control form-control-sm bg-white" v-model="user.agent_name_en" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="row">
                            
                            <div class="col-4 small pr-0">
                                <label for="agent" class="px-0 col-md-12 col-form-label">Agent ID<div class="float-right">:</div></label>
                            </div>
                            <div class="col-6">
                                <input type="text" name="agent_id" v-model="user.agent_id" v-on:keyup="getAgentName()" class="form-control form-control-sm">
                            </div>
                        </div>
                    </div>
                </div>


                <div id="japanese-name-row" class="row pt-2">
                    <div class="col-6">
                        <div class="row">
                            <div class="col-4 small pr-0">
                                <label for="japanese_lastname" class="px-0 col-md-12 col-form-label"><span class="text-danger">*</span> Japanese Last Name <div class="float-right">:</div></label>
                            </div>
                            <div class="col-6">
                                <input type="text"                                                
                                        v-model="user.japanese_lastname"                                                 
                                        id="japanese_lastname" 
                                        name="japanese_lastname" 
                                        class="form-control form-control-sm"
                                        :class="{ 'is-invalid' : submitted && $v.user.japanese_lastname.$error }"
                                        @blur='checkIsValid($v.user.japanese_lastname, $event)' 
                                />
                                <div v-if="submitted && !$v.user.japanese_lastname.required" class="invalid-feedback">
                                    Japenese Last Name is required
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="row">
                            <div class="col-4 small pr-0">
                                <label for="japanese_firstname" class="px-0 col-md-12 col-form-label"><span class="text-danger">*</span> Japanese First Name<div class="float-right">:</div></label>
                            </div>
                            <div class="col-6">                                       
                                <div class="form-group">
                                    <input type="text" 
                                            v-model="user.japanese_firstname"                                                    
                                            id="japanese_firstname" 
                                            name="japanese_firstname" 
                                            class="form-control form-control-sm" 
                                            :class="{ 'is-invalid' : submitted && $v.user.japanese_firstname.$error}"
                                            @blur='checkIsValid($v.user.japanese_firstname, $event)'
                                    />
                                    <div v-if="submitted && !$v.user.japanese_firstname.required" class="invalid-feedback">
                                        Japanese First Name is required
                                    </div>                                 
                                </div>
                            </div>
                        </div>
                    </div>    
                </div>        


                <div id="english-name-row" class="row pt-2">
                    <div class="col-6">
                        <div class="row">
                            <div class="col-4 small pr-0">
                                <label for="last_name" class="px-0 col-md-12 col-form-label"><span class="text-danger">*</span> Last Name <div class="float-right">:</div></label>
                            </div>
                            <div class="col-6">
                                <input type="text"                                                
                                        v-model="user.last_name"                                                 
                                        id="last_name" 
                                        name="last_name" 
                                        class="form-control form-control-sm"
                                        :class="{ 'is-invalid' : submitted && $v.user.last_name.$error }"
                                        @blur='checkIsValid($v.user.last_name, $event)' 
                                />
                                <div v-if="submitted && !$v.user.last_name.required" class="invalid-feedback">
                                    Last Name is required
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="row">
                            <div class="col-4 small pr-0">
                                <label for="first_name" class="px-0 col-md-12 col-form-label"><span class="text-danger">*</span> First Name<div class="float-right">:</div></label>
                            </div>
                            <div class="col-6">                                       
                                <div class="form-group">
                                    <input type="text" 
                                            v-model="user.first_name"                                                    
                                            id="first_name" 
                                            name="first_name" 
                                            class="form-control form-control-sm" 
                                            :class="{ 'is-invalid' : submitted && $v.user.first_name.$error}"
                                            @blur='checkIsValid($v.user.first_name, $event)'
                                    />
                                    <div v-if="submitted && !$v.user.first_name.required" class="invalid-feedback">
                                        First Name is required
                                    </div>                                 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            

                <div id="member-attribute-row" class="row pt-2">
                    <div class="col-6">
                        <div class="row">
                            <div class="col-4 small pr-0">
                                <label for="last_name" class="px-0 col-md-12 col-form-label"><span class="text-danger">*</span> Attribute <div class="float-right">:</div></label>
                            </div>
                            <div class="col-6">

                                <select id="attribute" name="attribute"
                                    v-model="user.attribute"
                                    class="form-control form-control-sm" 
                                    :class="{ 'is-invalid' : submitted && $v.user.attribute.$error}"
                                    @blur='checkIsValid($v.user.attribute, $event)'
                                >
                                    <option value="">-- Select --</option>
                                    <option v-for="attribute in this.attributes" :value="attribute.value" :key="attribute.id">{{ attribute.name }}</option>
                                </select>

                                <div v-if="submitted && !$v.user.attribute.required" class="invalid-feedback">
                                    Member attribute is required
                                </div>                                          
                            </div>
                        </div>
                    </div>
                </div>

                <div id="nickname-row" class="row pt-2">
                    <div class="col-6">
                        <div class="row">
                            <div class="col-4 small pr-0">
                                <label for="nickname" class="px-0 col-md-12 col-form-label"><span class="text-danger">*</span> Nickname <div class="float-right">:</div></label>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <input type="text" v-model="user.nickname" id="nickname" name="nickname" 
                                            class="form-control form-control-sm" 

                                            
                                            :class="{ 'is-invalid': submitted && $v.user.nickname.$error }" 
                                            @blur='checkIsValid($v.user.nickname, $event)'
                                    />
                                    <div v-if="submitted && !$v.user.nickname.required" class="invalid-feedback">
                                        Nickname is required
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div id="gender-row" class="row pt-2">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-2 small pr-0">
                                <label for="last_name" class="px-0 col-md-12 col-form-label"><span class="text-danger">*</span> Gender <div class="float-right">:</div></label>
                            </div>
                            <div class="col-8 pr-0 mr-0">
                                <div class="form-group my-0 pt-2">
                                
                                    <div class="form-group">                                           
                                        <input type="radio" v-model="user.gender" name="gender" :checked="user.gender === 'MALE'" value="MALE" class="" :class="{ 'is-invalid': submitted && $v.user.gender.$error }" />
                                        <label for="gender" class="small col-2 col-xs-2 col-md-1 px-0">Male</label>

                                        <input type="radio" v-model="user.gender" name="gender" :checked="user.gender === 'FEMALE'" value="FEMALE" class="" :class="{ 'is-invalid': submitted && $v.user.gender.$error }" />
                                        <label for="gender" class="small col-2 col-xs-2 col-md-1 px-0">Female</label>

                                        <div v-if="submitted && !$v.user.gender.required" class="invalid-feedback">
                                            Gender is required
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="communication-app-row" class="row pt-2">
                    <div class="col-6">
                        <div class="row">
                            <div class="col-4 small pr-0">
                                <label for="communication_app" class="px-0 col-md-12 col-form-label"><span class="text-danger">*</span> Communication App <div class="float-right">:</div></label>
                            </div>
                            <div class="col-8">
                                <div class="row my-0">
                                    <div class="col-5">
                                        <select id="communication_app" name="communication_app"                                                
                                            class="form-control form-control-sm" 
                                            v-model="user.communication_app"
                                            :class="{ 'is-invalid': submitted && $v.user.communication_app.$error }"
                                            @blur='checkIsValid($v.user.communication_app, $event)'
                                        >
                                            <option value="">-- Select --</option>
                                            <option value="Skype" :selected="this.memberinfo.communication_app === 'Skype'">Skype</option>
                                            <option value="Zoom" :selected="this.memberinfo.communication_app === 'Zoom'">Zoom</option>
                                        </select>
                                        <div v-if="submitted && !$v.user.communication_app.required" class="invalid-feedback">
                                            Communication App is required, Please select from choices
                                        </div>                                            
                                    </div>
                                    <div class="col-6 px-0">                                          
                                        <div class="form-group">                                               
                                            <input type="text" v-model="user.communication_app_username" id="communication_app_username" name="communication_app_username" 
                                                class="form-control form-control-sm" 
                                                :class="{ 'is-invalid': submitted && $v.user.communication_app_username.$error }"
                                                @blur='checkIsValid($v.user.communication_app_username, $event)'
                                            />
                                            <div v-if="submitted && !$v.user.communication_app_username.required" class="invalid-feedback">
                                                Communication App Username is required
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="member-id-row" class="row pt-2">
                    <div class="col-6">
                        <div class="row">
                            <div class="col-4 small pr-0">
                                <label for="id" class="px-0 col-md-12 col-form-label"><span>&nbsp;</span> Member ID <div class="float-right">:</div></label>
                            </div>
                            <div class="col-6">
                                <input type="text" name="id" class="form-control form-control-sm bg-white" 
                                :value="this.memberinfo.user_id" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="member-email-row" class="row pt-2">
                    <div class="col-6">
                        <div class="row">
                            <div class="col-4 small pr-0">
                                <label for="email" class="px-0 col-md-12 col-form-label"><span class="text-danger">*</span> E-Mail Adress (Username) <div class="float-right">:</div></label>
                            </div>
                            <div class="col-6">
                                <div class="form-group">                                       
                                    <input type="text" v-model="user.email" id="email" name="email" value="" placeholder="E-mail Address"
                                    class="form-control form-control-sm" 
                                    :class="{ 'is-invalid': submitted && $v.user.email.$error }" 
                                    @blur='checkIsValid($v.user.email, $event)'
                                    />
                                    <div v-if="submitted && !$v.user.email.required" class="invalid-feedback">
                                        E-Mail is required
                                    </div>
                                    <div v-if="submitted && !$v.user.email.email" class="invalid-feedback">
                                        Please input a valid e-mail address
                                    </div>                                        
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div id="birthday-row" class="row pt-2">
                    <div class="col-6">
                        <div class="row">
                            <div class="col-4 small pr-0">
                                <label for="birthday" class="px-0 col-md-12 col-form-label">Birthday <div class="float-right">:</div>
                                </label>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <datepicker 
                                        :language="ja"
                                        :disabled="false"
                                        id="birthday" 
                                        name="birthday"
                                        v-model="memberinfo.birthday"
                                        :format="birthDateFormatter"
                                        :input-class="'form-control form-control-sm'"                                          
                                    ></datepicker>
                                    <!--
                                    <datepicker 
                                        :language="ja"
                                        :disabled="false"
                                        id="birthday" 
                                        name="birthday"
                                        v-model="memberinfo.birthday"
                                        :format="birthDateFormatter"
                                        :input-class="[ 'form-control form-control-sm ', { 'is-invalid': submitted && $v.user.birthday.$error }]"                                           
                                    ></datepicker>                                    
                                    <div v-if="submitted && !$v.user.birthday.required" class="invalid-feedback" style="display: block">
                                        Birthday is required
                                    </div>
                                    -->
                                </div>                                                              
                            </div>
                        </div>
                    </div>
                </div>

                <div id="age" class="row pt-2">
                    <div class="col-6">
                        <div class="row">
                            <div class="col-4 small pr-0">
                                <label for="age" class="px-0 col-md-12 col-form-label"><span class="text-danger"> &nbsp;</span> Age <div class="float-right">:</div></label>
                            </div>
                            <div class="col-2">
                                <input type="text" v-model="user.age" name="age" class="form-control form-control-sm" placeholder="">
                            </div>
                        </div>
                    </div>
                </div>

                <div id="membship-row" class="row pt-2">
                    <div class="col-6">
                        <div class="row">
                            <div class="col-4 small pr-0">
                                <label for="membership" class="px-0 col-md-12 col-form-label"><span class="text-danger"> &nbsp;</span> Membership <div class="float-right">:</div></label>
                            </div>
                            <div class="col-8">
                                <!-- MEMBERSHIP-->
                                <select name="membership" v-model="user.membership" class="form-control form-control-sm">
                                    <option value="">-- Select --</option>
                                    <option v-for="membership in this.memberships" :value="membership.name" :key="membership.id">{{ membership.name }}</option>                                     
                                </select>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!--[end] member information section-->

            <!--[start] Preferences -->
            <div id="preferred-tutor-section" class="section">
                <div class="card-title bg-gray p-1">
                    <div class="pl-2 font-weight-bold small">
                        <!--Preferred Tutor-->
                        Member Monthly Lesson Credits 
                    </div>
                </div>
                <div class="row pt-2 mb-4">
                    <div class="col-12">
                        <!--[start] lesson class row -->
                        <div id="lesson-class-row" class="row pt-2">
                            <div class="col-2">
                                <label for="agent" class="px-0 col-md-12 col-form-label"><span class="text-danger">&nbsp;</span>
                                Lesson Class<div class="float-right">:</div></label>                                    
                            </div>
                            
                            <div  class="col-6">

                                <div class="row bg-lightgray border-bottom border-top">
                                    <div class="col-3 col-md-3 text-center bold">
                                        <label for="year">Year</label>
                                    </div>
                                    <div class="col-3 col-md-3 text-center bold">                                            
                                        <label for="month">Month</label>
                                    </div>
                                    <div class="col-3 col-md-3 text-center bold">
                                        <label for="grade">Grade</label>
                                    </div>
                                </div>

                                <div class="row py-2 bg-lightgray border-bottom">
                                    <div class="col-3 col-md-3 pr-0">
                                        <select id="lessonClassYear" name="lessonClassYear" v-model="user.preference.lesson.class.year" class="form-control form-control-sm pl-0" >
                                            <option v-for="year in years" :value="year" :key="year" >{{ year }}</option>
                                        </select>                                              
                                    </div>
                                    <div class="col-3 col-md-3 pr-0">
                                        <select id="lessonClassMonth" name="lessonClassMonth" v-model="user.preference.lesson.class.month" class="form-control form-control-sm pl-0">
                                            <option value="JAN" class="mx-0 px-0">January</option>
                                            <option value="FEB">Febuary</option>
                                            <option value="MAR">March</option>
                                            <option value="APR">April</option>
                                            <option value="MAY">May</option>
                                            <option value="JUN">June</option>
                                            <option value="JUL">July</option>
                                            <option value="AUG">August</option>
                                            <option value="SEP">September</option>
                                            <option value="OCT">October</option>
                                            <option value="NOV">November</option>
                                            <option value="DEC">December</option>
                                        </select>
                                    </div>
                                    <div class="col-3 col-md-3">                                            
                                        <input id="lessonClassGrade" name="lessonClassGrade" type="text" v-model="user.preference.lesson.class.lesson_limit" class="form-control form-control-sm" />
                                    </div>                                        
                                    <div class="col-3 col-md-3 text-center">     
                                        <button class="btn btn-success btn-sm col-12" @click.prevent="addLessonClass()">Add</button>
                                    </div>                                        
                                </div>

                                <!--[start] enumaration of all added timeslot -->
                                <div class="row py-2 bg-lightgray border-bottom" v-for="(lessonClass, index) in user.preference.lessonClasses" :key="lessonClass.id" >
                                    <div class="col-3 col-md-3 text-center">
                                            {{ lessonClass.attribute }}                            
                                    </div>
                                    <div year="col-3 col-md-3 text-center">{{ lessonClass.year }} {{ lessonClass.month }}</div>                                        
                                    <div class="col-3 col-md-3 text-center">     
                                        <input type="text" v-model="lessonClass.lesson_limit" class="form-control form-control-sm d-inline-block" />
                                    </div> 
                                    <div class="col-3 col-md-3 text-center">
                                        <button class="btn btn-danger btn-sm col-4" @click.prevent="removeLessonClass(index)">X</button>                                            
                                    </div>
                                </div>

                            </div><!--[end] lesson class row -->

                        </div>

                    </div>
                </div>

            </div>
            <!--[end] Member Preferences -->

            <!--[Start] Purpose -->
            <div id="purpose-section" class="section">
                <div class="mb-4">
                    <PurposeComponent :purposeList="this.purposeList"></PurposeComponent>
                </div>
            </div>
            <!--[End] Purpose -->

   
            <div id="examScore-section" class="section">

                <div class="card-title bg-gray p-1 mt-4">
                    <div class="pl-2 font-weight-bold small">Examination Scores                    
                        <span class="btnAddScoreHolder ml-2">
                            <span v-b-modal.modalUpdateMemberForm class="p-1 bg-blue text-white">
                                <i class="fas fa-plus"></i>
                            </span>
                        </span>    
                    </div>
                </div>

                <div id="memberAddExamScoreForm" class="modal-container">
                    <b-modal id="modalUpdateMemberForm" title="Add Member Examination Score" @show="resetModal" @hide="resetButtons">

                        <form id="updateMemberForm" name="updateMemberForm" @submit.prevent="handleUpdateMemberSubmit">   
                            <!--[start] Exam (New)-->
                            <div id="examination-section" class="section">

                                <div class="row pt-2">
                                    <div class="col-4">                       
                                        <div class="pl-2 small"> <span class="text-danger">*</span> Type of Examination </div>
                                    </div>                   
                                    <div class="col-8">
                                        <select id="examType" name="examType" v-model="examType" @change="handleChangeExamType($event)" class="form-control form-control-sm pl-0  col-md-10">
                                            <option value="" class="mx-0 px-0">Select Examination Type</option>
                                            <option value="IELTS" class="mx-0 px-0">IELTS</option>
                                            <option value="TOEFL">TOEFL iBT</option>
                                            <option value="TOEFL_Junior">TOEFL Junior</option>
                                            <option value="TOEFL_Primary_Step_1">TOEFL Primary Step 1</option>
                                            <option value="TOEFL_Primary_Step_2">TOEFL Primary Step 2</option>
                                            <option value="TOEIC_Listening_and_Reading">TOEIC Listening and Reading</option>
                                            <option value="TOEIC_Speaking">TOEIC Speaking</option>
                                            <option value="TOEIC_Writing">TOEIC Writing</option>
                                            <option value="EIKEN">EIKEN(英検）</option>
                                            <option value="TEAP">TEAP</option>
                                            <option value="Other_Test">Other Test</option>
                                        </select>       
                                    </div>                     
                                </div>

                                <div class="row pt-2">
                                    <div class="col-4">                       
                                        <div class="pl-2 small"> <span class="text-danger">*</span> Examination Date </div>
                                    </div>
                                    <div class="col-8">
                                        <div class="mb-2 ">
                                            <datepicker id="examDate" 
                                                name="examDate"                                          
                                                v-model="examDate"
                                                :value="examDate"
                                                :format="examDateFormatter"
                                                :placeholder="'Select Date'"
                                                :input-class="[ 'form-control form-control-sm col-md-10 bg-white ']"
                                                :language="ja"
                                            ></datepicker>  

                                        </div>
                                    </div>
                                </div>  
                            </div>           

                            <div id="examScoreContainer" class="row">
                                <div class="col-12">  
                                    <!--[start] Dynamic Examination Scores -->
                                    <IELTScoreComponent :examScore="examScore" :size="this.size"></IELTScoreComponent>
                                    <ToeflScoreComponent :examScore="examScore" :size="this.size"></ToeflScoreComponent>
                                    <ToeflJuniorScoreComponent :examScore="examScore" :size="this.size"></ToeflJuniorScoreComponent>
                                    <ToeflPrimaryStep1ScoreComponent :examScore="examScore" :size="this.size"></ToeflPrimaryStep1ScoreComponent>
                                    <ToeflPrimaryStep2ScoreComponent :examScore="examScore" :size="this.size"></ToeflPrimaryStep2ScoreComponent>
                                    <ToeicListeningAndReadingScoreComponent :examScore="examScore" :size="this.size"></ToeicListeningAndReadingScoreComponent>
                                    <ToeicSpeakingScoreComponent :examScore="examScore" :size="this.size"></ToeicSpeakingScoreComponent>
                                    <ToeicWritingScoreComponent :examScore="examScore" :size="this.size"></ToeicWritingScoreComponent>

                                    <EikenScoreComponent :examScore="examScore" :size="this.size"></EikenScoreComponent>
                                    <TeapScoreComponent :examScore="examScore" :size="this.size"></TeapScoreComponent>            
                                                            
                                    <!--[end] Dynamic Examination Scores -->

                                    <!--[start] Other-->
                                    <div id="ScoresComponent" class="ScoresComponent">
                                        <!--[start] TEAP- -->
                                        <div id="examination-score-Other_Test" class="section examScoreHolder">
                                            <div class="row pt-2">
                                                <div class="col-4">                       
                                                    <div class="pl-2 small  mb-2"> <span class="text-danger">*</span> Score </div>             
                                                </div>
                                                <div class="col-8">            
                                                    <input id="otherScore" name="otherScore" v-model="examScore.Other_Test.otherScore" class="form-control form-control-sm col-md-3">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--[end]-->
                                </div>
                            </div>
                        </form>

                        <template #modal-footer>
                            <div class="buttons-container w-100">
                                <p class="float-left"></p>
                                <div v-if="updateType == 'update' || updateType == 'edit'">
                                    <b-button variant="primary" size="sm" class="float-right mr" id="updateExamScore" v-on:click="updateExamScore">Update Exam Score</b-button>
                                </div>

                                <div v-else>
                                    <b-button variant="primary" size="sm" class="float-right mr" id="addExamScore" v-on:click="addExamScore">Save Exam Score</b-button>
                                </div>
                                <b-button variant="danger" size="sm" class="float-right mr-2" @click="$bvModal.hide('modalUpdateMemberForm')">Cancel</b-button> 

                                
                            </div>

                            <div class="loading-container">
                                <b-button variant="primary" size="sm" class="float-right mr">
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    Loading...
                                </b-button>
                            </div>
                        </template> 

                    </b-modal>
                </div> 


                <!-- RECENT SCORES -->
                <div class="row">     
                    <div class="col-2"></div>
                    <div class="col-8">
                        <div class="latest-score">

                            <div class="label">
                                <span class="font-weight-bold small">Exam Date:</span> 
                                <span class="small">{{ this.latestScore.examDate }}</span>
                            </div>

                            <div class="label">
                                <span class="font-weight-bold small">Exam Type:</span>  
                                <span class="small">{{ this.latestScore.examType }}</span> 
                            </div>

                            <div v-for="(value, name) in this.latestScore.examScores" :key="name">
                                <span class="font-weight-bold small">{{ capitalizeFirstLetter(name) }}</span>: 
                                <span class="small">{{ value }}</span>
                            </div>
                            
                        </div>

        

                    
                        <b-modal id="examHistory" ref="examHistoryModal" title="Exam Scores">
                            <input type="hidden" id="memberExamUserID" v-model="memberinfo.user_id">
                            <div id="memberExamScores">
                                <span v-html="this.examScores"></span>
                            </div>
                        </b-modal>


                    </div>
                </div>
                <!--[end]-->

                <!-- SCORE MODAL Button-->
                <div class="row mt-2">
                     <div class="col-2"></div>

                    <!-- View Scores -->
                    <div class="col-8 float-right px-0 mx-0 d-flex">
                    
                        <span v-b-modal.modalMemberExamScoreList >
                            <b-button size="sm" variant="dark" pill>
                                <b-icon-calculator></b-icon-calculator> <span class="small"> View Scores </span> 
                            </b-button>                   
                        </span>
                        &nbsp;

                        <span v-b-modal.modalMemberExamScoreGraph>
                            <b-button size="sm" variant="primary" pill>
                                <b-icon-bar-chart-fill></b-icon-bar-chart-fill> <span class="small">Score Graph </span>
                            </b-button>                   
                        </span>
                        &nbsp;
                    </div>


                    <!-- [START] SCORE MODAL -->
                    <div id="memberExamScoreList" class="modal-container">
                        <b-modal id="modalMemberExamScoreList" title="テストスコア履歴" size="xl" @show="getMemberScoreList">  

                            <div id="memberExamModalMessage" class="row">
                                <div class="text-center col-md-12">No Data found</div>                            
                            </div>


                            <div class="row">
                                <div class="col-4" v-for="(examScoreType, examScoreTypeIndex) in examScoreTypes" :key="examScoreTypeIndex">

                                    <div class="card esi-card mb-3">
                                        <div class="card-header esi-card-header small">
                                             {{ capitalizeFirstLetter(examScoreType) }}
                                            <div class="float-right" v-if="examScoreList[examScoreType].rows >= 1">
                                                <a href="#" @click.prevent="showUpdateScoreForm(examScoreType)"><b-icon icon="pencil-square" aria-hidden="true"></b-icon></a>
                                                <a href="#" @click.prevent="deleteScore(examScoreType, examScoreList[examScoreType].items.details[0].id)"><b-icon icon=" trash" aria-hidden="true"></b-icon></a>
                                            </div>
                                        </div>
                                        <div v-for="(values, scoreListIndex) in examScoreList[examScoreType]" :key="scoreListIndex" >
                                            <div :id="examScoreType" :class="examScoreType" v-if="scoreListIndex == 'rows'">
                 

                                               <div v-if="examScoreList[examScoreType].rows >= 1">

                                                    <table class="table esi-table table-bordered table-striped" >
                                                        <tbody :id="item.id" v-for="(item, itemIndexKey) in examScoreList[examScoreType].items.data" :key="itemIndexKey">
                                                            <tr>
                                                                <td> Exam Date </td>
                                                                <td>
                                                                    {{ dateFormatter(examScoreList[examScoreType].items.details[itemIndexKey].exam_date) }}
                                                                </td>
                                                            </tr>
                                                            <tr v-for="(field, fieldKey) in examScoreList[examScoreType].fields" :key="fieldKey" >
                                                                <td class="mb-4" >
                                                                    {{ ucwords(FormatObjectKey(field)) }}
                                                                </td>
                                                                <td class="mb-4" >
                                                                <!-- {{ item[field] }} (reactive)-->
                                                                    {{ examScoreDisplay[examScoreType +'_display'].items.data[0][field]  }}
                                                                </td>                                                                         
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                

                                                    <div class="mt-4">
                                                        <b-pagination
                                                            v-model="examScoreList[examScoreType].currentPage"
                                                            @input="changePage(examScoreType, examScoreList[examScoreType].currentPage)"
                                                            :total-rows="examScoreList[examScoreType].rows"
                                                            :per-page="examScoreList[examScoreType].perPage"
                                                            first-text="<<"
                                                            prev-text="<"
                                                            next-text=">"
                                                            last-text=">>"
                                                            size="sm"
                                                            align="center"                                            
                                                        ></b-pagination>
                                                    </div>
                                               </div>
                                                <div v-else class="text-center py-5">
                                                    <span class="small text-info">
                                                        No results found
                                                    </span>
                                                </div>                                               

                                            </div>
                                        </div>
                                    </div>
                                </div>                        
                            </div>   

                            <template #modal-footer>
                                <div class="buttons-container w-100">
                                    <p class="float-left"></p>
                                    <b-button variant="primary" size="sm" class="float-right mr-2" @click="$bvModal.hide('modalMemberExamScoreList')">Close</b-button>                            
                                </div>
                            </template>  


                        </b-modal>
                    </div>
                    <!-- [END] SCORE MODAL -->


                    <!-- [START] SCORE MODAL GRAPH -->
                    <div id="memberExamScoreGraph" class="modal-container">
                        <b-modal id="modalMemberExamScoreGraph" title="テストスコア履歴 グラフ" size="xl" @show="getMemberScoreTotalList"> 

                            <div id="memberGraphModalMessage" class="row">
                                <div class="text-center col-md-12">No Data found</div>                            
                            </div>

                            <div class="row">
                                <div class="col-4" v-for="(examScoreType, examScoreTypeIndex) in examScoreTypes" :key="examScoreTypeIndex">
                                    <line-chart :chart-data="datacollection[examScoreType]"  v-if="loaded"  :options="extraOptions[examScoreType]"></line-chart>
                                </div>
                            </div>

                            <template #modal-footer>
                                <div class="buttons-container w-100">
                                    <p class="float-left"></p>
                                    <b-button variant="primary" size="sm" class="float-right mr-2" @click="$bvModal.hide('modalMemberExamScoreGraph')">Close</b-button>                            
                                </div>
                            </template>                         
                            
                        </b-modal>
                    </div>
                    <!-- [END] SCORE MODAL -->


                </div>
            </div>


            <!--[start] Lesson Details -->
            <div id="lesson-details-section" class="section">
                <div class="card-title bg-gray p-1 mt-4">
                    <div class="pl-2 font-weight-bold small">Lesson Details</div>
                </div>
                <div class="row pt-2">
                    <div class="col-6">
                        <div class="row">
                            <div class="col-4 small pr-0">
                                <label for="agent" class="px-0 col-md-12 col-form-label"><span class="text-danger">&nbsp;</span> 
                                Member Since<div class="float-right">:</div></label>
                            </div>
                            <div class="col-6">                                    
                                <datepicker                        
                                    id="member_since" 
                                    name="member_since"
                                    :value="memberinfo.member_since"
                                    :format="memberSinceFomattter"
                                    :input-class="[ 'form-control form-control-sm ' /* , { 'is-invalid': submitted && $v.user.member_since.$error }*/]"
                                    :language="ja"
                                ></datepicker>                                    
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row pt-2">
                    <div class="col-6">
                        <div class="row">
                            <div class="col-4 small pr-0">
                                <label for="agent" class="px-0 col-md-12 col-form-label"><span class="text-danger">*</span>
                                Lesson Time<div class="float-right">:</div></label>
                            </div>

                            <div class="col-6">
                                <select id="lessonshiftid" name="lessonshiftid"
                                    v-model="user.lessonshiftid"
                                    class="form-control form-control-sm" 
                                    :class="{ 'is-invalid': submitted && $v.user.lessonshiftid.$error }"
                                    @blur='checkIsValid($v.user.lessonshiftid, $event)'
                                    @change="propagateMainTutorOptions"   
                                >
                                    <option value="">-- Select --</option>
                                    <option v-for="shift in shifts" :value="shift.id" :key="shift.id">{{ shift.name }}</option>

                                </select>
                                <div v-if="submitted && !$v.user.lessonshiftid.required" class="invalid-feedback">
                                    Lesson Time is required
                                </div>                                    
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row pt-2">
                    <div class="col-6">
                        <div class="row">
                            <div class="col-4 small pr-0">
                                <label for="agent" class="px-0 col-md-12 col-form-label"><span class="text-danger">*</span> 
                                Main Tutor<div class="float-right">:</div></label>
                            </div>
                            <div class="col-6">
                                <select id="maintutor" name="maintutor" 
                                    v-model="user.maintutorid"
                                    class="form-control form-control-sm"
                                    :class="{ 'is-invalid': submitted && $v.user.maintutorid.$error }"
                                    @blur='checkIsValid($v.user.maintutorid, $event)'                                                                            
                                >
                                    <option value="">-- Select --</option>
                                    <!--@todo loop dynamically the teacher of times -->
                                    <option v-for="mainTutor in mainTutors" :value="mainTutor.user_id" :key="mainTutor.user_id">{{ mainTutor.firstname }} {{ mainTutor.lastname }}</option>
                                </select>
                                <div v-if="submitted && !$v.user.maintutorid.required" class="invalid-feedback">
                                    Main Tutor is required
                                </div>                                       
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!--[end] Lesson Details -->

            <!--Report Requirement-->
            <div id="member-report-requirement" class="section">
                <div class="card-title bg-gray p-1 mt-4">
                    <div class="pl-2 font-weight-bold small">Report Requirement</div>
                </div>

                <div class="row pt-2">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-2 small pr-0">
                                <label for="agent" class="px-0 col-md-12 pt-4 col-form-label">                                    
                                    Report Card <div class="float-right">:</div>
                                </label>
                            </div>
                            <div class="col-10">
                                <div class="row">
                                    <div class="col-2 pr-0">
                                        <div class="text-center">Member</div>
                                        <select name="memberReportCard" class="form-control form-control-sm" v-model="user.reportCard.member">
                                            <option value="">-- Select --</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                    <div class="col-2 pr-0">
                                        <div class="text-center">Agent</div>
                                        <select name="agentReportCard" class="form-control form-control-sm" v-model="user.reportCard.agent">
                                            <option value="">-- Select --</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row pt-2">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-2 small pr-0">
                                <label for="agent" class="px-0 col-md-12 col-form-label">                                        
                                    Monthly Report <div class="float-right">:</div>
                                </label>
                            </div>
                            <div class="col-10">
                                <div class="row">
                                    <div class="col-2 pr-0">
                                        <select name="year" class="form-control form-control-sm" v-model="user.monthlyReport.member">
                                            <option value="">-- Select --</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                    <div class="col-2 pr-0">
                                        <select id="month" name="month" class="form-control form-control-sm" v-model="user.monthlyReport.agent">
                                            <option value="">-- Select --</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
             <!--[end] Report Requirement-->

            <div id="member-latest-reportcard" class="section">
                <div class="card-title bg-gray p-1 mt-4">
                    <div class="pl-2 font-weight-bold small">Member CEFR Level</div>
                </div>
                <div class="row pt-2">

                    <div class="col-12">
                        <div class="row">
                            <div class="col-2 small pr-0">
                                <label for="agent" class="px-0 col-md-12 col-form-label">
                                    Level<div class="float-right">:</div>
                                </label>
                            </div>
                            <div class="col-10">
                                <div class="row">
                                    <div class="col-10 pt-1">
                                    
                                       <b-form-select id="level" v-model="selectedMemberLevel" :options="memberLevelOptions" ></b-form-select> 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>                

            </div>


            <!--[start] Member Latest Report Card -->
            <div id="member-latest-reportcard" class="section">
                <div class="card-title bg-gray p-1 mt-4">
                    <div class="pl-2 font-weight-bold small">Latest Report Card</div>
                </div>
                <div class="row pt-2">

                    <div class="col-12">
                        <div class="row">
                            <div class="col-2 small pr-0">
                                <label for="agent" class="px-0 col-md-12 col-form-label">
                                    Level<div class="float-right">:</div>
                                </label>
                            </div>
                            <div class="col-10">
                                <div class="row">
                                    <div class="col-10 pt-1">
                                       {{ this.latestreportcard.lesson_level }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>



                    <div class="col-12">
                        <div class="row">
                            <div class="col-2 small pr-0">
                                <label for="agent" class="px-0 col-md-12 col-form-label">
                                    Course<div class="float-right">:</div>
                                </label>
                            </div>
                            <div class="col-10">
                                <div class="row">
                                    <div class="col-10 pt-1">
                                       {{ this.latestreportcard.lesson_course }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="row">
                            <div class="col-2 small pr-0">
                                <label for="agent" class="px-0 col-md-12 col-form-label">
                                    Material<div class="float-right">:</div>
                                </label>
                            </div>
                            <div class="col-10">
                                <div class="row">
                                    <div class="col-10 pt-1">
                                       {{ this.latestreportcard.lesson_material }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="row">
                            <div class="col-2 small pr-0">
                                <label for="agent" class="px-0 col-md-12 col-form-label">
                                    Grade<div class="float-right">:</div>
                                </label>
                            </div>
                            <div class="col-10">
                                <div class="row">
                                    <div class="col-10 pt-1">
                                       {{ this.latestreportcard.lesson_grade }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>  

                    <div class="col-12">
                        <div class="row">
                            <div class="col-2 small pr-0">
                                <label for="agent" class="px-0 col-md-12 col-form-label">
                                    Homework <div class="float-right">:</div>
                                </label>
                            </div>
                            <div class="col-6">

                                <table class="table table-sm border">
                                    <tr class="bg-darkblue">
                                        <th class="small text-white text-center font-weight-bold">
                                            Members Home Work 
                                        </th>
                                    </tr>                                                                    
                                    <tr>
                                        <td class="px-2">  

                                            <div v-if="this.latestreportcard.homework.url">
                                                <div class="small">
                                                    File: <a :href="this.latestreportcard.homework.url" 
                                                    :download="this.latestreportcard.homework.url" >{{ this.latestreportcard.homework.url }}</a>
                                                </div>

                                                <div class="small">
                                                    Instruction : {{ this.latestreportcard.homework.instruction }}
                                                </div>
                                            </div>
                                            <div v-else>                   
                                                <div class="text-left pt-2">
                                                    <span class="small text-secondary">No homework found!</span>
                                                </div>
                                            </div>

                                        </td>
                                    </tr>
                                </table> 

                            </div>


                        </div>
                    </div>
                                        
                </div>
            </div>
            <!--[end] Member Latest Report Card -->


            <div id="member-point-purchase-type" class="section">
                <div class="card-title bg-gray p-1 mt-4">
                    <div class="pl-2 font-weight-bold small">Point Purchase Type</div>
                </div>

                <div class="row pt-2">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-2 small pr-0">
                                <label for="agent" class="px-0 col-md-12 col-form-label"><span class="text-danger">&nbsp;</span> Point Purchase<div class="float-right">:</div></label>
                            </div>
                            <div class="col-10">
                                <div class="row">
                                    <div class="col-2 pr-0">
                                        <select id="pointpurchase" name="pointpurchase" class="form-control form-control-sm" v-model="user.pointPurchase">
                                            <option value="">-- Select --</option>
                                            <option value="AGENT">Agent</option>
                                            <option value="DIRECT">Direct</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="member-desired-schedule" class="section">
                <div class="card-title bg-gray p-1 mt-4">
                    <div class="pl-2 font-weight-bold small">Desired Schedule </div>
                </div>
                <div class="row pt-2">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-2 small pr-0">
                            </div>
                            <div class="col-10">
                                <div class="row">
                                    <div class="col-2 pr-0">
                                        <select id="selectDay" name="desiredDay"  v-model="user.desiredSchedule.day" class="form-control form-control-sm d-inline-block">
                                            <option value="">-- Select --</option>
                                            <option value="MONDAY">Monday</option>
                                            <option value="TUESDAY">Tuesday</option>
                                            <option value="WEDNESDAY">Wednesday</option>
                                            <option value="THURSDAY">Thursday</option>
                                            <option value="FRIDAY">Friday</option>
                                            <option value="SATURDAY">Saturday</option>
                                            <option value="SUNDAY">Sunday</option>
                                        </select>
                                    </div>
                                    <div class="col-3">                                                                                        
                                        <b-form-timepicker id="timepicker-sm" size="sm" :hour12="false" v-model="user.desiredSchedule.desired_time" local="en" class="mb-4"></b-form-timepicker>
                                    </div>
                                    <div class="col-3">
                                        <button class="btn btn-success btn-sm d-inline-block"  @click.prevent="addDesiredSchedule()">Add</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6 offset-md-2">
                                <!--[start] enumaration of all TOEIC timeslot */-->
                                <div class="row py-2 bg-lightgray border-bottom" v-for="(schedule, index) in user.desiredScheduleList" :key="schedule.id" >
                                    <div id="scheduleDayr" class="col-3 col-md-3 text-center">
                                        {{ schedule.day }}
                                    </div>
                                    <div id="scheduleMo" class="col-3 col-md-3 text-center">                                                                                    
                                        {{ schedule.desired_time | formatDate}}
                                    </div>        
                                    <div class="col-3 col-md-3 text-center">
                                        <button class="btn btn-danger btn-sm col-4" @click.prevent="removeDesiredSchedule(index)">X</button>                                            
                                    </div>																				                                 
                                </div> 
                                
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div id="submit-button" class="section row py-4">
                <div class="col-2"></div>
                <div class="col-3 text-left">
                    <button class="btn btn-primary btn-sm">Save</button>
                    <input type="reset" value="Cancel" class="btn btn-primary btn-sm">
                    
                </div>
            </div>

        </form>
    </div>
</template>

<script>
import LineChart from './frontend/chart/lineChartComponent.vue'
import PurposeComponent from "./purpose/PurposeComponent.vue";
import IELTScoreComponent from "./scores/IELTScoreComponent.vue";
import ToeflScoreComponent from "./scores/ToeflScoreComponent.vue";
import ToeflJuniorScoreComponent from "./scores/ToeflJuniorScoreComponent.vue";
import ToeflPrimaryStep1ScoreComponent from "./scores/ToeflPrimaryStep1ScoreComponent.vue";
import ToeflPrimaryStep2ScoreComponent from "./scores/ToeflPrimaryStep2ScoreComponent.vue";
import ToeicListeningAndReadingScoreComponent from "./scores/ToeicListeningAndReadingScoreComponent.vue";
import ToeicSpeakingScoreComponent from "./scores/ToeicSpeakingScoreComponent.vue";
import EikenScoreComponent from "./scores/EikenScoreComponent.vue";
import TeapScoreComponent from "./scores/TeapScoreComponent.vue";
import ToeicWritingScoreComponent from "./scores/ToeicWritingScoreComponent.vue";


import Vuelidate from "vuelidate";
import { required, email, minLength, sameAs } from "vuelidate/lib/validators";
Vue.use(Vuelidate);

import * as Moment from 'moment'
import Datepicker from 'vuejs-datepicker';
import {en, ja} from 'vuejs-datepicker/dist/locale';


export default {
    name: "MemberUpdateComponent",
    components: {
        LineChart,
        Datepicker, PurposeComponent,
        IELTScoreComponent, 
        ToeflScoreComponent, ToeflJuniorScoreComponent,
        ToeflPrimaryStep1ScoreComponent, ToeflPrimaryStep2ScoreComponent, 
        ToeicListeningAndReadingScoreComponent, ToeicSpeakingScoreComponent, ToeicWritingScoreComponent, 
        EikenScoreComponent,
        TeapScoreComponent,
    },
    props: {
        agentinfo: {
            type: Object
        },
        memberinfo: {
            type: Object,
        },
        userinfo: {
            type: Object,
        },
        currentmemberlevel: {
            type: Object
        },
        latestreportcard: {
            type: Object,
        },
		purpose: {
			type: Array
		},
        memberlatestexamscore: {
            type: Object
        },
		lessongoals: {
			type: Array
		},
		lessonclasses: {
			type: Array
		},
		desiredschedule: {
			type: Array 
		},
        memberships : {
            type: Array
        },
        attributes: {
            type: Array
        },
        shifts : {
            type: Array
        },        
		csrf_token: {
			type: String
		},
		api_token: {
			type: String
		},
    },
    data() {
        return {
            updateType: "",

            submitted: false,
            currentYear: new Date().getFullYear(),
            
            selectedMemberLevel: null,

            memberLevelOptions: [
                { value: null, text: 'Please select CEFR Level' },
                { value: 'C2', text: 'C2 (Mastery)' },
                { value: 'C1', text: 'C1 (Expert)' },
                { value: 'B2', text: 'B2 (Upper Intermediate)' },
                { value: 'B1', text: 'B1 (Intermediate)' },
                { value: 'A2', text: 'A2 (Elementary)' },
                { value: 'A1', text: 'A1 (Starter)' },
                { value: 'A 0', text: 'A 0 (Beginner)' }
            ],

            //set calendar characters to japanese
            ja: ja, 

            extraOptions: [],

            //list of main tutors
            mainTutors: [],        

            //this is for examp type column
            size: {
                leftColumn  : "col-4",
                rightColumn : "col-8",
                select      : "col-10",
            },   

            //number sliders
            errorMsg: '',
        
            //charts
            loaded: false,
            datacollection: [],


            //Exam Score Listings
            examScoreTypes: [],
            examScoreList: [],
            examScoreDisplay: [],
            examScoreLink: [],


            examScorePage: {
                IELTS: {                    
                    perPage : 1,
                    rows: 1,
                    currentPage : 1,
                    items : 1,
                }, 
                TOEFL: {                    
                    perPage : 1,
                    rows: 1,
                    currentPage : 1,
                    items : 1,
                },
                TOEFL_Junior: {                    
                    perPage : 1,
                    rows: 1,
                    currentPage : 1,
                    items : 1,
                },
                TOEFL_Primary_Step_1: {                    
                    perPage : 1,
                    rows: 1,
                    currentPage : 1,
                    items : 1,          
                },
                TOEFL_Primary_Step_2: {                    
                    perPage : 1,
                    rows: 1,
                    currentPage : 1,
                    items : 1,             
                },
                TOEIC_Listening_and_Reading: {                    
                    perPage : 1,
                    rows: 1,
                    currentPage : 1,
                    items : 1, 
                },
                TOEIC_Speaking: {
                    perPage : 1,
                    rows: 1,
                    currentPage : 1,
                    items : 1,
                },
                EIKEN: {
                    perPage : 1,
                    rows: 1,
                    currentPage : 1,
                    items : 1,
                },
                TEAP: {                    
                    perPage : 1,
                    rows: 1,
                    currentPage : 1,
                    items : 1,
                },
                Other_Test: {
                    perPage : 1,
                    rows: 1,
                    currentPage : 1,
                    items : 1,
                }
            }, 


            //Exam Date (Form Entry)
            examDate: "",
            uExamDate: "",
            examType: "",
            examLevel: "",

            examScore: {
                IELTS: {                    
                    speakingBandScore : "",
                    writingBandScore : "",
                    readingBandScore : "",
                    listeningBandScore : "",
                    overallBandScore : "",
                }, 
                TOEFL: {                    
                    speakingScore: "",
                    writingScore: "",
                    readingScore: "",
                    listeningScore: "",
                    total: "",
                },
                TOEFL_Junior: {                    
                    listening: "",
                    languageFormAndMeaning: "",
                    reading: "",
                    total: "",
                },
                TOEFL_Primary_Step_1: {                    
                    reading: "",                    
                    listening: "",
                    total: "",               
                },
                TOEFL_Primary_Step_2: {                    
                    reading: "",                    
                    listening: "",
                    total: "",               
                },
                TOEIC_Listening_and_Reading: {                    
                    reading: "",                    
                    listening: "",
                    total: "",     
                },
                TOEIC_Speaking: {
                    speaking: "",
                    total: ""
                },
                TOEIC_Writing: {
                    writing: "",
                    total: "",
                },
                EIKEN: {
                    grade_5: "",
                    grade_4: "",
                    grade_3_1st_stage: "",
                    grade_pre_2_1st_stage: "",
                    grade_2_1st_stage: "",
                    grade_pre_1_1st_stage: "",
                    grade_1_1st_stage: "",

                    grade_3_2nd_stage: "",
                    grade_pre_2_2nd_stage: "",
                    grade_2_2nd_stage: "",
                    grade_pre_1_2nd_stage: "",
                    grade_1_2nd_stage: "",     
                    total: ""               
                },
                TEAP: {                    
                    speakingScore: "",
                    writingScore: "",
                    readingScore: "",
                    listeningScore: "",
                    total: "",
                },
                Other_Test: {
                    otherScore: "",
                }
            }, 


            //Latest Score (show recently added scores)
            latestScore: {
                examDate: "",
                examType: "",
                examScores: "",                
            },

            //list exam scores (paginated)
            examScores: [],

            //purpose List
            purposeList: {
                IELTS:  "",
                IELTS_option:
                {
                        Speaking: "",
                        Writing: "",
                        Reading: "",
                        Listening: "",                        
                },
                IELTS_targetScore:
                {
                        Speaking: 3,
                        Writing: 3,
                        Reading: 3,
                        Listening: 3,                        
                },



                TOEFL: "",
                TOEFL_option: {
                        Speaking: "",
                        Writing: "",
                        Reading: "",
                        Listening: "",                
                },
                TOEFL_targetScore:
                {
                        Speaking: 0,
                        Writing: 0,
                        Reading: 0,
                        Listening: 0,                        
                },


                TOEFL_Primary: "",

                TOEIC: "",
                TOEIC_option: {
                        Speaking: "",
                        Writing: "",
                        Reading: "",
                        Listening: "",                
                },
                TOEIC_targetScore:
                {
                        Speaking: 0,
                        Writing: 0,
                        Reading: 0,
                        Listening: 0,                        
                },                 

                EIKEN: "",
                EIKEN_option: {
                        EIKEN_Grade_5: "",
                        EIKEN_Grade_4: "",
                        EIKEN_Grade_3: "",
                        EIKEN_Grade_pre_2: "",
                        EIKEN_Grade_2: "",
                        EIKEN_Grade_pre_1: "",
                        EIKEN_Grade_1: "",
                },
                EIKEN_targetScore:
                {
                        Grade_5: 0,
                        Grade_4: 0,
                        Grade_3: 0,
                        Grade_pre_2: 0,
                        Grade_2: 0,
                        Grade_pre_1: 0,
                        Grade_1: 0,                      
                }, 

                TEAP: "",
                TEAP_option:
                {
                        Speaking: "",
                        Writing: "",
                        Reading: "",
                        Listening: "",                        
                },
                TEAP_targetScore:
                {
                        Speaking: 0,
                        Writing: 0,
                        Reading: 0,
                        Listening: 0,                        
                },     
               

                BUSINES: "",
                BUSINESS_option:
                {
                        Basic: "",
                        Intermediate: "",
                        Advance: "",                               
                },                  
                BUSINESS_targetScore:
                {
                        Basic: "Beginner",
                        Intermediate: "Beginner",
                        Advance: "Beginner",                               
                },  
                


                BUSINESS_CAREERS: "",
                BUSINESS_CAREERS_option:
                {
                        Medicine: "",
                        Nursing: "",
                        Pharmaceutical: "",          
                        Accounting: "",
                        Legal_Professionals: "",
                        Finance: "",       
                        Technology: "",
                        Commerce: "",
                        Tourism: "",       
                        Cabin_Crew: "",
                        Marketing_and_Advertising: "",                                                                                                                        
                },  
                BUSINESS_CAREERS_targetScore:
                {
                        Medicine: "Beginner",
                        Nursing: "Beginner",
                        Pharmaceutical: "Beginner",          
                        Accounting: "Beginner",
                        Legal_professionals: "Beginner",
                        Finance: "Beginner",       
                        Technology: "Beginner",
                        Commerce: "Beginner",
                        Tourism: "Beginner",       
                        Cabin_crew: "Beginner",
                        Marketing_and_advertising: "Beginner",                                                                                                                        
                },  


                DAILY_CONVERSATION: "",
                DAILY_CONVERSATION_option:
                {
                        Basic: "",
                        Intermediate: "",
                        Advance: "",                               
                },
                DAILY_CONVERSATION_targetScore:
                {
                        Basic: "Beginner",
                        Intermediate: "Beginner",
                        Advance: "Beginner",
                },                

                OTHERS: "",
                OTHERS_value: "",
            },

            //users
            user: {
                agent_id: "",
				agent_name_en: "",
                id: "",
                first_name: "",
                last_name: "",
                nickname: "",
              
                
                japanese_lastname: "",
                japanese_firstname: "",

                attribute: "",
                email: "",
                username: "",
                gender: "",
                communication_app: "",
                communication_app_username: "",
                birthday: "",
                ubirthday: "", //unformatted birthday                
                age: "",
                membership: "",
                password: "",
                confirmPassword: "",               

                //Lesson Details - Section
                member_since: "",
                umember_since: "", //unformatted umember_since
                lessonshiftid: "",                
                maintutorid: "",

                //Preferred Tutor - Section
                preference: 
                {            
                    purpose: {
                        BILINGUAL: "",
                        CONVERSATION: "",
                        ANTI_EIKEN: "",
                        ANTI_EXAM: "",
                        TOEFL: "",
                        TOEIC: "",
                        STUDY_ABROAD: "",
                        BUSINESS: "",
                        OTHERS: ""
                    },  
                    purposeExtraDetails: {
                        BILINGUAL: "",
                        CONVERSATION: "",
                        ANTI_EIKEN: "",
                        ANTI_EXAM: "",
                        TOEFL: "",
                        TOEIC: "",
                        STUDY_ABROAD: "",
                        BUSINESS: "",
                        OTHERS: ""

                    },

                    //array list of purpose
                    lessongoals: [],
                    lesson: {
                        class: {
                            month:  "",
                            year:   new Date().getFullYear(),
                            lesson_limit:  ""
                        }
                    },

                    lessonClasses: [],
                },

                examRecord: {
                    toeic: {
                        month:  "",
                        year:   "",
                        grade:  ""
                    },
                    eiken: {
                        month:  "",
                        year:   "",
                        grade:  ""                            
                    }
                },
                //Array: enumaration of lesson classes!
              
                toeicList: [],
                eikenList: [],                

                //Report Requirement
                reportCard: {
                    member: "",
                    agent: ""
                },
                monthlyReport: {
                    member: "",
                    agent: ""
                },

                //Point Purchase
                pointPurchase: "",

                //desired schedule list
                desiredSchedule: {
                    day: "",
                    desired_time: ""
                },

                desiredScheduleList: [],
            },
            


        };
    },      
    mounted: function () 
	{

        if (this.currentmemberlevel) {
            this.selectedMemberLevel = this.currentmemberlevel.level;
        }  else {
            this.selectedMemberLevel = null;
        }

        

    


        //console.log(this.memberlatestexamscore, "latest score");

        if (this.memberlatestexamscore.original.success == true) {
            this.latestScore.examDate = this.memberlatestexamscore.original.examDate;
            this.latestScore.examType = this.memberlatestexamscore.original.examType;
            this.latestScore.examScores = JSON.parse(this.memberlatestexamscore.original.examScores);
        }        

        try {
            this.user.agent_id	= this.agentinfo.agent_id;  
        } catch(err) {
            this.user.agent_id	= "";
        }        

        try {
            this.user.agent_name_en	 = this.agentinfo.firstname;  
        } catch(err) {
           this.user.agent_name_en = "";
        }

        //console.log(this.userinfo);
        //console.log(this.latestreportcard);

        //get user
        this.user.user_id                       = this.userinfo.id;
		this.user.first_name					= this.userinfo.firstname;
		this.user.last_name						= this.userinfo.lastname;

        this.user.japanese_firstname			= this.userinfo.japanese_firstname;
		this.user.japanese_lastname			    = this.userinfo.japanese_lastname;

        this.user.email							= this.userinfo.email;
		this.user.attribute						= this.memberinfo.attribute;		
		this.user.nickname						= this.memberinfo.nickname;
		this.user.gender						= this.memberinfo.gender;		
		this.user.communication_app             = this.memberinfo.communication_app;

        if ( this.user.communication_app === 'Skype' || this.user.communication_app === 'skype') 
        {
            this.user.communication_app_username    = this.memberinfo.skype_account; 

        } else if ( this.user.communication_app === 'Zoom' || this.user.communication_app === 'zoom') {

            this.user.communication_app_username    = this.memberinfo.zoom_account;
        } else {

            //member added skype so lets set comm app to skype
            if (this.memberinfo.skype_account) {               
                this.user.communication_app = "Skype";
                this.user.communication_app_username    = this.memberinfo.skype_account;
            } else {
                //member added zoom account so let make it zoom
                this.user.communication_app = "Zoom";
                this.user.communication_app_username    = this.memberinfo.zoom_account;
            }
        }

		this.user.birthday						= this.memberinfo.birthday;
		this.user.age							= this.memberinfo.age;
		this.user.membership					= this.memberinfo.membership;

		//Lesson Details - Section
        this.user.member_since	    = this.memberinfo.member_since;
		this.user.lessonshiftid 	= this.memberinfo.lesson_shift_id;                        
		this.user.maintutorid 		= this.memberinfo.tutor_id;

		//report cards
		if (this.memberinfo.is_report_card_visible === 1) {
            this.user.reportCard.member = "Yes"; 
        } else {
            this.user.reportCard.member = "No"; 
        }

		if (this.memberinfo.is_report_card_visible_to_agent === 1)  {
            this.user.reportCard.agent = "Yes";
        } else {
            this.user.reportCard.agent = "No";
        }

		//monthly reports
		if (this.memberinfo.is_monthly_report_card_visible === 1) {
            this.user.monthlyReport.member  = "Yes"; 
        } else {
            this.user.monthlyReport.member  = "No"; 
        }

		if (this.memberinfo.is_monthly_report_card_visible_to_agent === 1)  {
            this.user.monthlyReport.agent = "Yes"; 
        } else {
            this.user.monthlyReport.agent = "No"; 
        }

		//Lesson Goals (purpose) (OLD Scheme)       
        /*
		let item  = [];
		for (item of this.lessongoals) {	

			if (item.purpose !== '' || item.purpose !== null) this.user.preference.purpose[item.purpose] = true;  
            
            if (item.purpose === "CONVERSATION") 
            {
                this.user.preference.purposeExtraDetails.[item.purpose] = item.goal;
            } 
            else if (item.purpose === "ANTI_EXAM" || item.purpose === "STUDY_ABROAD") 
            {
                this.user.preference.purposeExtraDetails.[item.purpose] = item.year_level;
            }
            else 
            {
                this.user.preference.purposeExtraDetails.[item.purpose] = item.extra_detail;
            }
		}*/

        let purposeItem  = [];
        let purposeOptionItem = [];
        let purposeOptionItems = [];

        let purposeTargetScoreItem = [];
        let purposeTargetScores = [];

		for (purposeItem of this.purpose) 
        {
            let mainItemStr = purposeItem.purpose.replace(/\s+/g, '_');
            this.purposeList[mainItemStr] = mainItemStr;

            if (purposeItem.purpose.toLowerCase() == "others" ) 
            {
                this.purposeList.OTHERS_value = purposeItem.purpose_options;
            } else {

                purposeOptionItems = JSON.parse( purposeItem.purpose_options);
                if (purposeOptionItems === null || purposeOptionItems === "null" || purposeOptionItems === "") 
                {
                   //no option
                } else {                   
                    for (purposeOptionItem of purposeOptionItems) 
                    {	                      
                        let purposeOptionItemStr = purposeOptionItem.replace(/\s+/g, '_');
                        this.purposeList[mainItemStr +"_option"][purposeOptionItemStr] = purposeOptionItemStr;
                    }
                }

               // console.log( purposeItem.target_scores);

               purposeTargetScores = JSON.parse( purposeItem.target_scores);

                if (purposeTargetScores === null || purposeTargetScores === "null" || purposeTargetScores === "") 
                {
                   //no option
                } else {      
                    Object.entries(purposeTargetScores).forEach(entry => {
                        const [key, value] = entry;
                        const mykeyArray = key.split("_");

                        let keyword = "";

                        mykeyArray.forEach(function(string) 
                        {
                            let keyStr = string.charAt(0).toUpperCase() + string.slice(1);
                            if (string == "and") {
                                keyStr = "and";
                            }
                            //document.getElementById("demo").innerHTML += " "+ keyStr; 
                            keyword += " "+  keyStr;
                          
                        });

                        //console.log("keyword ==> " + keyword.trim());
                        //let keyStr = key.charAt(0).toUpperCase() + key.slice(1);
                        //let purposeOptionItemStr = keyStr.replace(/\s+/g, '_') + "";
                        

                        //console.log(purposeOptionItemStr);
                        let keyStrCleaned = keyword.trim();
                        let purposeOptionItemStr = keyStrCleaned.replace(/\s+/g, '_') + "";

                        this.purposeList[mainItemStr +"_targetScore"][purposeOptionItemStr] = value;

                        
                    });

                    

                    
                }

            }
        }

        
		//Point Purchase
		this.user.pointPurchase	= this.memberinfo.point_purchase_type;
		//enumerate lesson classes
		this.user.preference.lessonClasses = this.lessonclasses;
		//desired schedule
		this.user.desiredScheduleList = this.desiredschedule;
		this.propagateMainTutorOptions(); //25 minutes is default	
    },
    validations: 
    {
        user: 
        {   
            first_name: { 
                required                
            },
            last_name: { 
                required                
            },

            japanese_firstname: { 
                required                
            },
            japanese_lastname: { 
                required                
            },

            attribute: {
                required
            },
            nickname: {
                required
            },
            gender: {
                required
            },
            communication_app: {
                required
            },            
            communication_app_username: {
                required
            },

            /*
            birthday: {
                required
            },
            */

            email: { required, email },
            //password: { required, minLength: minLength(6) },
            //confirmPassword: { required, sameAsPassword: sameAs("password") },
            
            lessonshiftid: {
                required
            },

            maintutorid: {
                required
            }

            /*lessongoals: required */
        }
    },
    methods: {

        handleSubmit(e) 
        {
            this.submitted = true;
            // stop here if form is invalid
            this.$v.$touch();
            if (this.$v.$invalid) {
                alert ("Errors found, please check the form for errors");
                return;
            }

       
            axios.post("/api/update_member?api_token=" + this.api_token, 
            {
                method          : "POST",
                user            : JSON.stringify(this.user),
                purposeList     : JSON.stringify(this.purposeList),
                level           : this.selectedMemberLevel
            })
            .then(response => 
            {
                if (response.data.success === false) {
                    alert (response.data.message);
                } else if (response.data.success  === true) {
                    location.reload(); //success
                } 

			}).catch(function(error) {
                // handle error
                alert("Error " + error);
                //console.log(error);
            }).finally(() => {
                
            });
            
                        
        },
        handleChangeExamType(event) 
        {
            this.examLevel = "";
            this.submitted = false;

            let examTypeValue = event.target.value;                    
            let examType = examTypeValue.replace(/\s+/g, '-');
            this.hideClass('examScoreHolder');
            if (examType.length  > 0 ) {
                this.showElementId('examination-score-'+ examType);
            }
            this.removeHighlightExamElement();
        },
        hideClass(className) {
            var elements = document.getElementsByClassName(className)
            for (var i = 0; i < elements.length; i++){
                elements[i].style.display = "none";
            }        
        },
        getMemberScoreList() 
        {
            this.getMemberExamScoreByType();
        },
        getMemberScoreTotalList() {
            this.getMemberExamTotal();
        },   
        getMemberExamScoreByType() 
        {

            /*
            axios.post("/api/getMemberExamScoreByType?api_token=" + this.api_token, 
            {
                method      : "POST",
                memberID    : this.memberinfo.user_id,
                limit       : 1,
            }).then(response => {               
                if (response.data.success === true) 
                {
                    this.examScoreTypes = response.data.examTypes;
                    this.examScoreList = response.data.examScoreList;
                }
            });*/

            axios.post("/api/getMemberExamScoreByType?api_token=" + this.api_token, 
            {
                method      : "POST",
                memberID    : this.memberinfo.user_id,
                examType    : this.examType,
                limit       : 1,
            }).then(response => {               
                if (response.data.success === true) 
                {

                    $('#memberExamModalMessage').hide();
                    this.examScoreTypes = response.data.examTypes;
                    this.examScoreList = response.data.examScoreList;
                    this.examScoreDisplay = response.data.examScoreDisplay;
                }
                else
                {

                    this.examScoreTypes = [];
                    this.examScoreList = [];
                    this.examScoreDisplay = [];

                    console.log(response.data.message);
                }
            }).catch(function(error) {
                console.log("Error " + error);
            });  


        },     
        getMemberExamTotal() 
        { 
            axios.post("/api/getMemberScoreHistory?api_token=" + this.api_token, 
            {
                method      : "POST",
                memberID    : this.memberinfo.user_id,
            }).then(response => {    
                       
                if (response.data.success === true) 
                {
                    $('#memberGraphModalMessage').hide();

                    this.examScoreTypes = response.data.examTypes;
                    this.examScoreList = response.data.examScoreList;
                    let types = this.examScoreTypes;


                    let max = {'IELTS': 9, 'TOEFL': 120, 'TOEFL_Junior': 900, 
                                'TOEFL_Primary_Step_1':  218, 'TOEFL_Primary_Step_2': 230,
                                'TOEIC_Listening_and_Reading': 990, 'TOEIC_Speaking': 200, 'TOEIC_Writing' : 495,
                                'EIKEN_Grade_5': 850,
                                'EIKEN_Grade_4': 1000,     
                                'EIKEN_Grade_3': 2200,                                
                                'EIKEN_Grade_2': 2600,
                                'EIKEN_Grade_1': 3100,                                
                                'EIKEN_Grade_pre_1': 3000,
                                'EIKEN_Grade_pre_2': 2400,
                            }

                    types.forEach((type) => 
                    {            
                        let totals = response.data.examScoreList[type].totals;

                        this.datacollection[type] = {
                            labels: response.data.examScoreList[type].dates,
                            datasets: [
                                {
                                    label: this.capitalizeFirstLetter(type),
                                    backgroundColor: '#'+ Math.floor(Math.random()*16777215).toString(16), 
                                    data: totals,                   
                                }                                
                            ],                           
                        }
                        
                        if (type == "Other_Test") 
                        {                        
                            this.extraOptions['Other_Test'] = null;
                        } else {
                        
                            this.extraOptions[type] = { 
                                scales: {
                                    yAxes: [
                                    {
                                        ticks: {
                                            min: 0,
                                            max: max[type],
                                            stepSize: 1,
                                            reverse: false,
                                            beginAtZero: true
                                        }
                                    }]
                                }
                            };

                        }
                          
                    });
                    this.loaded = true;
                }
                else
                {

                    
                }
            }).catch(function(error) {
               
                console.log(error);
            });       


        },    

        changePage (examType, page) {
            this.getMemberExamScoreByPage(examType, page);
        },
        showUpdateScoreForm(examType)
        {  
            clearTimeout(this.messageTimer);
            this.$bvModal.show('modalUpdateMemberForm'); 

            //SET AFTER SHOWING MODAL

            this.updateType = 'update';
            this.examType = examType;           

            //test if EIKEN
            let examtypeCheck = examType.split("_");           

            if (examtypeCheck[0] == "EIKEN") 
            {
                this.examType = examtypeCheck[0];
                let levelExamType = examType.split("Grade_"); 
                
                this.examLevel = levelExamType[1];         
                this.examDate = this.examScoreList[examType].items.details[0].exam_date;

                this.$nextTick(() => 
                {
                    document.getElementById("gradeLevel").setAttribute("disabled", "disabled");
                });


            } else {
                this.examType = examType;
                this.examDate = this.examScoreList[examType].items.details[0].exam_date; 
            }


            this.$nextTick(() => 
            {
                document.getElementById("examType").setAttribute("disabled", "disabled");

                this.hideClass('examScoreHolder');              
                let examTypeSelect = this.examType.replace(/\s+/g, '-');
                if (examTypeSelect.length  > 0 ) 
                {               
                    this.showElementId('examination-score-'+ examTypeSelect); 
                    this.examScore[examType] = this.examScoreList[examType].items.data[0] 
                }

                this.removeHighlightExamElement();    
                this.$forceUpdate();                  
            });

            $('#updateButtonContainer').show();

        },
        addExamScore(event) 
        {

            axios.post("/api/addMemberExamScore?api_token=" + this.api_token, 
            {
                method          : "POST",
     
                memberID        : this.memberinfo.user_id,
                examDate        : this.uExamDate,
                examType        : this.examType,
                examLevel       : this.examLevel,
                examScore       : this.examScore[this.examType],     

            })
            .then(response => 
            {              
                if (response.data.success === false) 
                {                  
                    this.highlightExamElement();
                } else {
                    this.latestScore.examDate = response.data.examDate;
                    this.latestScore.examType = response.data.examType;                    
                    this.latestScore.examScores = JSON.parse(response.data.examScores);

                    //new (!!!)
                    this.latestScore.examDate = response.data.examDate;
                    this.latestScore.examType = response.data.examType;                    
                    this.latestScore.examScores = JSON.parse(response.data.examScores);

                    $(document).find('.modal-footer').hide();

                    $(document).find('#updateMemberForm').slideUp(500, function() {
                        $(document).find('#updateMemberForm').html('<div class="alert alert-success text-center" role="alert">Thank you! your score has been submitted</div>');
                        $(document).find('#updateMemberForm').slideDown(500, function() {
                             $(document).find('#updateMemberForm').show();
                        });
                    });             

                    this.messageTimer = setTimeout(function(scope) {
                         scope.$bvModal.hide('modalUpdateMemberForm');
                    }, 3500, this);

                    this.$forceUpdate();
                }

			}).catch(function(error) {
                // handle error
                alert("Error " + error);
                //console.log(error);
            }); 

            event.preventDefault()
        },
        updateExamScore() 
        {
            //SHOW LOADER HERE
            $(document).find('.modal-footer').find('div.buttons-container').hide();
            $(document).find('.modal-footer').find('div.loading-container').show();  

            let examID = null;

            if (this.examType == "EIKEN") 
            {
                examID =  this.examScoreList[this.examType + '_Grade_' + this.examLevel].items.details[0].id;

            } else {

                examID = this.examScoreList[this.examType].items.details[0].id;
            }

            axios.post("/api/updateMemberExamScore?api_token=" + this.api_token, 
            {
                method          : "POST",
                id              : examID,
                memberID        : this.memberinfo.user_id,
                examDate        : this.uExamDate,
                examType        : this.examType,
                examLevel       : this.examLevel,
                examScore       : this.examScore[this.examType],                       
            }).then(response => {

                //HIDE LOADER HERE
                $(document).find('.modal-footer').find('div.buttons-container').show();
                $(document).find('.modal-footer').find('div.loading-container').hide();
                                
                if (response.data.success === false) 
                {    
                    this.highlightExamElement();
                } else {                 
                
                    if (this.examType == "EIKEN") 
                    {

                        this.getMemberExamScoreByPage(this.examType + '_Grade_' + this.examLevel, this.examScoreList[this.examType + '_Grade_' + this.examLevel].currentPage);

                    } else {
                        this.getMemberExamScoreByPage(this.examType, this.examScoreList[this.examType].currentPage);
                    }

                    this.getMemberLatestExamScore();
                    
                    $(document).find('.modal-footer').hide();

                    $(document).find('#updateMemberForm').slideUp(500, function() {
                        $(document).find('#updateMemberForm').html('<div class="alert alert-success text-center" role="alert">Thank you! your score has been submitted</div>');
                        $(document).find('#updateMemberForm').slideDown(500, function() {
                             $(document).find('#updateMemberForm').show();
                        });
                    });

                    this.messageTimer = setTimeout(function(scope) {
                         scope.$bvModal.hide('modalUpdateMemberForm');
                    }, 3500, this);

                    this.$forceUpdate();
                }
			}).catch(function(error) {

                //HIDE LOADER HERE
                $(document).find('.modal-footer').find('div.buttons-container').show();
                $(document).find('.modal-footer').find('div.loading-container').hide();
                console.log(error);
            }); 
        },
        deleteScore(examType, id) 
        {
            axios.post("/api/deleteMemberExamScore?api_token=" + this.api_token, 
            {
                method          : "POST",
                id              : id,
                examType        : examType,
                                
            }).then(response => {

                //HIDE LOADER HERE
                $(document).find('.modal-footer').find('div.buttons-container').show();
                $(document).find('.modal-footer').find('div.loading-container').hide();
                                
                if (response.data.success === true) 
                {    
                    if (examType == "EIKEN") 
                    {
                        let currentPage = this.examScoreList[examType + '_Grade_' + this.examLevel].currentPage;

                        if (currentPage > 1) {
                            let previous_page_eiken = parseInt(currentPage) - 1;
                            this.getMemberExamScoreByPage(examType + '_Grade_' + this.examLevel, previous_page_eiken);
                        } else {
                            this.getMemberExamScoreByPage(examType, 1);    
                        }
                        

                        
                    } else {
                        
                        let currentPage = this.examScoreList[examType].currentPage;

                        if (currentPage > 1) {
                            let previous_page = parseInt(currentPage) - 1;
                            this.getMemberExamScoreByPage(examType, previous_page);
                        } else {
                            this.getMemberExamScoreByPage(examType, 1);
                        }
                        
                        
                    }

                    this.getMemberLatestExamScore();

                } else {                 
                
                    
                }

			}).catch(function(error) {

                //HIDE LOADER HERE
                $(document).find('.modal-footer').find('div.buttons-container').show();
                $(document).find('.modal-footer').find('div.loading-container').hide();
                console.log(error);
            }); 
        },

        getMemberExamScoreByPage(examType, page)  {

            axios.post("/api/getMemberExamScoreByPage?page="+ page +"&api_token=" + this.api_token,            
            {
                method      : "POST",
                memberID    : this.memberinfo.user_id,
                examType    : examType
            }).then(response => {        


                if (response.data.success === true) 
                {

                    this.examScoreList[examType] = response.data.examScoreList[examType];
                    this.examScoreDisplay[examType + '_display'] = response.data.examScoreDisplay[examType + '_display'];
                    this.$forceUpdate();
                }
                else
                {
                    this.examScoreList[examType] = response.data.examScoreList[examType];
                    this.examScoreDisplay[examType + '_display'] = response.data.examScoreDisplay[examType + '_display'];
                 
                }

            }).catch(function(error) {
                console.log("Error " + error);
            });  
        
        },
        hideFormModal(name) {
            this.$bvModal.hide(name);
        },
        showElementId(id) {
            document.getElementById(id).style.display = "block";
        },                
        resetButtons() 
        {
            this.updateType = null;                 
        },
        resetModal() {
            this.submitted = false;
            clearTimeout(this.messageTimer);            
            this.resetScoreData();


            //reset
            this.examDate = "";
            this.examType = "";


            const parentElement = document.querySelector('#examScoreContainer');
            let allChildren = parentElement.querySelectorAll("select");

            allChildren.forEach(item => {            
                //alert (item.id)                                
                let dropDown = document.getElementById(item.id);
                dropDown.selectedIndex = "";
            });

        },
        resetScoreData() {

            this.examDate = "";
            this.uExamDate = "";
            this.examType = "";

            this.examScore = {
                IELTS: {                 
                    speakingBandScore : "",
                    writingBandScore : "",
                    readingBandScore : "",
                    listeningBandScore : "",
                    overallBandScore : "",            
                }, 
                TOEFL: {                   
                    speakingScore: "",
                    writingScore: "",
                    readingScore: "",
                    listeningScore: "",
                    total: "",
                },
                TOEFL_Junior: {                    
                    listening: "",
                    languageFormAndMeaning: "",
                    reading: "",
                    total: "",
                },
                TOEFL_Primary_Step_1: {                    
                    reading: "",                    
                    listening: "",     
                    total: "",               
                },
                TOEFL_Primary_Step_2: {                    
                    reading: "",                    
                    listening: "",     
                    total: "",                                   
                },
                TOEIC_Listening_and_Reading: {                    
                    reading: "",                    
                    listening: "",            
                    total: "",                         
                },
                TOEIC_Speaking: {
                    speaking: "",
                    total: "", 
                },
                TOEIC_Writing: {
                    writing: "",
                    total: "",
                },                
                EIKEN: {
                    grade_5: "",
                    grade_4: "",
                    grade_3_1st_stage: "",
                    grade_pre_2_1st_stage: "",
                    grade_2_1st_stage: "",
                    grade_pre_1_1st_stage: "",
                    grade_1_1st_stage: "",

                    grade_3_2nd_stage: "",
                    grade_pre_2_2nd_stage: "",
                    grade_2_2nd_stage: "",
                    grade_pre_1_2nd_stage: "",
                    grade_1_2nd_stage: "",  
                    total: "",                  
                },
                TEAP: {
                    
                    speakingScore: "",
                    writingScore: "",
                    readingScore: "",
                    listeningScore: "",    
                    total: "",            
                },
                Other_Test: {
                    otherScore: "",
                }
            }         
        }, 
        highlightExamElement()  
        {                       
            let examType = document.getElementById('examType').value;
            let examDate = this.examDate;
            let selection = $('div#examination-score-'+examType).find('select');

            if (examType.length == 0 ) {
                 $('#examType').addClass('border border-danger')
            } else {               
                $(document).find('#examType').removeClass('border border-danger')
            }

            if (examDate == 0) {
                 $('#examDate').addClass('border border-danger')
            } else {
                 $(document).find('#examDate').removeClass('border border-danger')
            }

            selection.each(function() {
                let elementID = $(this).attr('id');
                let numeric = parseInt($(this).val())
                if(!$.isNumeric(numeric)) 
                {
                    console.log(elementID + "  will be highlighted");
                    $('#'+elementID).addClass('border border-danger')
                } else {
                    $('#'+elementID).removeClass('border border-danger')
                }
            });        
        },
        removeHighlightExamElement() 
        {        
            let examType = document.getElementById('examType').value;
            let selection = $('div#examination-score-'+examType).find('select');
            let examDate = document.getElementById('examDate').value;
        
            if (examType.length == 0) {
                 $('#examType').addClass('border border-danger')
            } else {
               
                  $(document).find('#examType').removeClass('border border-danger')
            }

            if (examDate.length == 0) {
                 $(document).find('#examDate').removeClass('border border-danger')
            } else {                 
                 //$('#examDate').addClass('border border-danger')
            }


            selection.each(function() 
            {
                let elementID = $(this).attr('id');
                let numeric = parseInt($(this).val())
                if(!$.isNumeric(numeric)) 
                {
                    $('#'+elementID).removeClass('border border-danger')
                }
            });
        }, 


        getMemberLatestExamScore() 
        {        
            axios.post("/api/getMemberLatestScore?api_token=" + this.api_token,
            {
                method       : "POST",
                limit        : 1,
                memberID     : this.memberinfo.user_id,
            }).then(response => {     
                if (response.data.success === true) 
                { 
                    $('.latest-score-message').html("");
                    $('.latest-score').show();

                    this.latestScore.examDate = response.data.examDate;
                    this.latestScore.examType = response.data.examType;                    
                    this.latestScore.examScores = JSON.parse(response.data.examScores);
                } else {
                    $('.latest-score-message').html("<center>No Latest Score</center>");
                    $('.latest-score').hide();
                }
			});

        },

        showExamHistoryModal() 
        {            
            this.$refs['examHistoryModal'].show(); 

            axios.post("/api/getAllMemberExamScore?page=1&api_token=" + this.api_token, 
            {
                method       : "POST",
                limit        : 5,
                memberID     : this.memberinfo.user_id,
            })
            .then(response => 
            {              
                if (response.data.success === false) {
                       
                } else {
                    this.examScores = response.data.scores;
                }
			}).catch(function(error) {               
                alert("Error " + error);                
            });
            

           
            this.$forceUpdate();

        },
        getAgentName() {
            axios.post("/api/get_agent_name?api_token=" + this.api_token, 
            {
                method          : "POST",
                agent_id        : this.user.agent_id,
            })
            .then(response => 
            {              
                if (response.data.success === false) {
                    //alert (response.data.message);
                     this.user.agent_name_en = "";
                } else {
                    this.user.agent_name_en = response.data.firstname + " " + response.data.lastname;
                }
			}).catch(function(error) {               
                alert("Error " + error);                
            });
        },
        checkIsValid (val, event) 
        {
            if (val.$anyError) 
            {
                //console.log("shake!")

                event.target.classList.add('form__input-shake')                
                setTimeout(() => {
                    event.target.classList.remove('form__input-shake')                  
                }, 600)
            }
        },
        dateFormatter(date) 
        {       
            let fdate = Moment(date).format('YYYY年 MM月 D日');
            return fdate;
        },       
        examDateFormatter(date) 
        {       
            let fdate           = this.dateFormatter(date);
            this.uExamDate      = date;
            return fdate;
        },
        memberSinceFomattter(date) {
            let fdate                   = this.dateFormatter(date);
            this.user.umember_since       = date; 
            this.user.member_since       = fdate; 
            return fdate;
        },        
        birthDateFormatter(date) 
        {
            //format the date!
            let fdate           = this.dateFormatter(date);
            let age             = this.computeAge(date);

            //add the birthday and computed age back to user variable
            this.user.ubirthday  = date;            
            this.user.birthday  = fdate;
            this.user.age       = age; 

             //return the formatted date
            return fdate;
        },
        computeAge(date) {
            //compute the age below!
            let currentDate = new Date();            
            let month = Moment(date).format('MM');
            let day = Moment(date).format('D');
            let year = Moment(date).format('YYYY');
            
            //get the difference from this current year to birthday
            let birthDate = new Date(year + "/"+ month +"/" + day);
            let difference = currentDate - birthDate;
            let age = Math.floor(difference/31557600000);

            return age;
        },

		removeLessonClass(index) {							
			this.user.preference.lessonClasses.splice(index, 1);
		},
        addLessonClass() 
        {            
            //console.log(this.user.preference.lessonClasses); //check all the classess added
            
            if (this.user.attribute) 
            {   
                let year =  this.user.preference.lesson.class.year;
                let month = this.user.preference.lesson.class.month;
                let lesson_limit =  this.user.preference.lesson.class.lesson_limit;

                if (year && month && lesson_limit) 
                {
                    const result =  this.user.preference.lessonClasses.find(item => item.year === year && item.month === month);
                    if (result) {
                        alert ("Selected item is already in the list");
                        return false;
                    } else {
                        this.user.preference.lessonClasses.push({                   
                            attribute: this.user.attribute,
                            year:  this.user.preference.lesson.class.year,
                            month: this.user.preference.lesson.class.month,
                            lesson_limit:  this.user.preference.lesson.class.lesson_limit
                        });
                    }                    
                  
  
                } else {
                    alert ("Incomplete Fields");
                    return false;
                }
            } else {
                alert ("Please select attribute");
            }
        },
        addTOEIC()
        {
            
            let year =  this.user.examRecord.toeic.year;
            let month = this.user.examRecord.toeic.month;
            let grade =  this.user.examRecord.toeic.grade;
        
            if (year && month && grade) {
                let result =  this.user.toeicList.find(item => item.year === year && item.month === month);
                if (result) {
                    alert ("Selected item is already in the list");
                    return false;
                } else {
                    this.user.toeicList.push({                                      
                        year:  this.user.examRecord.toeic.year,
                        month: this.user.examRecord.toeic.month,
                        grade:  this.user.examRecord.toeic.grade
                    });     
                }       
            } else {
                alert ("Please enter TOEIC month, year and grade");
            }
        },
        addEIKEN()
        {
           
            let year =  this.user.examRecord.eiken.year;
            let month = this.user.examRecord.eiken.month;
            let grade =  this.user.examRecord.eiken.grade;

            if (year && month && grade) {     
                let result =  this.user.eikenList.find(item => item.year === year && item.month === month);
                if (result) {
                    alert ("Selected item is already in the list");
                    return false;
                } else {
                    this.user.eikenList.push({                                      
                        year:  this.user.examRecord.eiken.year,
                        month: this.user.examRecord.eiken.month,
                        grade:  this.user.examRecord.eiken.grade
                    });
                }       
            } else {
                alert ("Please enter eiken month, year and grade");
            }
        },
		removeDesiredSchedule(index) {
			this.user.desiredScheduleList.splice(index, 1);
		},		
        addDesiredSchedule() 
        {
        
            let day     = this.user.desiredSchedule.day;
            let desired_time    = this.user.desiredSchedule.desired_time;

            if (day && desired_time) {
                
                let result =  this.user.desiredScheduleList.find(item => item.day === day && item.desired_time === desired_time);

                if (result) {
                    alert ("Selected schedule is already added in the list");
                    return false;
                } else {
                    this.user.desiredScheduleList.push({                                      
                        day:  day,
                        desired_time: desired_time 
                    });
                }
            } else {
                 alert ("Please enter schedule day and time");
            }
                
        },
        propagateMainTutorOptions() 
        {		

            axios.post("/api/get_tutors?api_token=" + this.api_token, 
            {
                method          : "POST",
                shift_id        : this.user.lessonshiftid
            })
            .then(response => 
            {
              
              //console.log(response.data.tutors);

              this.mainTutors = response.data.tutors;

			}).catch(function(error) {
                // handle error
                alert("Error " + error);
                //console.log(error);
            });
            
            
            this.user.eikenList.push(["40-1"]);
        },

        FormatObjectKey(string) {
            let newString = string.charAt(0).toUpperCase() + string.slice(1);
            newString = newString.replace(/_/g, " ")

            //add space before big letters
            return newString.replace(/([A-Z])/g, ' $1').trim(); 
        },
        capitalizeFirstLetter(string) {
            let newString = string.charAt(0).toUpperCase() + string.slice(1);    
            newString = newString.replace(/_/g, " ")       
            return newString.trim(); 
        }, 
        ucwords(string) {
            return string.toLowerCase().replace(/(?<= )[^\s]|^./g, a=>a.toUpperCase())  
        },        
        getTotalScore(ExamType) 
        {
            let selection = $('div#examination-score-'+ExamType).find('select');
            console.log(selection.length);

            let total = 0;
            let filled_selection_length = 0;

            selection.each(function() 
            {
                let elementID = $(this).attr('id');
                let numeric = parseInt($(this).val())
                if($.isNumeric(numeric)) 
                {
                    filled_selection_length++

                    if (elementID.includes("total")) {
                        //this will not be added to total score, since this is a total score element
                    } else {
                        total = parseInt(total) + parseInt($(this).val());                    
                        console.log($(this).attr('id') + " " + parseInt($(this).val() ));
                    }
                } else {
                    console.log("empty");
                }

            });
            //console.log (filled_selection_length + " ? length ? " + selection.length);

            //if (filled_selection_length == (selection.length - 1) ||   filled_selection_length == selection.length  ) 
            if (filled_selection_length == selection.length  ) 
            {
                console.log("Filled Elements " + filled_selection_length)
                console.log("total :  " + total );
                return parseInt(total);
            } else {
                console.log("not all filled!")
            }
        },                

    },
    computed : {
        years () {
            const year = new Date().getFullYear()
            return Array.from({length: (year - 2000) + 1}, (value, index) => 2010 + index)
        },
        
    },    



  
};

Vue.filter('formatDate', function(value) {
  if (value) {
    var H = Moment(value, ["HH"]).format("HH");
    var M = Moment(value, ["HH:m"]).format("mm");


    if (H == 24 || H == "00") {
        return "24" + ":" + M;
    } else {
        return Moment(value, ["HH:mm"]).format("HH:mm");
    }
    
  }
});

</script>

<style>
.form__input-shake {
  animation: shake 0.2s;
  animation-iteration-count: 3;
}

@keyframes shake {
  0% { transform: translateX(0px)  }
  25% { transform: translateX(2px) }
  50% { transform: translateX(0px)  }
  75% { transform: translateX(-2px) }
  100% { transform: translateX(0px)  }
}

.b-form-timepicker .b-form-spinbutton.form-control
{
    height: 100px !important;    
}


.sub_options, .examScoreHolder, .loading-container {
    display: none;
}   

.memberExamTable td {
    font-size: 11px;
}


</style>