<template>
    <div class="jumbotron">
        <div class="container">
            <form @submit.prevent="handleSubmit">
                <div class="row">
                    <div class="col-sm-3 offset-sm-2">
                        <div>

                            <div class="form-group">
                                <label for="firstName">First Name</label>
                                <input type="text" v-model="user.firstName" id="firstName" name="firstName" class="form-control form-control-sm" :class="{ 'is-invalid' : submitted && $v.user.firstName.$error}"/>
                                <div v-if="submitted && !$v.user.firstName.required" class="invalid-feedback">
                                    First Name is required
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="lastName">Last Name</label>
                                <input type="text" v-model="user.lastName" id="lastName" name="lastName" class="form-control form-control-sm" 
                                    :class="{ 'is-invalid': submitted && $v.user.lastName.$error }" 
                                    @blur='checkIsValid($v.user.lastName, $event)'
                                />
                                <div v-if="submitted && !$v.user.lastName.required" class="invalid-feedback">
                                    Last Name is required
                                </div>
                            </div>

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
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" v-model="user.email" id="email" name="email" class="form-control form-control-sm" :class="{'is-invalid': submitted && $v.user.email.$error}"/>
                                <div v-if="submitted && $v.user.email.$error" class="invalid-feedback">
                                    <span v-if="!$v.user.email.required">Email is required</span>
                                    <span v-if="!$v.user.email.email">Email is invalid</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" v-model="user.password" id="password" name="password" class="form-control form-control-sm" :class="{'is-invalid' : submitted && $v.user.password.$error}"/>
                                <div v-if="submitted && $v.user.password.$error" class="invalid-feedback">
                                    <span v-if="!$v.user.password.required">Password is required</span>
                                    <span v-if="!$v.user.password.minLength">Password must be at least 6 characters</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="confirmPassword">Confirm Password</label>
                                <input type="password" v-model="user.confirmPassword" id="confirmPassword" name="confirmPassword" class="form-control form-control-sm" :class="{ 'is-invalid': submitted && $v.user.confirmPassword.$error }" />
                                <div v-if="submitted && $v.user.confirmPassword.$error" class="invalid-feedback">
                                    <span v-if="!$v.user.confirmPassword.required">Confirm Password is required</span>
                                    <span v-else-if="!$v.user.confirmPassword.sameAsPassword">Passwords must match</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary">
                                    Register
                                </button>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import Vuelidate from "vuelidate";

import { required, email, minLength, sameAs } from "vuelidate/lib/validators";

Vue.use(Vuelidate);

export default {
    name: "app",
    data() {
        return {
            user: {
                firstName: "",
                lastName: "",
                email: "",
                password: "",
                confirmPassword: ""
            },
            submitted: false
        };
    },
    validations: {
        user: {
            firstName: { required },
            lastName: { required },
            email: { required, email },
            password: { required, minLength: minLength(6) },
            confirmPassword: { required, sameAsPassword: sameAs("password") }
        }
    },
    methods: {
        handleSubmit(e) {
            this.submitted = true;

            // stop here if form is invalid
            this.$v.$touch();
            if (this.$v.$invalid) {
                return;
            }

            alert("SUCCESS!! :-)\n\n" + JSON.stringify(this.user));
        }
    }
};
</script>
