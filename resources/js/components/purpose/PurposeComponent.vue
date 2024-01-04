<template>

  <div id="PurposeComponent" class="mb-4">

    <div class="message"></div>
    
    <!--[start] Purpose (New)-->
    <div class="card-title bg-gray p-1">
        <div class="pl-2 font-weight-bold small">Member Purpose</div>                    
    </div>

    <div class="row">
        <div class="col-md-2">
                                            
        </div>
        <div class="col-md-8 small">
            <div class="row">
                <div class="col-md-2 mx-0 px-0">
                    <strong>Examination Type</strong>            
                </div>               
                <div class="col-md-2">
                        <strong>Target Score</strong>
                </div>
                <div class="col-md-6 text-center">
                        <strong>Target Score Selection</strong>
                </div>                    
            </div>
        </div>
    </div>
    
    <div class="row pt-2">
        <div class="col-2">
                                    
            <div class="pl-2 small">Select Purpose</div>
                
        </div>                
        <div class="col-10">     
            <div class="checkbox-options">
                <input type="checkbox" id="IELTS" name="IELTS" value="IELTS" class="main_option" @click="checkMainOption" v-model="childPurposeList.IELTS"> IELTS                               

                <div class="IELTS ml-4 sub_options">

                    <div class="row mt-2">
                        <div class="IELTS_option col-md-2">
                            <input type="checkbox" name="IELTS_option[]" value="Speaking"  v-model="childPurposeList.IELTS_option.Speaking"> Speaking                                 
                        </div>               
                        <div class="col-md-2">
                            {{ childPurposeList.IELTS_targetScore.Speaking }}
                        </div>
                        <div class="IELTS_targetScore col-md-6">                                    
                            <vue-slider  @change="clearErrorMsg" v-model="childPurposeList.IELTS_targetScore.Speaking" :min="3" :max="9"  :interval="0.5" :marks="[3, 9]"></vue-slider>                                     
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="IELTS_option col-md-2">
                            <input type="checkbox" name="IELTS_option[]" value="Writing"  v-model="childPurposeList.IELTS_option.Writing"> Writing                                 
                        </div>               
                        <div class="col-md-2">
                            {{ childPurposeList.IELTS_targetScore.Writing }}
                        </div>
                        <div class="IELTS_targetScore col-md-6">                                    
                            <vue-slider  @change="clearErrorMsg" v-model="childPurposeList.IELTS_targetScore.Writing" :min="3" :max="9"  :interval="0.5" :marks="[3, 9]"></vue-slider>                                     
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="IELTS_option col-md-2">
                            <input type="checkbox" name="IELTS_option[]" value="Reading"  v-model="childPurposeList.IELTS_option.Reading"> Reading                                 
                        </div>               
                        <div class="col-md-2">
                            {{ childPurposeList.IELTS_targetScore.Reading }}
                        </div>
                        <div class="IELTS_targetScore col-md-6">                                    
                            <vue-slider  @change="clearErrorMsg" v-model="childPurposeList.IELTS_targetScore.Reading" :min="3" :max="9"  :interval="0.5" :marks="[3, 9]"></vue-slider>                                     
                        </div>
                    </div>

                    <div class="row mt-4 mb-2">
                        <div class="IELTS_option col-md-2">
                            <input type="checkbox" name="IELTS_option[]" value="Listening"  v-model="childPurposeList.IELTS_option.Listening"> Listening                                 
                        </div>               
                        <div class="IELTS_option col-md-2">
                            {{ childPurposeList.IELTS_targetScore.Listening }}
                        </div>
                        <div class="IELTS_targetScore col-md-6">                                    
                            <vue-slider  @change="clearErrorMsg" v-model="childPurposeList.IELTS_targetScore.Listening" :min="3" :max="9"  :interval="0.5" :marks="[3, 9]"></vue-slider>                                     
                        </div>
                    </div>
                    
                </div>
            </div>    

            <div class="checkbox-options">
                <input type="checkbox" id="TOEFL" name="TOEFL" value="TOEFL" class="main_option" @click="checkMainOption" v-model="childPurposeList.TOEFL"> TOEFL IBT
                <div class="TOEFL ml-4 sub_options">

                    <div class="row mt-2">
                        <div class="TOEFL_option col-md-2">
                            <input type="checkbox" name="TOEFL_option[]" value="Speaking" v-model="childPurposeList.TOEFL_option.Speaking"> Speaking <br/>
                        </div>               
                        <div class="TOEFL_option col-md-2">
                            {{ childPurposeList.TOEFL_targetScore.Speaking }}
                        </div>
                        <div class="TOEFL_targetScore col-md-6">                                    
                            <vue-slider  @change="clearErrorMsg" v-model="childPurposeList.TOEFL_targetScore.Speaking" :min="0" :max="30"  :interval="1" :marks="[0, 30]"></vue-slider>                                     
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="TOEFL_option col-md-2">
                            <input type="checkbox" name="TOEFL_option[]" value="Writing" v-model="childPurposeList.TOEFL_option.Writing"> Writing <br/>
                        </div>               
                        <div class="TOEFL_option col-md-2">
                            {{ childPurposeList.TOEFL_targetScore.Writing }}
                        </div>
                        <div class="TOEFL_targetScore col-md-6">                                    
                            <vue-slider  @change="clearErrorMsg" v-model="childPurposeList.TOEFL_targetScore.Writing" :min="0" :max="30"  :interval="1" :marks="[0, 30]"></vue-slider>                                     
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="TOEFL_option col-md-2">
                            <input type="checkbox" name="TOEFL_option[]" value="Reading" v-model="childPurposeList.TOEFL_option.Reading"> Reading <br/>
                        </div>               
                        <div class="TOEFL_option col-md-2">
                            {{ childPurposeList.TOEFL_targetScore.Reading }}
                        </div>
                        <div class="TOEFL_targetScore col-md-6">                                    
                            <vue-slider  @change="clearErrorMsg" v-model="childPurposeList.TOEFL_targetScore.Reading" :min="0" :max="30"  :interval="1" :marks="[0, 30]"></vue-slider>                                     
                        </div>
                    </div>


                    <div class="row mt-4 mb-2">
                        <div class="IELTS_option col-md-2">
                            <input type="checkbox" name="TOEFL_option[]" value="Listening" v-model="childPurposeList.TOEFL_option.Listening"> Listening <br/>
                        </div>               
                        <div class="IELTS_option col-md-2">
                            {{ childPurposeList.TOEFL_targetScore.Listening }}
                        </div>
                        <div class="IELTS_targetScore col-md-6">                                    
                            <vue-slider  @change="clearErrorMsg" v-model="childPurposeList.TOEFL_targetScore.Listening" :min="0" :max="30"  :interval="1" :marks="[0, 30]"></vue-slider>                                     
                        </div>
                    </div>      

                </div>
            </div>


            <!-- [NEW] TOELF ITP -->
            <div class="checkbox-options">
                <input type="checkbox" id="TOEFL_ITP" name="TOEFL_ITP" value="TOEFL_ITP" class="main_option" @click="checkMainOption" v-model="childPurposeList.TOEFL_ITP"> TOEFL ITP
                <div class="TOEFL_ITP ml-4 sub_options">


                    <div class="row mt-4">
                        <div class="TOEFL_ITP_option col-md-2">
                            <input type="checkbox" name="TOEFL_ITP_option[]" value="Writing" v-model="childPurposeList.TOEFL_ITP_option.Writing"> 
                            Structure and Written Expression <br/>
                        </div>               
                        <div class="TOEFL_ITP_option col-md-2">
                            {{ childPurposeList.TOEFL_ITP_targetScore.Writing }}
                        </div>
                        <div class="TOEFL_ITP_targetScore col-md-6">                                    
                            <vue-slider  @change="clearErrorMsg" v-model="childPurposeList.TOEFL_ITP_targetScore.Writing" :min="31" :max="68"  :interval="1" :marks="[31, 68]"></vue-slider>                                     
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="TOEFL_ITP_option col-md-2">
                            <input type="checkbox" name="TOEFL_ITP_option[]" value="Reading" v-model="childPurposeList.TOEFL_ITP_option.Reading"> Reading <br/>
                        </div>               
                        <div class="TOEFL_ITP_option col-md-2">
                            {{ childPurposeList.TOEFL_ITP_targetScore.Reading }}
                        </div>
                        <div class="TOEFL_ITP_targetScore col-md-6">                                    
                            <vue-slider  @change="clearErrorMsg" v-model="childPurposeList.TOEFL_ITP_targetScore.Reading" :min="31" :max="67"  :interval="1" :marks="[31, 67]"></vue-slider>                                     
                        </div>
                    </div>


                    <div class="row mt-4 mb-2">
                        <div class="IELTS_option col-md-2">
                            <input type="checkbox" name="TOEFL_ITP_option[]" value="Listening" v-model="childPurposeList.TOEFL_ITP_option.Listening"> Listening <br/>
                        </div>               
                        <div class="IELTS_option col-md-2">
                            {{ childPurposeList.TOEFL_ITP_targetScore.Listening }}
                        </div>
                        <div class="IELTS_targetScore col-md-6">                                    
                            <vue-slider  @change="clearErrorMsg" v-model="childPurposeList.TOEFL_ITP_targetScore.Listening" :min="31" :max="68"  :interval="1" :marks="[31, 68]"></vue-slider>                                     
                        </div>
                    </div>      

                </div>
            </div>
            <!---[END] TOELF ITP-->      
            
   
               
            

            <!--- [START] TOEFL IUNIOR-->
            <div class="checkbox-options">
                <input type="checkbox" id="TOEFL_Junior" name="TOEFL_Junior" value="TOEFL_Junior" class="main_option" @click="checkMainOption" 
                v-model="childPurposeList.TOEFL_Junior"> TOEFL Junior

                <div class="TOEFL_Junior ml-4 sub_options">
                
                    <div class="row mt-4">
                        <div class="TOEFL_Junior_option col-md-2">
                            <input type="checkbox" name="TOEFL_Junior_option[]" value="Reading" v-model="childPurposeList.TOEFL_Junior_option.reading"> Reading <br/>
                        </div>               
                        <div class="TOEFL_Junior_option col-md-2">
                            {{ childPurposeList.TOEFL_Junior_targetScore.reading }}
                        </div>
                        <div class="TOEFL_Junior_targetScore col-md-6">                                    
                            <vue-slider  @change="clearErrorMsg" v-model="childPurposeList.TOEFL_Junior_targetScore.reading" :min="200" :max="300"  :interval="1" :marks="[200, 300]"></vue-slider>                                     
                        </div>
                    </div>


                    <div class="row mt-4">
                        <div class="TOEFL_Junior_option col-md-2">
                            <input type="checkbox" name="TOEFL_Junior_option[]" value="Writing" v-model="childPurposeList.TOEFL_Junior_option.languageFormAndMeaning"> Language Form & Meaning <br/>
                        </div>               
                        <div class="TOEFL_Junior_option col-md-2">
                            {{ childPurposeList.TOEFL_Junior_targetScore.languageFormAndMeaning }}
                        </div>
                        <div class="TOEFL_Junior_targetScore col-md-6">                                    
                            <vue-slider  @change="clearErrorMsg" v-model="childPurposeList.TOEFL_Junior_targetScore.languageFormAndMeaning" :min="200" :max="300"  :interval="1" :marks="[200, 300]"></vue-slider>                                     
                        </div>
                    </div>




                    <div class="row mt-4 mb-2">
                        <div class="IELTS_option col-md-2">
                            <input type="checkbox" name="TOEFL_Junior_option[]" value="Listening" v-model="childPurposeList.TOEFL_Junior_option.listening"> Listening <br/>
                        </div>               
                        <div class="IELTS_option col-md-2">
                            {{ childPurposeList.TOEFL_Junior_targetScore.listening }}
                        </div>
                        <div class="IELTS_targetScore col-md-6">                                    
                            <vue-slider  @change="clearErrorMsg" v-model="childPurposeList.TOEFL_Junior_targetScore.listening" :min="200" :max="300"  :interval="1" :marks="[200, 300]"></vue-slider>                                     
                        </div>
                    </div>      

                </div>
            </div>            

            <!--- [START] TOEFL PRIMARY STEP 1-->
            <div class="checkbox-options">
                <input type="checkbox" id="TOEFL_Primary_Step_1" name="TOEFL_Primary_Step_1" value="TOEFL Primary Step 1" class="main_option" @click="checkMainOption" v-model="childPurposeList.TOEFL_Primary_Step_1"> TOEFL Primary Step 1

                <div class="TOEFL ml-4 sub_options">

                    <div class="row mt-4">
                        <div class="TOEFL_option col-md-2">
                            <input type="checkbox" name="TOEFL_option[]" value="Reading" v-model="childPurposeList.TOEFL_Primary_Step_1_option.Reading"> Reading <br/>
                        </div>               
                        <div class="TOEFL_option col-md-2">
                            {{ childPurposeList.TOEFL_Primary_Step_1_targetScore.Reading }}
                        </div>
                        <div class="TOEFL_targetScore col-md-6">                                    
                            <vue-slider  @change="clearErrorMsg" v-model="childPurposeList.TOEFL_Primary_Step_1_targetScore.Reading" :min="0" :max="109"  :interval="1" :marks="[0, 109]"></vue-slider>                                     
                        </div>
                    </div>


                    <div class="row mt-4 mb-2">
                        <div class="IELTS_option col-md-2">
                            <input type="checkbox" name="TOEFL_option[]" value="Listening" v-model="childPurposeList.TOEFL_Primary_Step_1_option.Listening"> Listening <br/>
                        </div>               
                        <div class="IELTS_option col-md-2">
                            {{ childPurposeList.TOEFL_Primary_Step_1_targetScore.Listening }}
                        </div>
                        <div class="IELTS_targetScore col-md-6">                                    
                            <vue-slider  @change="clearErrorMsg" v-model="childPurposeList.TOEFL_Primary_Step_1_targetScore.Listening" :min="0" :max="109"  :interval="1" :marks="[0, 109]"></vue-slider>                                     
                        </div>
                    </div>      

                </div>

            </div>

            <!--- [START] TOEFL PRIMARY STEP 2-->
            <div class="checkbox-options">
                <input type="checkbox" id="TOEFL_Primary" name="TOEFL_Primary_Step_2" value="TOEFL Primary Step 2" class="main_option" @click="checkMainOption" v-model="childPurposeList.TOEFL_Primary_Step_2"> TOEFL Primary Step 2

                <div class="TOEFL ml-4 sub_options">

                    <div class="row mt-4">
                        <div class="TOEFL_option col-md-2">
                            <input type="checkbox" name="TOEFL_option[]" value="Reading" v-model="childPurposeList.TOEFL_Primary_Step_2_option.Reading"> Reading <br/>
                        </div>               
                        <div class="TOEFL_option col-md-2">
                            {{ childPurposeList.TOEFL_Primary_Step_2_targetScore.Reading }}
                        </div>
                        <div class="TOEFL_targetScore col-md-6">                                    
                            <vue-slider  @change="clearErrorMsg" v-model="childPurposeList.TOEFL_Primary_Step_2_targetScore.Reading" :min="0" :max="115"  :interval="1" :marks="[0, 115]"></vue-slider>                                     
                        </div>
                    </div>


                    <div class="row mt-4 mb-2">
                        <div class="IELTS_option col-md-2">
                            <input type="checkbox" name="TOEFL_option[]" value="Listening" v-model="childPurposeList.TOEFL_Primary_Step_2_option.Listening"> Listening <br/>
                        </div>               
                        <div class="IELTS_option col-md-2">
                            {{ childPurposeList.TOEFL_Primary_Step_2_targetScore.Listening }}
                        </div>
                        <div class="IELTS_targetScore col-md-6">                                    
                            <vue-slider  @change="clearErrorMsg" v-model="childPurposeList.TOEFL_Primary_Step_2_targetScore.Listening" :min="0" :max="115"  :interval="1" :marks="[0, 115]"></vue-slider>                                     
                        </div>
                    </div>      

                </div>

            </div>


            <div class="checkbox-options">
                <input type="checkbox" id="TOEIC" name="TOEIC" value="TOEIC" class="main_option" @click="checkMainOption"  v-model="childPurposeList.TOEIC"> TOEIC
                <div class="TOEIC ml-4 sub_options">

                    <div class="row mt-2">
                        <div class="TOEIC_option col-md-2">
                            <input type="checkbox" name="TOEIC_option[]" value="Speaking"  v-model="childPurposeList.TOEIC_option.Speaking"> Speaking                                 
                        </div>               
                        <div class="col-md-2">
                            {{ childPurposeList.TOEIC_targetScore.Speaking }}
                        </div>
                        <div class="TOEIC_targetScore col-md-6">                                    
                            <vue-slider  @change="clearErrorMsg" v-model="childPurposeList.TOEIC_targetScore.Speaking" :min="0" :max="200"  :interval="1" :marks="[0, 200]"></vue-slider>                                     
                        </div>
                    </div>
                    

                    <div class="row mt-4">
                        <div class="TOEIC_option col-md-2">
                            <input type="checkbox" name="TOEIC_option[]" value="Writing"  v-model="childPurposeList.TOEIC_option.Writing"> Writing                                 
                        </div>               
                        <div class="col-md-2">
                            {{ childPurposeList.TOEIC_targetScore.Writing }}
                        </div>
                        <div class="TOEIC_targetScore col-md-6">                                    
                            <vue-slider  @change="clearErrorMsg" v-model="childPurposeList.TOEIC_targetScore.Writing" :min="0" :max="495"  :interval="1" :marks="[0, 495]"></vue-slider>                                     
                        </div>
                    </div>      


                    <div class="row mt-4">
                        <div class="TOEIC_option col-md-2">
                            <input type="checkbox" name="TOEIC_option[]" value="Reading"  v-model="childPurposeList.TOEIC_option.Reading"> Reading                                 
                        </div>               
                        <div class="col-md-2">
                            {{ childPurposeList.TOEIC_targetScore.Reading }}
                        </div>
                        <div class="TOEIC_targetScore col-md-6">                                    
                            <vue-slider  @change="clearErrorMsg" v-model="childPurposeList.TOEIC_targetScore.Reading" :min="0" :max="495"  :interval="1" :marks="[0, 495]"></vue-slider>                                     
                        </div>
                    </div>    


                    <div class="row mt-4 mb-2">
                        <div class="TOEIC_option col-md-2">
                            <input type="checkbox" name="TOEIC_option[]" value="Listening"  v-model="childPurposeList.TOEIC_option.Listening"> Listening                                 
                        </div>               
                        <div class="col-md-2">
                            {{ childPurposeList.TOEIC_targetScore.Listening }}
                        </div>
                        <div class="TOEIC_targetScore col-md-6">                                    
                            <vue-slider  @change="clearErrorMsg" v-model="childPurposeList.TOEIC_targetScore.Listening" :min="0" :max="495"  :interval="1" :marks="[0, 495]"></vue-slider>                                     
                        </div>
                    </div>                                                                

                </div>
            </div>

            <div class="checkbox-options">
                <input type="checkbox" id="EIKEN" name="EIKEN" value="EIKEN" class="main_option" @click="checkMainOption" v-model="childPurposeList.EIKEN"> EIKEN(英検）
                <div class="EIKEN ml-4 sub_options">

                    <div class="row mt-2">
                        <div class="EIKEN_option col-md-2">
                            <input type="checkbox" name="EIKEN_option[]" value="Grade 5"  v-model="childPurposeList.EIKEN_option.Grade_5"> Grade 5                                 
                        </div>               
                        <div class="col-md-2">
                            {{ childPurposeList.EIKEN_targetScore.Grade_5 }}
                        </div>
                        <div class="EIKEN_targetScore col-md-6">                                    
                            <vue-slider  @change="clearErrorMsg" v-model="childPurposeList.EIKEN_targetScore.Grade_5" :min="0" :max="850"  :interval="1" :marks="[0, 850]"></vue-slider>                                     
                        </div>
                    </div>  


                    <div class="row mt-4">
                        <div class="EIKEN_option col-md-2">
                            <input type="checkbox" name="EIKEN_option[]" value="Grade 4"  v-model="childPurposeList.EIKEN_option.Grade_4"> Grade 4                                 
                        </div>               
                        <div class="col-md-2">
                            {{ childPurposeList.EIKEN_targetScore.Grade_4 }}
                        </div>
                        <div class="EIKEN_targetScore col-md-6">                                    
                            <vue-slider  @change="clearErrorMsg" v-model="childPurposeList.EIKEN_targetScore.Grade_4" :min="0" :max="1000"  :interval="1" :marks="[0, 1000]"></vue-slider>                                     
                        </div>
                    </div>


                    <div class="row mt-4">
                        <div class="EIKEN_option col-md-2">
                            <input type="checkbox" name="EIKEN_option[]" value="Grade 3"  v-model="childPurposeList.EIKEN_option.Grade_3"> Grade 3
                        </div>               
                        <div class="col-md-2">
                            {{ childPurposeList.EIKEN_targetScore.Grade_3 }}
                        </div>
                        <div class="EIKEN_targetScore col-md-6">                                    
                            <vue-slider  @change="clearErrorMsg" v-model="childPurposeList.EIKEN_targetScore.Grade_3" :min="0" :max="2200"  :interval="1" :marks="[0, 2200]"></vue-slider>                                     
                        </div>
                    </div>



                    <div class="row mt-4">
                        <div class="EIKEN_option col-md-2">
                            <input type="checkbox" name="EIKEN_option[]" value="Grade pre 2"  v-model="childPurposeList.EIKEN_option.Grade_pre_2"> Grade pre 2
                        </div>               
                        <div class="col-md-2">
                            {{ childPurposeList.EIKEN_targetScore.Grade_pre_2 }}
                        </div>
                        <div class="EIKEN_targetScore col-md-6">                                    
                            <vue-slider  @change="clearErrorMsg" v-model="childPurposeList.EIKEN_targetScore.Grade_pre_2" :min="0" :max="2400"  :interval="1" :marks="[0, 2400]"></vue-slider>                                     
                        </div>
                    </div>


                    <div class="row mt-4">
                        <div class="EIKEN_option col-md-2">
                            <input type="checkbox" name="EIKEN_option[]" value="Grade 2"  v-model="childPurposeList.EIKEN_option.Grade_2"> Grade 2 
                        </div>               
                        <div class="col-md-2">
                            {{ childPurposeList.EIKEN_targetScore.Grade_2 }}
                        </div>
                        <div class="EIKEN_targetScore col-md-6">                                    
                            <vue-slider  @change="clearErrorMsg" v-model="childPurposeList.EIKEN_targetScore.Grade_2" :min="0" :max="2600"  :interval="1" :marks="[0, 2600]"></vue-slider>                                     
                        </div>
                    </div>
                    
            


                    <div class="row mt-4">
                        <div class="EIKEN_option col-md-2">
                            <input type="checkbox" name="EIKEN_option[]" value="Grade pre 1"  v-model="childPurposeList.EIKEN_option.Grade_pre_1"> Grade pre 1
                        </div>               
                        <div class="col-md-2">
                            {{ childPurposeList.EIKEN_targetScore.Grade_pre_1 }}
                        </div>
                        <div class="EIKEN_targetScore col-md-6">                                    
                            <vue-slider  @change="clearErrorMsg" v-model="childPurposeList.EIKEN_targetScore.Grade_pre_1" :min="0" :max="3000"  :interval="1" :marks="[0, 3000]"></vue-slider>                                     
                        </div>
                    </div>


                    <div class="row mt-4 mb-2">
                        <div class="EIKEN_option col-md-2">
                            <input type="checkbox" name="EIKEN_option[]" value="Grade 1"  v-model="childPurposeList.EIKEN_option.Grade_1"> Grade 1
                        </div>               
                        <div class="col-md-2">
                            {{ childPurposeList.EIKEN_targetScore.Grade_1 }}
                        </div>
                        <div class="EIKEN_targetScore col-md-6">                                    
                            <vue-slider  @change="clearErrorMsg" v-model="childPurposeList.EIKEN_targetScore.Grade_1" :min="0" :max="3400"  :interval="1" :marks="[0, 3400]"></vue-slider>                                     
                        </div>
                    </div>
                    
                    
                </div>
            </div>

            

            <div class="checkbox-options">
                <input type="checkbox" id="TEAP" name="TEAP" value="TEAP" class="main_option" @click="checkMainOption" v-model="childPurposeList.TEAP"> TEAP
                <div class="TEAP ml-4 sub_options">
                    <div class="row mt-2">

                        <div class="TEAP_option col-md-2">
                            <input type="checkbox" name="TEAP_option[]" value="Speaking" v-model="childPurposeList.TEAP_option.Speaking"> Speaking <br/>
                        </div>               
                        <div class="col-md-2">
                            {{ childPurposeList.TEAP_targetScore.Speaking }}
                        </div>
                        <div class="TEAP_targetScore col-md-6">                                    
                            <vue-slider  @change="clearErrorMsg" v-model="childPurposeList.TEAP_targetScore.Speaking" :min="20" :max="100"  :interval="1" :marks="[20, 100]"></vue-slider>                                     
                        </div>
                    </div>


                    <div class="row mt-4 mb-2">
                        <div class="TEAP_option col-md-2">
                            <input type="checkbox" name="TEAP_option[]" value="Writing" v-model="childPurposeList.TEAP_option.Writing"> Writing <br/>                                     
                        </div>               
                        <div class="col-md-2">
                            {{ childPurposeList.TEAP_targetScore.Writing }}
                        </div>
                        <div class="TEAP_targetScore col-md-6">                                    
                            <vue-slider  @change="clearErrorMsg" v-model="childPurposeList.TEAP_targetScore.Writing" :min="20" :max="100"  :interval="1" :marks="[20, 100]"></vue-slider>                                     
                        </div>
                    </div>

                    <div class="row mt-4 mb-2">
                        <div class="TEAP_option col-md-2">
                            <input type="checkbox" name="TEAP_option[]" value="Writing" v-model="childPurposeList.TEAP_option.Reading"> Reading <br/>                                     
                        </div>               
                        <div class="col-md-2">
                            {{ childPurposeList.TEAP_targetScore.Reading }}
                        </div>
                        <div class="TEAP_targetScore col-md-6">                                    
                            <vue-slider  @change="clearErrorMsg" v-model="childPurposeList.TEAP_targetScore.Reading" :min="20" :max="100"  :interval="1" :marks="[20, 100]"></vue-slider>                                     
                        </div>
                    </div>

                    <div class="row mt-4 mb-2">
                        <div class="TEAP_option col-md-2">
                            <input type="checkbox" name="TEAP_option[]" value="Writing" v-model="childPurposeList.TEAP_option.Listening"> Listening <br/>                                     
                        </div>               
                        <div class="col-md-2">
                            {{ childPurposeList.TEAP_targetScore.Listening }}
                        </div>
                        <div class="TEAP_targetScore col-md-6">                                    
                            <vue-slider  @change="clearErrorMsg" v-model="childPurposeList.TEAP_targetScore.Listening" :min="20" :max="100"  :interval="1" :marks="[20, 100]"></vue-slider>                                     
                        </div>
                    </div>

                    
                </div>
            </div>      


            <div class="checkbox-options">
                <input type="checkbox" id="BUSINESS" name="BUSINESS" value="BUSINESS" class="main_option" @click="checkMainOption" v-model="childPurposeList.BUSINESS"> Business
                <div class="BUSINESS ml-4 sub_options">


                    <div class="row mt-2">
                        <div class="BUSINESS_option col-md-2">
                            <input type="checkbox" name="BUSINESS_option[]" value="Basic" v-model="childPurposeList.BUSINESS_option.Basic"> Basic <br/>
                        </div>               
                        <div class="col-md-2">
                            {{ childPurposeList.BUSINESS_targetScore.Basic }}
                        </div>
                        <div class="BUSINESS_targetScore col-md-6">                                    
                            <vue-slider  @change="clearErrorMsg" v-model="childPurposeList.BUSINESS_targetScore.Basic" :data="level_difficulty_data_select" :marks="true"></vue-slider>                   
                        </div>
                    </div>


                    <div class="row mt-4">
                        <div class="BUSINESS_option col-md-2">
                            <input type="checkbox" name="BUSINESS_option[]" value="Intermediate" v-model="childPurposeList.BUSINESS_option.Intermediate"> Intermediate <br/>
                        </div>               
                        <div class="col-md-2">
                            {{ childPurposeList.BUSINESS_targetScore.Intermediate }}
                        </div>
                        <div class="BUSINESS_targetScore col-md-6">                                    
                            <vue-slider  @change="clearErrorMsg" v-model="childPurposeList.BUSINESS_targetScore.Intermediate" :data="level_difficulty_data_select" :marks="true"></vue-slider>                   
                        </div>
                    </div>


                    <div class="row mt-4 mb-2">
                        <div class="BUSINESS_option col-md-2">
                            <input type="checkbox" name="BUSINESS_option[]" value="Advance" v-model="childPurposeList.BUSINESS_option.Advance"> Advance <br/>                                
                        </div>               
                        <div class="col-md-2">
                            {{ childPurposeList.BUSINESS_targetScore.Advance }}
                        </div>
                        <div class="BUSINESS_targetScore col-md-6">                                    
                            <vue-slider  @change="clearErrorMsg" v-model="childPurposeList.BUSINESS_targetScore.Advance" :data="level_difficulty_data_select" :marks="true"></vue-slider>                   
                        </div>
                    </div>

                </div>
            </div>                        

            <div class="checkbox-options">
                <input type="checkbox" id="BUSINESS_CAREERS" name="BUSINESS_CAREERS" value="BUSINESS CAREERS" class="main_option" @click="checkMainOption"  v-model="childPurposeList.BUSINESS_CAREERS"> Business Careers(職業別英語）

                <div class="BUSINESS_CAREERS ml-4 sub_options">

                    <div class="row mt-2">
                        <div class="BUSINESS_option col-md-2">
                            <input type="checkbox" name="BUSINESS_CAREERS_option[]" value="Medicine" v-model="childPurposeList.BUSINESS_CAREERS_option.Medicine"> Medicine <br/>
                        </div>               
                        <div class="col-md-2">
                            {{ childPurposeList.BUSINESS_CAREERS_targetScore.Medicine }}
                        </div>
                        <div class="BUSINESS_CAREERS_targetScore col-md-6">                                    
                            <vue-slider  @change="clearErrorMsg" v-model="childPurposeList.BUSINESS_CAREERS_targetScore.Medicine" :data="level_difficulty_data_select" :marks="true"></vue-slider>                   
                        </div>
                    </div>


                    <div class="row mt-4">
                        <div class="BUSINESS_option col-md-2">
                            <input type="checkbox" name="BUSINESS_CAREERS_option[]" value="Nursing" v-model="childPurposeList.BUSINESS_CAREERS_option.Nursing"> Nursing <br/>
                        </div>               
                        <div class="col-md-2">
                            {{ childPurposeList.BUSINESS_CAREERS_targetScore.Nursing }}
                        </div>
                        <div class="BUSINESS_CAREERS_targetScore col-md-6">                                    
                            <vue-slider  @change="clearErrorMsg" v-model="childPurposeList.BUSINESS_CAREERS_targetScore.Nursing" :data="level_difficulty_data_select" :marks="true"></vue-slider>                   
                        </div>
                    </div>                       

                    <div class="row mt-4">
                        <div class="BUSINESS_option col-md-2">
                            <input type="checkbox" name="BUSINESS_CAREERS_option[]" value="Pharmaceutical" v-model="childPurposeList.BUSINESS_CAREERS_option.Pharmaceutical"> Pharmaceutical <br/>        
                        </div>               
                        <div class="col-md-2">
                            {{ childPurposeList.BUSINESS_CAREERS_targetScore.Pharmaceutical }}
                        </div>
                        <div class="BUSINESS_CAREERS_targetScore col-md-6">                                    
                            <vue-slider  @change="clearErrorMsg" v-model="childPurposeList.BUSINESS_CAREERS_targetScore.Pharmaceutical" :data="level_difficulty_data_select" :marks="true"></vue-slider>                   
                        </div>
                    </div>


                    <div class="row mt-4">
                        <div class="BUSINESS_option col-md-2">
                            <input type="checkbox" name="BUSINESS_CAREERS_option[]" value="Accounting" v-model="childPurposeList.BUSINESS_CAREERS_option.Accounting"> Accounting <br/>                                
                        </div>               
                        <div class="col-md-2">
                            {{ childPurposeList.BUSINESS_CAREERS_targetScore.Accounting }}
                        </div>
                        <div class="BUSINESS_CAREERS_targetScore col-md-6">                                    
                            <vue-slider  @change="clearErrorMsg" v-model="childPurposeList.BUSINESS_CAREERS_targetScore.Accounting" :data="level_difficulty_data_select" :marks="true"></vue-slider>                   
                        </div>
                    </div>


                    <div class="row mt-4">
                        <div class="BUSINESS_option col-md-2">
                            <input type="checkbox" name="BUSINESS_CAREERS_option[]" value="Legal Professionals" v-model="childPurposeList.BUSINESS_CAREERS_option.Legal_Professionals"> Legal Professionals <br/>        
                        </div>               
                        <div class="col-md-2">
                            {{ childPurposeList.BUSINESS_CAREERS_targetScore.Legal_Professionals }}
                        </div>
                        <div class="BUSINESS_CAREERS_targetScore col-md-6">                                    
                            <vue-slider  @change="clearErrorMsg" v-model="childPurposeList.BUSINESS_CAREERS_targetScore.Legal_Professionals" :data="level_difficulty_data_select" :marks="true"></vue-slider>                   
                        </div>
                    </div>

                    
                    <div class="row mt-4">
                        <div class="BUSINESS_option col-md-2">
                            <input type="checkbox" name="BUSINESS_CAREERS_option[]" value="Finance" v-model="childPurposeList.BUSINESS_CAREERS_option.Finance"> Finance <br/>                                
                        </div>               
                        <div class="col-md-2">
                            {{ childPurposeList.BUSINESS_CAREERS_targetScore.Finance }}
                        </div>
                        <div class="BUSINESS_CAREERS_targetScore col-md-6">                                    
                            <vue-slider  @change="clearErrorMsg" v-model="childPurposeList.BUSINESS_CAREERS_targetScore.Finance" :data="level_difficulty_data_select" :marks="true"></vue-slider>                   
                        </div>
                    </div>


                    <div class="row mt-4">
                        <div class="BUSINESS_option col-md-2">
                            <input type="checkbox" name="BUSINESS_CAREERS_option[]" value="Technology" v-model="childPurposeList.BUSINESS_CAREERS_option.Technology"> Technology <br/>
                        </div>               
                        <div class="col-md-2">
                            {{ childPurposeList.BUSINESS_CAREERS_targetScore.Technology }}
                        </div>
                        <div class="BUSINESS_CAREERS_targetScore col-md-6">                                    
                            <vue-slider  @change="clearErrorMsg" v-model="childPurposeList.BUSINESS_CAREERS_targetScore.Technology" :data="level_difficulty_data_select" :marks="true"></vue-slider>                   
                        </div>
                    </div>

                    
                    <div class="row mt-4">
                        <div class="BUSINESS_option col-md-2">
                            <input type="checkbox" name="BUSINESS_CAREERS_option[]" value="Commerce" v-model="childPurposeList.BUSINESS_CAREERS_option.Commerce"> Commerce <br/>
                        </div>               
                        <div class="col-md-2">
                            {{ childPurposeList.BUSINESS_CAREERS_targetScore.Commerce }}
                        </div>
                        <div class="BUSINESS_CAREERS_targetScore col-md-6">                                    
                            <vue-slider  @change="clearErrorMsg" v-model="childPurposeList.BUSINESS_CAREERS_targetScore.Commerce" :data="level_difficulty_data_select" :marks="true"></vue-slider>                   
                        </div>
                    </div>


                    <div class="row mt-4">
                        <div class="BUSINESS_option col-md-2">
                            <input type="checkbox" name="BUSINESS_CAREERS_option[]" value="Tourism" v-model="childPurposeList.BUSINESS_CAREERS_option.Tourism"> Tourism <br/>
                        </div>               
                        <div class="col-md-2">
                            {{ childPurposeList.BUSINESS_CAREERS_targetScore.Tourism }}
                        </div>
                        <div class="BUSINESS_CAREERS_targetScore col-md-6">                                    
                            <vue-slider  @change="clearErrorMsg" v-model="childPurposeList.BUSINESS_CAREERS_targetScore.Tourism" :data="level_difficulty_data_select" :marks="true"></vue-slider>                   
                        </div>
                    </div>



                    <div class="row mt-4">
                        <div class="BUSINESS_option col-md-2">
                            <input type="checkbox" name="BUSINESS_CAREERS_option[]" value="Cabin Crew" v-model="childPurposeList.BUSINESS_CAREERS_option.Cabin_Crew"> Cabin Crew <br/>
                        </div>               
                        <div class="col-md-2">
                            {{ childPurposeList.BUSINESS_CAREERS_targetScore.Cabin_Crew }}
                        </div>
                        <div class="BUSINESS_CAREERS_targetScore col-md-6">                                    
                            <vue-slider  @change="clearErrorMsg" v-model="childPurposeList.BUSINESS_CAREERS_targetScore.Cabin_Crew" :data="level_difficulty_data_select" :marks="true"></vue-slider>                   
                        </div>
                    </div>

                    
                    <div class="row mt-4 mb-2">
                        <div class="BUSINESS_option col-md-2">
                            <input type="checkbox" name="BUSINESS_CAREERS_option[]" value="Marketing and Advertising" v-model="childPurposeList.BUSINESS_CAREERS_option.Marketing_and_Advertising"> Marketing and Advertising <br/>                                                                
                        </div>               
                        <div class="col-md-2">
                            {{ childPurposeList.BUSINESS_CAREERS_targetScore.Marketing_and_Advertising }}
                        </div>
                        <div class="BUSINESS_CAREERS_targetScore col-md-6">                                    
                            <vue-slider  @change="clearErrorMsg" v-model="childPurposeList.BUSINESS_CAREERS_targetScore.Marketing_and_Advertising" :data="level_difficulty_data_select" :marks="true"></vue-slider>                   
                        </div>
                    </div>                        
                    
                </div>
            </div>                        

            <div class="checkbox-options">
                <input type="checkbox" id="DAILY_CONVERSATION" name="DAILY_CONVERSATION" value="DAILY CONVERSATION" class="main_option" @click="checkMainOption"  v-model="childPurposeList.DAILY_CONVERSATION"> Daily Conversation
                <div class="DAILY_CONVERSATION ml-4 sub_options">
                    

                    <div class="row mt-2">
                        <div class="BUSINESS_option col-md-2">                                
                            <input type="checkbox" name="DAILY_CONVERSATION_option[]" value="Basic" v-model="childPurposeList.DAILY_CONVERSATION_option.Basic"> Basic <br/>
                        </div>               
                        <div class="col-md-2">
                            {{ childPurposeList.DAILY_CONVERSATION_targetScore.Basic }}
                        </div>
                        <div class="DAILY_CONVERSATION_targetScore col-md-6">                                    
                            <vue-slider  @change="clearErrorMsg" v-model="childPurposeList.DAILY_CONVERSATION_targetScore.Basic" :data="level_difficulty_data_select" :marks="true"></vue-slider>                   
                        </div>
                    </div>  

                    <div class="row mt-4">
                        <div class="BUSINESS_option col-md-2">                                                                
                            <input type="checkbox" name="DAILY_CONVERSATION_option[]" value="Intermediate" v-model="childPurposeList.DAILY_CONVERSATION_option.Intermediate"> Intermediate <br/>                                
                        </div>               
                        <div class="col-md-2">
                            {{ childPurposeList.DAILY_CONVERSATION_targetScore.Intermediate }}
                        </div>
                        <div class="DAILY_CONVERSATION_targetScore col-md-6">                                    
                            <vue-slider  @change="clearErrorMsg" v-model="childPurposeList.DAILY_CONVERSATION_targetScore.Intermediate" :data="level_difficulty_data_select" :marks="true"></vue-slider>                   
                        </div>
                    </div>  


                    <div class="row mt-4 mb-2">
                        <div class="BUSINESS_option col-md-2">                                                                
                            <input type="checkbox" name="DAILY_CONVERSATION_option[]" value="Advance" v-model="childPurposeList.DAILY_CONVERSATION_option.Advance"> Advance <br/>
                        </div>               
                        <div class="col-md-2">
                            {{ childPurposeList.DAILY_CONVERSATION_targetScore.Advance }}
                        </div>
                        <div class="DAILY_CONVERSATION_targetScore col-md-6">                                    
                            <vue-slider  @change="clearErrorMsg" v-model="childPurposeList.DAILY_CONVERSATION_targetScore.Advance" :data="level_difficulty_data_select" :marks="true"></vue-slider>                   
                        </div>
                    </div>  

                </div>
            </div>



            <div class="checkbox-options">
                <input type="checkbox" id="OTHERS" name="OTHERS" value="OTHERS" class="main_option" @click="checkMainOption"  v-model="childPurposeList.OTHERS"> Others
                <div class="others ml-4 sub_options">
                    <input type="text" name="OTHERS_value" value="OTHERS" class="col-md-8 form-control form-control-sm " v-model="childPurposeList.OTHERS_value">  
                </div>
            </div>                        

        </div>
    </div>
    <!--[end]-->

  </div>
</template>

<script>

import VueSlider from 'vue-slider-component'
import 'vue-slider-component/theme/antd.css'

const ERROR_TYPE = {
    VALUE: 1,
    INTERVAL: 2,
    MIN: 3,
    MAX: 4,
    ORDER: 5,
  }

export default {
    name: "PurposeComponent",
    components: {
    VueSlider
    },
    data() {
        return {
            errorMsg: '',
            //purpose List
            level_difficulty_data_select: ['Beginner', 'Intermediate', 'Advance'],
            childPurposeList: {
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

                //TOELF ITP
                TOEFL_ITP: "",
                TOEFL_ITP_option: {
                        //Speaking: "",
                        Writing: "",
                        Reading: "",
                        Listening: "",                
                },
                TOEFL_ITP_targetScore:
                {
                        //Speaking: 31,
                        Writing: 31,
                        Reading: 31,
                        Listening: 31,                        
                },                

                /* NEW TEOFL ADDITION */
                TOEFL_Junior: "",
                TOEFL_Junior_option: {                    
                    listening: "",
                    languageFormAndMeaning: "",
                    reading: "",
                    total: "",
                },
                TOEFL_Junior_targetScore: {                    
                    listening: 200,
                    languageFormAndMeaning: 200,
                    reading: 200
                },                
                                
                TOEFL_Primary_Step_1: "",
                TOEFL_Primary_Step_1_option: {  Listening: "",  Reading: "",},
                TOEFL_Primary_Step_1_targetScore: { Listening: 0, Reading: 0 },

                TOEFL_Primary_Step_2: "",
                TOEFL_Primary_Step_2_option: {  Listening: "",  Reading: "",},
                TOEFL_Primary_Step_2_targetScore: { Listening: 0, Reading: 0 },  

                /* NEW TEOFL ADDITION */
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
                        Speaking: 20,
                        Writing: 20,
                        Reading: 20,
                        Listening: 20,                        
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
                        Basic: "",
                        Intermediate: "",
                        Advance: "",                               
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
                DAILY_CONVERSATION: "",
                DAILY_CONVERSATION_option:
                {
                        Basic: "",
                        Intermediate: "",
                        Advance: "",
                },
                DAILY_CONVERSATION_targetScore:
                {
                        Basic: "",
                        Intermediate: "",
                        Advance: "",
                },
                OTHERS: "",
                OTHERS_value: "",
            },            
            
        };
    },
    props: {
        purposeList: Object
    },
    methods: {
        error(type, msg) {
            switch (type) {
            case ERROR_TYPE.MIN:
                break
            case ERROR_TYPE.MAX:
                break
            case ERROR_TYPE.VALUE:
                break
            }
            this.errorMsg = msg
        },
        showMsgModal(message) {
            this.boxTwo = '';
            this.$bvModal.msgBoxOk(message, {
                title: '告知',
                size: 'sm',
                buttonSize: 'sm',
                okVariant: 'success',
                headerClass: 'p-2 border-bottom-0',
                footerClass: 'p-2 border-top-0',
                centered: true
            }).then(value => {
                this.boxTwo = value
            })
            .catch(err => {
                // An error occurred
            })            
        },
        checkMainOption(event)
        {
            let mainOptionLength = $('input.main_option:checked').length;
            if (mainOptionLength > 3) {
                this.showMsgModal("受講目的--->最大3目的")
                event.preventDefault();
            } else {
                this.showMainMenuSubOptions() 
            }

        },
        showMainMenuSubOptions() 
        {
            let mainOption = document.getElementsByClassName("main_option");
            let mainOptionKeys = Object.keys(mainOption);

            mainOptionKeys.forEach(function (key) 
            {
                let element = mainOption[key];
                if (element.checked) {

                    if (element.nextElementSibling) {
                        element.nextElementSibling.style.display = 'block'; 
                    }
                  
                } else {
                    if (element.nextElementSibling) {
                        element.nextElementSibling.style.display = 'none';
                    }
                }
            });
            this.showSubMenu();         
        },
        showSubMenu() 
        {
            let subOption = document.getElementsByClassName("sub_options");
            Object.keys(subOption).forEach(function (key) 
            {              
                let element = subOption[key];
                let inputElements = element.querySelectorAll('input');
                Object.keys(inputElements).forEach(function (inputElementkey) 
                {
                    let subElement = inputElements[inputElementkey];
                    
                    if (subElement.checked) {
                        subElement.parentElement.nextElementSibling.style.display = 'block';   
                        subElement.parentElement.nextElementSibling.nextElementSibling.style.display = 'block';
                    } else {

                        if (subElement.parentElement.nextElementSibling) {
                            subElement.parentElement.nextElementSibling.style.display = 'none';   
                            subElement.parentElement.nextElementSibling.nextElementSibling.style.display = 'none';                            
                        }
                    }

                });

            });
        },
        clearErrorMsg() {
            this.errorMsg = ''
        },        
    },
    computed: {},
    updated: function () 
    {
        this.$parent.purposeList = this.childPurposeList;
        this.showMainMenuSubOptions();


    },
    mounted: function () 
    {
        this.childPurposeList = this.$props.purposeList;
        console.log(this.purposeList);


    },
};
</script>
<style scoped>
    .sub_options {
        display: none;
    }      
</style>