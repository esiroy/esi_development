<template>
    <div id="createMemberContainer" class="card mt-4">

        <div class="card-header">Member Form</div>

        <div class="card-body">
            <form @submit.prevent="handleSubmit">
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
                                    <input type="text" name="agent" class="form-control  form-control-sm">
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="row">
                                
                                <div class="col-4 small pr-0">
                                    <label for="agent" class="px-0 col-md-12 col-form-label">Agent ID<div class="float-right">:</div></label>
                                </div>
                                <div class="col-6">
                                    <input type="text" name="agent_id" class="form-control  form-control-sm">
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
                                            v-model="user.lastName"                                                 
                                            id="lastName" 
                                            name="lastName" 
                                            class="form-control form-control-sm"
                                            :class="{ 'is-invalid' : submitted && $v.user.lastName.$error }"
                                            @blur='checkIsValid($v.user.lastName, $event)' 
                                    />
                                    <div v-if="submitted && !$v.user.lastName.required" class="invalid-feedback">
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
                                                v-model="user.firstName"                                                    
                                                id="firstName" 
                                                name="firstName" 
                                                class="form-control form-control-sm" 
                                                :class="{ 'is-invalid' : submitted && $v.user.firstName.$error}"
                                                @blur='checkIsValid($v.user.firstName, $event)'
                                        />
                                        <div v-if="submitted && !$v.user.firstName.required" class="invalid-feedback">
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
                                    <label for="last_name" class="px-0 col-md-12 col-form-label"><span class="text-danger">*</span> Attribue <div class="float-right">:</div></label>
                                </div>
                                <div class="col-6">
                                    <select id="attribute" name="attribute"
                                        v-model="user.attribute"
                                        class="form-control form-control-sm" 
                                        :class="{ 'is-invalid' : submitted && $v.user.attribute.$error}"
                                        @blur='checkIsValid($v.user.attribute, $event)'
                                    >
                                        <option value="">-- Select --</option>
                                        <option value="TRIAL">Trial</option>
                                        <option value="MEMBER">Member</option>
                                        <option value="WITHDRAW">Withdraw</option>
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
                                            <input type="radio" v-model="user.gender" id="gender" name="gender" checked="checked" value="MALE" class="" :class="{ 'is-invalid': submitted && $v.user.gender.$error }" />
                                            <label for="gender" class="small col-2 px-0">Male</label>

                                            <input type="radio" v-model="user.gender" id="gender" name="gender" value="FEMALE" class="" :class="{ 'is-invalid': submitted && $v.user.gender.$error }" />
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
                                                v-model="user.communication_app"
                                                class="form-control form-control-sm" 
                                                :class="{ 'is-invalid': submitted && $v.user.communication_app.$error }"
                                                @blur='checkIsValid($v.user.communication_app, $event)'
                                            >
                                                <option value="">-- Select --</option>
                                                <option value="skype">Skype</option>
                                                <option value="zoom">Zoom</option>
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
                                    <input type="text" name="id" class="form-control form-control-sm bg-white" value="Auto Generated" readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="email-row" class="row pt-2">
                        <div class="col-6">
                            <div class="row">
                                <div class="col-4 small pr-0">
                                    <label for="email" class="px-0 col-md-12 col-form-label"><span class="text-danger">*</span> E-Mail Adress (Username) <div class="float-right">:</div></label>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">                                       
                                        <input type="text" v-model="user.email" id="email" name="email"  placeholder="E-mail Address"
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

                    <div id="password-row" class="row pt-2">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-2 small pr-0">
                                    <label for="password" class="px-0 col-md-12 col-form-label"><span class="text-danger">*</span> Password <div class="float-right">:</div></label>
                                </div>
                                <div class="col-6">
                                    <input type="text" v-model="user.password" id="password" name="password" class="col-4 form-control form-control-sm" 
                                        :class="{ 'is-invalid': submitted && $v.user.password.$error }" 
                                        @blur='checkIsValid($v.user.password, $event)'
                                    />
                                    <div v-if="submitted && !$v.user.password.required" class="invalid-feedback">
                                        Password is required
                                    </div>
                                    <div v-else-if="submitted && !$v.user.password.minLength" class="invalid-feedback" >
                                        Minimum Length of 6 characters for the password is required
                                    </div>                                         
                                                                                           
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="password-row" class="row pt-2">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-2 small pr-0">
                                    <label for="password" class="px-0 col-md-12 col-form-label"><span class="text-danger">*</span> Confirm Password <div class="float-right">:</div></label>
                                </div>
                                <div class="col-6">
                                    <input type="text" v-model="user.confirmPassword" id="confirmPassword" name="confirmPassword" class="col-4 form-control form-control-sm" 
                                        :class="{ 'is-invalid': submitted && $v.user.confirmPassword.$error }" 
                                        @blur='checkIsValid($v.user.confirmPassword, $event)'
                                    />
                                    <div v-if="submitted && !$v.user.confirmPassword.required" class="invalid-feedback">
                                        Confirm Password is required
                                    </div>
                                    <div v-if="submitted && !$v.user.confirmPassword.sameAsPassword" class="invalid-feedback">
                                        Confirmation password must be same with the password
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
                                    <label for="birthday" class="px-0 col-md-12 col-form-label"><span class="text-danger"> &nbsp;</span> Age <div class="float-right">:</div></label>
                                </div>
                                <div class="col-2">
                                    <input type="text" v-model="user.age" name="birthday" class="form-control form-control-sm" placeholder="">
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
                                    <select name="membership" class="form-control form-control-sm">
                                        <option value="">-- Select --</option>
                                        <option value="Point Balance">Point Balance</option>
                                        <option value="Monthly">Monthly</option>
                                        <option value="Both">Both</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!--[end] member information section-->

                <!--[start] Member Preferences -->
                <div id="preferred-tutor-section" class="section">
                    <div class="card-title bg-gray p-1 mt-4">
                        <div class="pl-2 font-weight-bold small">Preferred Tutor</div>
                    </div>
                    <div class="row pt-2">
                        <div class="col-12">

                            <div class="row">
                                <div class="col-2 small pr-0">
                                    <label for="purpose" class="p-0 col-md-12 col-form-label"><span class="text-danger">*</span> Purpose <div class="float-right">:</div></label>
                                </div>
                                <div class="col-8">

                                    <ul class="checkbox-options">
                                        <li>
                                            <input type="checkbox" ref="purposes" name="purposes" id="bilingual"  v-model="user.preference.purpose.bilingual"  value="BILINGUAL"> Take part in Bilingual training course                                            
                                        </li>
                                        
                                        <li>
                                            <input type="checkbox" ref="purposes" name="purposes" id="conversation" v-model="user.preference.purpose.conversation"  value="CONVERSATION">
                                            <label>Get conversation(communication) skill</label>

                                            <ul id="goalList" class="checkbox-options" v-if="user.preference.purpose.conversation">
                                                <li><input type="radio" name="goal" value="BEGINNER" checked=""> Beginner- easy daily conversation level</li>
                                                <li><input type="radio" name="goal" value="INTERMEDIATE"> Intermediate- Daily conversation level</li>
                                                <li><input type="radio" name="goal" value="ADVANCE"> Advance - Social, Environment, Business English</li>
                                                <li><input type="radio" name="goal" value="NATIVE"> Be native level</li>
                                            </ul>
                                            <input type="hidden" name="extraDetails" value="BEGINNER">
                                        </li>


                                        <li>
                                            <input type="checkbox" ref="purposes" name="purposes" id="antieken" v-model="user.preference.purpose.antieken"  value="ANTI_EIKEN">
                                            English certification exam in Japan
                                            <input type="text" name="extraDetails" v-if="user.preference.purpose.antieken" class="col-3 pl-1 form-control form-control-sm d-inline-block">
                                        </li>

                                        <li>
                                            <input type="checkbox" ref="purposes" name="purposes" id="antiexam" v-model="user.preference.purpose.antiexam"  value="ANTI_EXAM"> 
                                            Enter school
                                            <ul id="examLevel" v-if="user.preference.purpose.antiexam" style="list-style-type: none;">
                                                <li><input type="radio" name="antiExamLevel" value="JUNIOR_HIGH" checked=""> Junior High</li>
                                                <li><input type="radio" name="antiExamLevel" value="HIGHSCHOOL"> High school</li>
                                                <li><input type="radio" name="antiExamLevel" value="UNIVERSITY"> University</li>
                                            </ul>                                            
                                        </li>

                                        <li>
                                            <input type="checkbox" ref="purposes" name="purposes" id="toefl" v-model="user.preference.purpose.toefl" value="TOEFL" > 
                                            TOEFL(目標スコアー 点)
                                            <input type="text" name="extraDetails" v-if="user.preference.purpose.toefl" class="col-3 pl-1 form-control form-control-sm d-inline-block">
                                        </li>

                                        <li>
                                            <input type="checkbox" ref="purposes" name="purposes" id="toeic" v-model="user.preference.purpose.toeic" value="TOEIC">  
                                            TOEIC(目標スコアー 点)
                                            <input type="text" name="extraDetails" v-if="user.preference.purpose.toeic" class="col-3 pl-1 form-control form-control-sm d-inline-block">
                                        </li>

                                        <li>
                                            <input type="checkbox" ref="purposes" name="purposes" id="studyabroad" v-model="user.preference.purpose.studyabroad" value="STUDY_ABROAD"> Study Abroad
                                            <ul id="abroadLevel" style="list-style-type: none;"  v-if="user.preference.purpose.studyabroad">
                                                <li><input type="radio" name="studyAbroadLevel" value="JUNIOR_HIGH" checked=""> Junior High</li>
                                                <li><input type="radio" name="studyAbroadLevel" value="HIGHSCHOOL"> High school</li>
                                                <li><input type="radio" name="studyAbroadLevel" value="UNIVERSITY"> University</li>
                                            </ul>
                                        </li>


                                        <li>
                                            <input type="checkbox" ref="purposes" name="purposes" id="business" v-model="user.preference.purpose.business" value="BUSINESS"> Business English
                                            <input type="hidden" name="extraDetails" v-if="user.preference.purpose.business">
                                        </li>

                                        <li>
                                            <input type="checkbox" ref="purposes" name="purposes" id="others" v-model="user.preference.purpose.others" value="OTHERS"> Others 
                                            <textarea name="extraDetails" rows="2" cols="20" style="min-height: 20px; vertical-align: top;" class="col-3 pl-1 form-control form-control-sm d-inline-block" v-if="user.preference.purpose.others"></textarea>
                                        </li>
                                    </ul>

                                    <div v-if="submitted && !$v.user.purposes.required" class="invalid-feedback" style="display: block">
                                        Member Purpose is required, Please check at least one of the choices
                                    </div>
                                </div>

                            </div>


                            <!--[start] lesson class row -->
                            <div id="lesson-class-row" class="row pt-2">
                                <div class="col-2">
                                     <label for="agent" class="px-0 col-md-12 col-form-label"><span class="text-danger">&nbsp;</span>
                                    Lesson Class<div class="float-right">:</div></label>                                    
                                </div>
                                
                                <div  class="col-4">

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
                                            <select id="year" name="year" v-model="user.preference.lesson.class.year" class="form-control form-control-sm pl-0" >
                                                <option v-for="year in years" :value="year" :key="year">{{ year }}</option>
                                            </select>                                              
                                        </div>
                                        <div class="col-3 col-md-3 pr-0">
                                            <select id="month" name="month" v-model="user.preference.lesson.class.month" class="form-control form-control-sm pl-0">
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
                                            <input type="text"  v-model="user.preference.lesson.class.grade" class="form-control form-control-sm" />
                                        </div>                                        
                                        <div class="col-3 col-md-3 text-center">     
                                            <button class="btn btn-success btn-sm col-12" @click.prevent="addLessonClass()">Add</button>
                                        </div>                                        
                                    </div>

                                    <!--[start] enumaration of all added timeslot */-->
                                    <div class="row py-2 bg-lightgray border-bottom" v-for="lessonClass in user.preference.lessonClasses" :key="lessonClass.id" >
                                        <div class="col-3 col-md-3 text-center">
                                             {{ lessonClass.attribute }}                                     
                                        </div>
                                        <div year="col-3 col-md-3 text-center">
                                            {{ lessonClass.year }}
                                        </div>
                                        <div id="month" class="col-3 col-md-3 text-center">                                            
                                            {{ lessonClass.month }}
                                        </div>                                        
                                        <div class="col-3 col-md-3 text-center">     
                                            <input type="text" :value="lessonClass.grade" class="form-control form-control-sm d-inline-block" />
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
                                    <!--<input type="text" name="agent_id" class="form-control  form-control-sm">-->
                                    <datepicker 
                                        :language="ja"
                                        id="member_since" 
                                        name="member_since"
                                        :format="dateFormatter"
                                        :input-class="[ 'form-control form-control-sm ' /* , { 'is-invalid': submitted && $v.user.member_since.$error }*/]"                                           
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
                                    <select id="lessonshiftid" name="lessonshiftid" class="form-control form-control-sm">
                                        <option value="">-- Select --</option>
                                        <option value="25">25 mins</option>
                                        <option value="40">40 mins</option>
                                    </select>
                                </div>
                                <div v-if="submitted && !$v.user.lessonshiftid.required" class="invalid-feedback">
                                    Lesson Time is required
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
                                    <select id="maintutor" name="maintutor" class="form-control form-control-sm">
                                        <option value="">-- Select --</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!--[end] Lesson Details -->


                <!--current-->
                <div id="member-exam-records" class="section">
                    <div class="card-title bg-gray p-1 mt-4">
                        <div class="pl-2 font-weight-bold small">Exam Record</div>
                    </div>

                    <div class="row pt-2">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-2 small pr-0 pt-4">
                                    <label for="agent" class="px-0 col-md-12 col-form-label">
                                        <!--<span class="text-danger">*</span> -->
                                        TOEIC
                                        <div class="float-right">:</div>
                                    </label>
                                </div>
                                <div class="col-10">

                                    <div class="row">
                                        <div class="col-2 pr-0">
                                            <div class="text-center">Year</div>
                                            <select id="year" name="year" v-model="user.preference.lesson.toeic.year" class="form-control form-control-sm">
                                                <!--<option value="">-- Select --</option>-->
                                                <option v-for="year in years" :value="year" :key="year">{{ year }}</option>
                                            </select>
                                        </div>
                                        <div class="col-2 pr-0">
                                            <div class="text-center">Month</div>
                                            <select id="month" name="month" v-model="user.preference.lesson.toeic.month" class="form-control form-control-sm">
                                                <option value="">-- Select --</option>
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
                                        <div class="col-2 pr-0">
                                            <div class="text-center">Grade</div>
                                            <input id="toeicGrade" type="text" name="toeicGrade" v-model="user.preference.lesson.toeic.grade" class="form-control  form-control-sm d-inline-block">
                                        </div>
                                        <div class="col-6">
                                            <button class="btn btn-success btn-sm d-inline-block mt-4"  @click.prevent="addTOEIC()">Add</button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                      
                        <div class="col-6 offset-md-2">
                            <!--[start] enumaration of all TOEIC timeslot */-->
                            <div class="row py-2 bg-lightgray border-bottom" v-for="toeic in user.preference.toeicList" :key="toeic.id" >
                                <div id="year" class="col-3 col-md-3 text-center">
                                    {{ toeic.year }}
                                </div>
                                <div id="month" class="col-3 col-md-3 text-center">                                            
                                    {{ toeic.month }}
                                </div>                                        
                                <div class="col-3 col-md-3 text-center">     
                                    <input type="text" :value="toeic.grade" class="form-control form-control-sm d-inline-block" />
                                </div> 
                            </div> 
                        </div>

                    </div>

              

                    <div class="row pt-2">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-2 small pr-0">
                                    <label for="agent" class="px-0 col-md-12 col-form-label">
                                        <!--<span class="text-danger">*</span> !-->
                                        EIKEN
                                        <div class="float-right">:</div>
                                    </label>
                                </div>
                                <div class="col-10">

                                    <div class="row">
                                        <div class="col-2 pr-0">

                                            <select id="year" name="year" class="form-control form-control-sm">
                                                <!--<option value="">-- Select --</option>-->
                                                <option v-for="year in years" :value="year" :key="year">{{ year }}</option>
                                            </select>
                                        </div>
                                        <div class="col-2 pr-0">

                                            <select id="month" name="month" class="form-control form-control-sm">
                                                <option value="">-- Select --</option>
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
                                        <div class="col-2 pr-0">
                                            <input type="text" name="agent_id" class="form-control  form-control-sm d-inline-block">
                                        </div>
                                        <div class="col-6">
                                            <button class="btn btn-success btn-sm d-inline-block">Add</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div id="member-report-requirement" class="section">
                    <div class="card-title bg-gray p-1 mt-4">
                        <div class="pl-2 font-weight-bold small">Report Requirement</div>
                    </div>

                    <div class="row pt-2">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-2 small pr-0">
                                    <label for="agent" class="px-0 col-md-12 col-form-label"><span class="text-danger">*</span> Report Card<div class="float-right">:</div></label>
                                </div>
                                <div class="col-10">
                                    <div class="row">
                                        <div class="col-2 pr-0">
                                            <div class="text-center">Member</div>
                                            <select id="year" name="year" class="form-control form-control-sm">
                                                <option value="">-- Select --</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-2 pr-0">
                                            <div class="text-center">Agent</div>
                                            <select id="month" name="month" class="form-control form-control-sm">
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
                                    <label for="agent" class="px-0 col-md-12 col-form-label"><span class="text-danger">*</span> Report Card<div class="float-right">:</div></label>
                                </div>
                                <div class="col-10">
                                    <div class="row">
                                        <div class="col-2 pr-0">
                                            <select id="year" name="year" class="form-control form-control-sm">
                                                <option value="">-- Select --</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-2 pr-0">
                                            <select id="month" name="month" class="form-control form-control-sm">
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
                                            <select id="pointpurchase" name="pointpurchase" class="form-control form-control-sm">
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
                                            <select id="selectDay" class="form-control form-control-sm d-inline-block">
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
                                        <div class="col-2 pr-0">
                                            <input type="text" name="agent_id" class="form-control form-control-sm d-inline-block">
                                        </div>
                                        <div class="col-6">
                                            <button class="btn btn-success btn-sm d-inline-block">Add</button>
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
                        <button  class="btn btn-primary btn-sm">Save</button>
                        <button  class="btn btn-primary btn-sm">Cancel</button>
                    </div>
                </div>

            </form>

        </div>
        <!--[end] card body -->
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
    data() {
        return {
            submitted: false,
            ja: ja, //set calendar characters to japanese
            user: {
                firstName: "",
                lastName: "",
                attribute: "",
                email: "",
                nickname: "",
                gender: "",
                communication_app: "",
                communication_app_username: "",
                birthday: "",
                age: "",
                membership: "",
                password: "",
                confirmPassword: "",

                

                //Lesson Details - Section
                member_since: "",   

                lessonshiftid: "",

                //Preferred Tutor - Section
                preference: {                    
                    purpose: {
                        conversation: "",
                        antieken: "",
                        antiexam: "",
                        toefl: "",
                        toeic: "",
                        studyabroad: "",
                        business: "",
                        others: ""
                    },
                    //array list of purpose
                    purposes: [],
                    lesson: {
                        class: {
                            month:  "",
                            year:   "",
                            grade:  ""
                        },
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
                    lessonClasses: [],

                    toeicList: [],
                    eikenList: [],
                },

              
            },
            
        };
    },  
    validations: 
    {

        user: 
        {            
            firstName: { 
                required                
            },
            lastName: { 
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
            password: { required, minLength: minLength(6) },
            confirmPassword: { required, sameAsPassword: sameAs("password") },
            
            lessonshiftid: {
                required
            },

            purposes: {
                required
            }
        }

    },

    methods: {
        handleSubmit(e) {
            this.submitted = true;


            /*
            this.user.preference.purposes = document.querySelectorAll('input[name="purposes"]:checked');
            console.log(this.user.preference.purposes.length);
            this.user.preference.purposes.forEach(function (purpose) {
                console.log(purpose.value)
            })
            */

            // stop here if form is invalid            
            this.$v.$touch();

            if (this.$v.$invalid) {
                return;
            }

            console.log (this.submitted, this.$v.$invalid);

            alert("SUCCESS!! :-)\n\n" + JSON.stringify(this.user));
        },          
        checkIsValid (val, event) 
        {
            if (val.$anyError) 
            {
                console.log("shake!")
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
        birthDateFormatter(date) 
        {
            //format the date!
            let fdate           = this.dateFormatter(date);
            let age             = this.computeAge(date);

            //add the birthday and computed age back to user variable
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
            
            //get the difference from this current year to birthdate
            let birthDate = new Date(year + "/"+ month +"/" + day);
            let difference = currentDate - birthDate;
            let age = Math.floor(difference/31557600000);

            return age;
        },
        addLessonClass() 
        {
            //alert (this.user.preference.lesson.class.month + " " + this.user.preference.lesson.class.year + " " + this.user.preference.lesson.class.grade);
            if (this.user.attribute) {
                let year =  this.user.preference.lesson.class.year;
                let month = this.user.preference.lesson.class.month;
                let grade =  this.user.preference.lesson.class.grade;

                if (year && month && grade) {
                    const result =  this.user.preference.lessonClasses.find(item => item.year === this.user.preference.lesson.class.year && item.month === this.user.preference.lesson.class.month);
                    if (result) {
                        alert ("Selected item is already in the list");
                        return false;
                    } else {
                        this.user.preference.lessonClasses.push({                   
                            attribute: this.user.attribute,
                            year:  this.user.preference.lesson.class.year,
                            month: this.user.preference.lesson.class.month,
                            grade:  this.user.preference.lesson.class.grade
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
            alert ("toeic list!")
            let year =  this.user.preference.lesson.toeic.year;
            let month = this.user.preference.lesson.toeic.month;
            let grade =  this.user.preference.lesson.toeic.grade;

            console.log(year + " " + month, grade);

            if (year && month && grade) {            
                this.user.preference.toeicList.push({                                      
                    year:  this.user.preference.lesson.toeic.year,
                    month: this.user.preference.lesson.toeic.month,
                    grade:  this.user.preference.lesson.toeic.grade
                });            
            } else {
                alert ("Please enter TOEIC month, year and grade");
            }
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

<style scoped>
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
</style>