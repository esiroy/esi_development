<template>
    <div id="updateMemberForm">
        <form name="updateMemberForm" @submit.prevent="handleSubmit">
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

                <div id="lastname-row" class="row pt-2">
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
                    <div class="col-6">
                        <div class="row">
                            <div class="col-4 small pr-0">
                                <label for="last_name" class="px-0 col-md-12 col-form-label"><span class="text-danger">*</span> Gender <div class="float-right">:</div></label>
                            </div>
                            <div class="col-6">
                                <div class="form-group my-0 pt-2">
                                    <div class="form-group">                                           
                                        <input type="radio" name="gender" :checked="memberinfo.gender === 'MALE'" value="MALE" class="" :class="{ 'is-invalid': submitted && $v.user.gender.$error }" />
                                        <label for="gender" class="small col-2 px-0">Male</label>

                                        <input type="radio" name="gender" :checked="memberinfo.gender === 'FEMALE'" value="FEMALE" class="" :class="{ 'is-invalid': submitted && $v.user.gender.$error }" />
                                        <label for="gender" class="small col-2 px-0">Female</label>

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
                                <label for="birthday" class="px-0 col-md-12 col-form-label"><span class="text-danger">*</span> Birthday <div class="float-right">:</div>
                                </label>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <datepicker 
                                        :language="ja"
                                        id="birthday" 
                                        name="birthday"
                                        v-model="this.memberinfo.birthday"
                                        :value="this.memberinfo.birthday"
                                        :format="birthDateFormatter"
                                        :input-class="[ 'form-control form-control-sm ', { 'is-invalid': submitted && $v.user.birthday.$error }]"                                           
                                    ></datepicker>
                                    <div v-if="submitted && !$v.user.birthday.required" class="invalid-feedback" style="display: block">
                                        Birthday is required
                                    </div>                                          
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
                <div class="card-title bg-gray p-1 mt-4">
                    <div class="pl-2 font-weight-bold small">Preferred Tutor</div>
                </div>
                <div class="row pt-2">
                    <div class="col-12">

                        <!-- Purpose -->
                        <div class="row">
                            <div class="col-2 small pr-0">
                                <label for="purpose" class="p-0 col-md-12 col-form-label">
                                    <!--<span class="text-danger">*</span>-->
                                    Purpose <div class="float-right">:</div>
                                </label>
                            </div>
                            <div class="col-8">

                                <ul class="checkbox-options">
                                    <li>
                                        <input type="checkbox" ref="purposes" name="purposes" id="BILINGUAL"  v-model="user.preference.purpose.BILINGUAL"  value="BILINGUAL"> Take part in Bilingual training course                                            
                                    </li>
                                    
                                    <li>
                                        <input type="checkbox" ref="purposes" name="purposes" id="CONVERSATION" v-model="user.preference.purpose.CONVERSATION"  value="CONVERSATION">
                                        <label>Get conversation(communication) skill</label>

                                        <ul id="goalList" class="checkbox-options" v-if="user.preference.purpose.CONVERSATION">
                                            <li><input type="radio" name="goal" value="BEGINNER" v-model="user.preference.purposeExtraDetails.CONVERSATION"> Beginner- easy daily conversation level</li>
                                            <li><input type="radio" name="goal" value="INTERMEDIATE" v-model="user.preference.purposeExtraDetails.CONVERSATION"> Intermediate- Daily conversation level</li>
                                            <li><input type="radio" name="goal" value="ADVANCE" v-model="user.preference.purposeExtraDetails.CONVERSATION"> Advance - Social, Environment, Business English</li>
                                            <li><input type="radio" name="goal" value="NATIVE" v-model="user.preference.purposeExtraDetails.CONVERSATION"> Be native level</li>
                                        </ul>
                                        <input type="hidden" name="extraDetails" value="BEGINNER">
                                    </li>


                                    <li>
                                        <input type="checkbox" ref="purposes" name="purposes" id="ANTI_EIKEN" v-model="user.preference.purpose.ANTI_EIKEN"  value="ANTI_EIKEN">
                                        English certification exam in Japan

                                        <input type="text" name="extraDetails" v-if="user.preference.purpose.ANTI_EIKEN" v-model="user.preference.purposeExtraDetails.ANTI_EIKEN" class="col-3 pl-1 form-control form-control-sm d-inline-block">
                                    </li>

                                    <li>
                                        <input type="checkbox" ref="purposes" name="purposes" id="ANTI_EXAM" v-model="user.preference.purpose.ANTI_EXAM"  value="ANTI_EXAM"> 
                                        Enter school
                                        <ul id="examLevel" v-if="user.preference.purpose.ANTI_EXAM" style="list-style-type: none;">
                                            <li><input type="radio" name="antiExamLevel" v-model="user.preference.purposeExtraDetails.ANTI_EXAM" value="JUNIOR_HIGH"> Junior High</li>
                                            <li><input type="radio" name="antiExamLevel" v-model="user.preference.purposeExtraDetails.ANTI_EXAM" value="HIGHSCHOOL"> High school</li>
                                            <li><input type="radio" name="antiExamLevel" v-model="user.preference.purposeExtraDetails.ANTI_EXAM" value="UNIVERSITY"> University</li>
                                        </ul>                                            
                                    </li>

                                    <li>
                                        <input type="checkbox" ref="purposes" name="purposes" id="TOEFL" v-model="user.preference.purpose.TOEFL" value="TOEFL" > 
                                        TOEFL(目標スコアー 点)
                                        <input type="text" name="extraDetails" v-if="user.preference.purpose.TOEFL" v-model="user.preference.purposeExtraDetails.TOEFL" class="col-3 pl-1 form-control form-control-sm d-inline-block">
                                    </li>

                                    <li>
                                        <input type="checkbox" ref="purposes" name="purposes" id="TOEIC" v-model="user.preference.purpose.TOEIC" value="TOEIC">  
                                        TOEIC(目標スコアー 点)
                                        <input type="text" name="extraDetails" v-if="user.preference.purpose.TOEIC" v-model="user.preference.purposeExtraDetails.TOEIC" class="col-3 pl-1 form-control form-control-sm d-inline-block">
                                    </li>

                                    <li>
                                        <input type="checkbox" ref="purposes" name="purposes" id="STUDY_ABROAD" v-model="user.preference.purpose.STUDY_ABROAD" value="STUDY_ABROAD"> Study Abroad
                                        <ul id="abroadLevel" style="list-style-type: none;"  v-if="user.preference.purpose.STUDY_ABROAD" >
                                            <li><input type="radio" name="studyAbroadLevel" value="JUNIOR_HIGH" v-model="user.preference.purposeExtraDetails.STUDY_ABROAD"> Junior High</li>
                                            <li><input type="radio" name="studyAbroadLevel" value="HIGHSCHOOL" v-model="user.preference.purposeExtraDetails.STUDY_ABROAD"> High school</li>
                                            <li><input type="radio" name="studyAbroadLevel" value="UNIVERSITY" v-model="user.preference.purposeExtraDetails.STUDY_ABROAD"> University</li>
                                        </ul>
                                    </li>


                                    <li>
                                        <input type="checkbox" ref="purposes" name="purposes" id="business" v-model="user.preference.purpose.BUSINESS" value="BUSINESS"> Business English
                                        <input type="hidden" name="extraDetails" v-if="user.preference.purpose.BUSINESS" v-model="user.preference.purposeExtraDetails.BUSINESS">
                                    </li>

                                    <li>
                                        <input type="checkbox" ref="purposes" name="purposes" id="others" v-model="user.preference.purpose.OTHERS" value="OTHERS"> Others 
                                        <textarea name="extraDetails" rows="2" cols="20" style="min-height: 20px; vertical-align: top;" class="col-3 pl-1 form-control form-control-sm d-inline-block" 
                                            v-if="user.preference.purpose.OTHERS" v-model="user.preference.purposeExtraDetails.OTHERS"></textarea>
                                    </li>
                                </ul>

                                <!-- loop all purposes
                                <div v-if="submitted && !$v.user.purposes.required" class="invalid-feedback" style="display: block">
                                    Member Purpose is required, Please check at least one of the choices
                                </div>
                                -->

                            </div>

                        </div>


                        <!--[start] lesson class row -->
                        <div id="lesson-class-row" class="row pt-2">
                            <div class="col-2">
                                    <label for="agent" class="px-0 col-md-12 col-form-label"><span class="text-danger">&nbsp;</span>
                                Lesson Class<div class="float-right">:</div></label>                                    
                            </div>
                            
                            <div  class="col-6">

                                <div class="row bg-lightgray border-bottom">
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
                                            <option v-for="year in years" :value="year" :key="year">{{ year }}</option>
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
                                        <input type="text" :value="lessonClass.lesson_limit" class="form-control form-control-sm d-inline-block" />
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
                                        <b-form-timepicker id="timepicker-sm" size="sm" v-model="user.desiredSchedule.desired_time" local="en" class="mb-4"></b-form-timepicker>
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
                                        {{ schedule.desired_time }}
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
import Vuelidate from "vuelidate";
import { required, email, minLength, sameAs } from "vuelidate/lib/validators";
Vue.use(Vuelidate);

import * as Moment from 'moment'
import Datepicker from 'vuejs-datepicker';
import {en, ja} from 'vuejs-datepicker/dist/locale';


export default {
    name: "app",
    components: {
        Datepicker
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

		purposes: {
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
            submitted: false,
            //set calendar characters to japanese
            ja: ja, 

            //list of main tutors
            mainTutors: [],
            
            user: {
                agent_id: "",
				agent_name_en: "",
                id: "",
                first_name: "",
                last_name: "",
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
                    purposes: [],
                    lesson: {
                        class: {
                            month:  "",
                            year:   "",
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
        console.log("mount v2 test");

        console.log(this.agentinfo);

        //try if member has an agent
        try {
            this.user.agent_id	= this.agentinfo.agent_id;  
        }catch(err) {
            this.user.agent_id	= "";
            //console.log( err.message);
        }
        

        try {
            this.user.agent_name_en	 = this.agentinfo.firstname;  
        }
        catch(err) {
           this.user.agent_name_en = "";
           //console.log( err.message);
        }  

        //get user
        this.user.user_id                       = this.userinfo.id;
		this.user.first_name					= this.userinfo.firstname;
		this.user.last_name						= this.userinfo.lastname;
        this.user.email							= this.userinfo.email;

		this.user.attribute						= this.memberinfo.attribute;		
		this.user.nickname						= this.memberinfo.nickname;
		this.user.gender						= this.memberinfo.gender;		
		this.user.communication_app             = this.memberinfo.communication_app;

        if ( this.user.communication_app === 'Skype') {
            this.user.communication_app_username    = this.memberinfo.skype_account; 

        } else if ( this.user.communication_app === 'Zoom') {

            this.user.communication_app_username    = this.memberinfo.zoom_account;

        } else {

            if (this.memberinfo.skype_account) {
                this.user.communication_app_username    = this.memberinfo.skype_account;
            } else {
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


		//Lesson Goals (purpose)
       
		let item  = [];
		for (item of this.purposes) {	

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
            birthday: {
                required
            },            
            email: { required, email },
            //password: { required, minLength: minLength(6) },
            //confirmPassword: { required, sameAsPassword: sameAs("password") },
            
            lessonshiftid: {
                required
            },

            maintutorid: {
                required
            }

            /*purposes: required */
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

            //console.log (this.submitted, this.$v.$invalid);
            //alert("SUCCESS!! :-)\n\n" + JSON.stringify(this.user));
            //console.log(JSON.stringify(this.user));

            axios.post("/api/update_member?api_token=" + this.api_token, 
            {
                method          : "POST",
                user            : JSON.stringify(this.user)
            })
            .then(response => 
            {
                if (response.data.success === false) {
                    alert (response.data.message);
                } else {
                    location.reload(); //success
                }

			}).catch(function(error) {
                // handle error
                alert("Error " + error);
                //console.log(error);
            });
                        
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
                // handle error
                alert("Error " + error);
                //console.log(error);
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
            if (this.user.attribute) {
                
                let year =  this.user.preference.lesson.class.year;
                let month = this.user.preference.lesson.class.month;
                let lesson_limit =  this.user.preference.lesson.class.lesson_limit;

                if (year && month && lesson_limit) {
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
                
                let result =  this.user.desiredScheduleList.find(item => item.day === day && item.time === time);

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
        }

    },

    computed : {
        years () {
            const year = new Date().getFullYear()
            return Array.from({length: (year - 2000) + 1}, (value, index) => 2010 + index)
        }
    },      
  
};
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


</style>