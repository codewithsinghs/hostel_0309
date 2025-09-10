<template>
    <div class="signup-content">
        <!-- Left -->

        <div class="signup-img">
            <div class="signup-img-content">
                <h2>RNTU Hostel</h2>
                <p>Student Registration</p>
            </div>
        </div>

        <!-- Right -->

        <div class="signup-form p-4">
            <div class="mobile-heading h2">RNTU Hostel registration</div>

            <!-- ALerts Start  -->
            <div id="errorMessage" class="alert alert-danger d-none" role="alert">
                <strong>Error!</strong>
                <span id="errorMessageText">Something went wrong. Please try again.</span>
            </div>

            <div id="registrationSuccessContainer" class="alert alert-success d-none text-center">
                <h4 class="alert-heading">Guest registered successfully!</h4>
                <p>Thank you!</p>
            </div>

            <div id="approvalMessageContainer" class="alert alert-info d-none text-center">
                Your registration is awaiting admin approval. Keep checking for
                updates.
            </div>
            <!-- ALerts End -->

            <!-- Form Start-->
            <form id="registrationForm" novalidate>
                <!-- token -->

                <!-- Profile Information -->
                <section class="form-box">
                    <!-- Heading -->
                    <div class="form-header">
                        <h3>Profile Information</h3>
                        <!-- <button class="edit-btn">Edit</button> -->
                    </div>

                    <!-- Form-grid -->
                    <div class="form-grid">
                        <!-- Scholar Number -->
                        <div class="form-field">
                            <label for="scholar_number">
                                Scholar Number
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="scholar_number" id="scholar_number" placeholder="***********" class="form-control" value="012345" />
                            <div id="scholar_numberError" class="invalid-feedback"></div>
                        </div>

                        <!-- Full Name -->
                        <div class="form-field">
                            <label for="name">Full Name
                                <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" aria-describedby="nameError" required
                                placeholder="Valid Input" class="form-control" value="Naman" />
                            <div id="nameError" class="invalid-feedback"></div>
                        </div>

                        <!-- Email -->
                        <div class="form-field">
                            <label for="email">Email Address
                                <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="email" aria-describedby="emailError" required
                                placeholder="name@gmail.com" class="form-control" value="test@gmail.com"/>
                            <div id="emailError" class="invalid-feedback"></div>
                        </div>

                        <!-- Mobile Number -->
                        <div class="form-field">
                            <label for="mobile">Mobile Number
                                <span class="text-danger">*</span></label>
                            <input type="text" name="mobile" id="mobile" pattern="[0-9]{10}" class="form-control"
                                aria-describedby="mobileError" required placeholder="99999-99999" value="9874563210"/>
                            <div id="mobileError" class="invalid-feedback"></div>
                        </div>

                        <!-- Faculty -->
                        <div class="form-field">
                            <label for="faculty_id">
                                Select Faculty <span class="text-danger">*</span>
                            </label>
                            <select name="faculty_id" id="faculty_id" v-model="facultyId" class="form-select" required
                                aria-describedby="faculty_idError" @change="onFacultyChange">
                                <option value="">Select Faculty</option>
                                <option v-for="fac in faculties" :key="fac.id" :value="fac.id">
                                    {{ fac.name }}
                                </option>
                            </select>
                            <div id="faculty_idError" class="invalid-feedback"></div>
                        </div>


                        <!-- Department -->
                        <div class="form-field">
                            <label for="department_id">Select Department
                                <span class="text-danger">*</span></label>
                            <select name="department_id" id="department_id" aria-describedby="department_idError" class="form-select">
                                <option value="">Select Department</option>
                            </select>
                            <div id="department_idError" class="invalid-feedback"></div>
                        </div>

                        <!-- Course -->
                        <div class="form-field">
                            <label for="course_id">Select Course
                                <span class="text-danger">*</span></label>
                            <select name="course_id" id="course_id" aria-describedby="course_idError" class="form-select">
                                <option value="">Select Course</option>
                            </select>
                            <div id="course_idError" class="invalid-feedback"></div>
                        </div>

                        <!-- Gender -->
                        <div class="form-field">
                            <label for="gender">Select gender
                                <span class="text-danger">*</span></label>
                            <select name="gender" id="gender" required aria-describedby="genderError" class="form-select">
                                <option value="">Select gender</option>
                                <option selected value="Male">Male</option>
                                <option value="Female">Female</option>
                                <!-- <option value="Other">Other</option> -->
                            </select>
                            <div id="genderError" class="invalid-feedback"></div>
                        </div>
                    </div>
                </section>
                <!-- Profile Information End -->

                <!-- Family Details Start -->
                <section class="form-box">
                    <!-- Heading -->
                    <div class="form-header">
                        <h3>Family Details</h3>
                        <!-- <button class="edit-btn">Edit</button> -->
                    </div>

                    <!-- Form-grid -->
                    <div class="form-grid">
                        <!-- Fathers Name -->
                        <div class="form-field">
                            <label for="fathers_name">Father's Name
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="fathers_name" id="fathers_name" required
                                aria-describedby="fathersNameError" placeholder="Father's Name" class="form-control" value="Rahul"/>
                            <div id="fatherNameError" class="invalid-feedback"></div>
                        </div>

                        <!-- Full Name -->
                        <div class="form-field">
                            <label for="mothers_name">Mother's Name
                                <span class="text-danger">*</span></label>
                            <input type="text" name="mothers_name" id="mothers_name" aria-describedby="mothersNameError"
                                required placeholder="Mother's Name" class="form-control" value="Rashmi"/>
                            <div id="motherNameError" class="invalid-feedback"></div>
                        </div>

                        <!-- Parent's Contact Number -->
                        <div class="form-field">
                            <label for="parent_contact">Parent's Contact Number
                                <span class="text-danger">*</span></label>
                            <input type="text" name="parent_contact" id="parent_contact"
                                aria-describedby="parent_contactError" required placeholder="99999-99999" class="form-control" value="9874563210"/>
                            <div id="parent_contactError" class="invalid-feedback"></div>
                        </div>

                        <!-- Local Guardian Name -->
                        <div class="form-field">
                            <label for="local_guardian_name">Local Guardian Name
                                <span class="text-danger">*</span></label>
                            <input type="text" name="local_guardian_name" id="local_guardian_name"
                                aria-describedby="localGuardianNameError" required placeholder="Valid Input" class="form-control" value="Arpit"/>
                            <div id="localGuardianNameError" class="invalid-feedback"></div>
                        </div>

                        <!-- Local Guardian's Contact Number -->
                        <div class="form-field">
                            <label for="guardian_contact">Local Guardian's Contact Number
                                <span class="text-danger">*</span></label>
                            <input type="text" name="guardian_contact" id="guardian_contact"
                                aria-describedby="guardian_contactError" required placeholder="99999-99999"  class="form-control" value="9874563210"/>
                            <div id="guardian_contactError" class="invalid-feedback"></div>
                        </div>

                        <!-- Emergency Contact Number -->
                        <div class="form-field">
                            <label for="emergency_contact">Emergency Contact Number
                                <span class="text-danger">*</span></label>
                            <input type="text" name="emergency_contact" id="emergency_contact"
                                aria-describedby="emergency_contactError" required placeholder="99999-99999" class="form-control" value="9874563210"/>
                            <div id="emergency_contactError" class="invalid-feedback"></div>
                        </div>
                    </div>
                </section>
                <!-- Family Details End -->



                <!-- Accessories Start -->
                <section class="form-box">
                    <!-- Heading -->
                    <div class="form-header">
                        <h3>Accessories</h3>
                        <!-- <button class="edit-btn">Edit</button> -->
                    </div>

                    <!-- Optional Add-ons  Final Working -->
                    <div class="my-1">
                        <label class="form-label fs-5">Optional Add-on Accessories</label>
                        <div class="border rounded p-3 bg-light d-flex flex-wrap gap-4" id="additional-accessories">
                            <div v-for="acc in additionalAccessories" :key="acc.id"
                                class="form-check border rounded p-3 mb-2 bg-white shadow-sm flex-grow-1 d-flex align-items-center"
                                style="margin-right: 30px;">
                                <input class="form-check-input fs-5 me-3" type="checkbox" :value="acc.id"
                                    v-model="selectedAccessories" name="accessories[]" :id="`accessory-${acc.id}`" />
                                <label class="form-check-label w-100" :for="`accessory-${acc.id}`"
                                    style="font-size:12px;">
                                    <strong>{{ acc.name }}</strong>
                                    <span class="text-muted"> (₹ {{ acc.price.toFixed(2) }} -/month) </span>
                                </label>
                            </div>
                        </div>
                    </div>

                </section>

                <!-- Preferences Start -->
                <section class="form-box">
                    <!-- Heading -->
                    <!-- <div class="form-header">
                        <h3>Preferences</h3>
                    </div> -->

                    <!-- Form-grid -->
                    <div class="row">


                        <div class="col-md-6">
                            <div class="form-header">
                                <h3>Preferences</h3>
                            </div>

                            <!-- Complimentary Accessories -->
                            <div class="form-field">
                                <label for="months">Select Stay Duration <span class="text-danger">*</span></label>
                                <select v-model="months" name="months" id="months" aria-describedby="monthsError">
                                    <option value="">Select Duration</option>
                                    <option value="1">Temporary (1 Month)</option>
                                    <option value="3">Regular (3 Months)</option>
                                    <option value="6">Half Year (6 Months)</option>
                                    <option value="12">Yearly (12 Months)</option>
                                </select>
                                <div id="monthsError" class="invalid-feedback"></div>
                            </div>
                            <!-- Complimentary Accessories -->

                            <!-- Complimentary Accessories -->
                            <div class="mb-3 mt-4">
                                <label class="fs-4">Complimentary Accessories</label>
                                <div class="border rounded p-3 bg-light d-flex flex-wrap gap-2" id="default-accessories"
                                    style="display: flex;">
                                    <template v-if="loadingAccessories">
                                        <p class="text-muted mb-0">Fetching complimentary accessories...</p>
                                    </template>
                                    <template v-else-if="errorAccessories">
                                        <p class="text-danger mb-0">{{ errorAccessories }}</p>
                                    </template>
                                    <template v-else-if="!defaultAccessories.length">
                                        <p class="text-muted mb-0">No complimentary accessories available.</p>
                                    </template>
                                    <template v-else>
                                        <div v-for="acc in defaultAccessories" :key="acc.id"
                                            class="border rounded p-2 mb-2 bg-white shadow-sm text-sm"
                                            style="font-size:12px;">
                                            <i class="bi bi-gift-fill text-success me-2"></i>
                                            <strong>{{ acc.name }}</strong>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                        <!-- Complimentary Accessories -->

                        <!-- Fee Breakups -->
                        <div class="col-md-6">
                            <div v-if="feeBreakup" class="card shadow-sm mt-4">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">Fee Breakup ({{ feeBreakup.stay_duration }})</h5>
                                </div>
                                <div class="card-body p-0">
                                    <table class="table table-bordered mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Fee Component</th>
                                                <th>Stay Duration</th>
                                                <th class="text-end">Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Base Fees -->
                                            <tr v-for="fee in feeBreakup.base_fees" :key="fee.name">
                                                <td>{{ fee.name }}</td>
                                                <td>{{ feeBreakup.stay_duration }}</td>
                                                <td class="text-end">₹ {{ parseFloat(fee.amount).toFixed(2) }}</td>
                                            </tr>

                                            <!-- One-time Fees -->
                                            <tr v-for="fee in feeBreakup.one_time_fees" :key="fee.name"
                                                class="text-primary">
                                                <td>{{ fee.name }} (One-time)</td>
                                                <td>One-time</td>
                                                <td class="text-end">₹ {{ parseFloat(fee.amount).toFixed(2) }}</td>
                                            </tr>

                                            <!-- Accessories -->
                                            <tr v-for="acc in feeBreakup.accessories" :key="acc.name"
                                                class="text-muted">
                                                <td>{{ acc.name }} (Add-on)</td>
                                                <td>{{ feeBreakup.stay_duration }}</td>
                                                <td class="text-end">₹ {{ parseFloat(acc.amount).toFixed(2) }}</td>
                                            </tr>

                                            <!-- Discount -->
                                            <tr v-if="feeBreakup.discount" class="table-success">
                                                <td colspan="2">
                                                    <strong>{{ feeBreakup.discount.type }}</strong> ({{
                                                        feeBreakup.discount.percentage }}% Off)
                                                </td>
                                                <td class="text-end">₹ {{
                                                    parseFloat(feeBreakup.discount.amount).toFixed(2) }}
                                                </td>
                                            </tr>
                                        </tbody>
                                        <tfoot class="table-dark text-white">
                                            <tr>
                                                <td colspan="2" class="text-end fw-bold">Total Payable</td>
                                                <td class="text-end fw-bold">₹ {{
                                                    parseFloat(feeBreakup.total).toFixed(2) }}
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>


                        </div>
                        <!-- Fee BreakUps -->



                    </div>

                    <!-- Agreement -->
                    <div class="form-check my-2">
                        <input class="form-check-input fs-4 fw-semibold" type="checkbox" id="agree" name="agree" />
                        <label class="form-check-label fs-4" for="agree">
                            I agree to the terms and conditions
                        </label>
                        <div id="agreeError" class="invalid-feedback"></div>
                    </div>

                </section>
                <!-- Preferences End -->



                <!-- Submit Btn -->
                <section class="submit-btn-registration">
                    <button type="submit" class="edit-btn" id="submitBtn">
                        Register
                    </button>
                    <div id="loading" class="mt-3 text-center text-muted d-none">
                        Submitting...
                    </div>
                </section>
            </form>
        </div>
    </div>
</template>

<script setup>
import { onMounted, ref, watch, computed } from "vue";

import axios from "axios";
import { useRouter } from "vue-router";
const router = useRouter();

const apiBase = "/api";

const faculties = ref([]); // all faculties list
const facultyId = ref(""); // selected faculty

// const durationMonths = ref(1); // user-selected duration
const months = ref("");                 // bound to your months select
// const baseFees = ref([]);               // backend fee breakup
let accessories = ref([]);            // fetched accessories
const selectedAccessories = ref([]);    // v-model for checkboxes

// accessories state
const defaultAccessories = ref([]);
const additionalAccessories = ref([]);
const defaultAccessoryHeadIds = ref([]);
const loadingAccessories = ref(false);
const errorAccessories = ref(null);

const feeBreakup = ref(null);
const errorFee = ref(null);

// watcher to trigger calculation when relevant fields change
watch(
    [facultyId, months, selectedAccessories],
    async ([newFaculty, newMonths, newAccessories]) => {
        if (!newFaculty || !newMonths) {
            feeBreakup.value = null;
            return;
        }

        const accessoriesArray = [...newAccessories]; // or newAccessories.slice()
        console.log(newFaculty, newMonths, accessoriesArray);
        try {
            const res = await axios.post("/api/fee/calculate", {
                faculty_id: newFaculty,
                months: newMonths,
                accessories: newAccessories,
            });
            console.log(res);
            feeBreakup.value = res.data.data;
            console.log(feeBreakup.value);
            errorFee.value = null;
        } catch (err) {
            feeBreakup.value = null;
            errorFee.value = "Unable to calculate fee. Please try again.";
        }
    }
);

// async function calculateFee(formData) {
//     try {
//         const res = await axios.post("/api/fee/calculate", formData);
//         feeBreakup.value = res.data.data;
//     } catch (err) {
//         errorFee.value = "Unable to calculate fee. Please try again.";
//     }
// }

// Load accessories for selected faculty
async function loadAccessories(faculty) {
    if (!faculty) {
        defaultAccessories.value = [];
        additionalAccessories.value = [];
        return;
    }

    loadingAccessories.value = true;
    errorAccessories.value = null;
    defaultAccessories.value = [];
    additionalAccessories.value = [];
    defaultAccessoryHeadIds.value = [];

    try {
        const response = await axios.get(`${apiBase}/accessories/active/${faculty}`);
        const accessories = response.data.data || [];

        accessories.forEach((acc) => {
            const id = acc.accessory_head.id;
            const name = acc.accessory_head.name;
            const price = parseFloat(acc.price);

            if (price === 0) {
                defaultAccessoryHeadIds.value.push(id);
                defaultAccessories.value.push({ id, name });
            } else {
                additionalAccessories.value.push({ id, name, price });
            }
        });
    } catch (err) {
        errorAccessories.value = "Unable to load accessories. Please try again.";
    } finally {
        loadingAccessories.value = false;
    }
}

// Watch faculty change → reload accessories
function onFacultyChange(e) {
    facultyId.value = e.target.value;
    // if (newVal) {
    // loadFees(facultyId.value);
    //     loadAccessories(newVal);
    //   }
    loadAccessories(facultyId.value);
}

// Validation rules
const rules = {
    scholar_number: {
        required: true,
        pattern: /^[a-zA-Z0-9]+$/,
        message: "Enter valid Scholar Number",
    },
    name: { required: true, message: "Full Name is required" },
    email: {
        required: true,
        pattern: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
        message: "Enter a valid email address",
    },
    mobile: {
        required: true,
        pattern: /^[0-9]{10}$/,
        message: "Enter a valid 10-digit mobile number",
    },
    faculty_id: { required: true, message: "Select a faculty" },
    department_id: { required: true, message: "Select a department" },
    course_id: { required: true, message: "Select a course" },
    gender: { required: true, message: "Select a gender" },
    fee_waiver: { required: false },
    attachment: { required: false },
    fathers_name: { required: true, message: "Father's Name is required" },
    mothers_name: { required: true, message: "Mother's Name is required" },
    parent_contact: { required: true, message: "Parent Contact is required" },
    local_guardian_name: {
        required: true,
        message: "Local Guardian Name is required",
    },
    guardian_contact: {
        required: true,
        message: "Guardian Contact is required",
    },
    emergency_contact: {
        required: true,
        message: "Emergency Contact is required",
    },
    Food_id: { required: true, message: "Select Food Preference" },
    Bed_id: { required: true, message: "Select Bed Preference" },
    months: { required: true, message: "Select Stay Duration" },
    agree: { required: true, message: "You must agree to the terms" },
};

function showError(field, message) {
    const el = document.getElementById(field);
    const errorDiv = document.getElementById(`${field}Error`);
    if (errorDiv) errorDiv.textContent = message || "";
    if (el) {
        if (message) el.classList.add("is-invalid");
        else el.classList.remove("is-invalid");
    }
}

function clearErrors() {
    Object.keys(rules).forEach((field) => showError(field, ""));
}

function scrollToFirstError() {
    const firstErrorEl = document.querySelector(".is-invalid");
    if (firstErrorEl)
        firstErrorEl.scrollIntoView({ behavior: "smooth", block: "center" });
}

// Mounted
onMounted(() => {
    const form = document.getElementById("registrationForm");
    if (!form) return;

    const submitBtn = document.getElementById("submitBtn");
    const loading = document.getElementById("loading");
    const successContainer = document.getElementById(
        "registrationSuccessContainer"
    );
    const errorMessage = document.getElementById("errorMessage");
    const errorMessageText = document.getElementById("errorMessageText");

    const facultySelect = document.getElementById("faculty_id");
    const deptSelect = document.getElementById("department_id");
    const courseSelect = document.getElementById("course_id");

    // If faculty is prefilled (edit form), load accessories
    if (facultyId.value) {
        loadAccessories(facultyId.value);
    }
    // ---- Dynamic Dropdowns ----
    const loadFaculties = async () => {
        if (!facultySelect) return;
        facultySelect.innerHTML = `<option value="">Loading...</option>`;
        try {
            const response = await axios.get(`${apiBase}/faculties/active`);
            const faculties = response.data.data || [];
            facultySelect.innerHTML = `<option value="">Select Faculty</option>`;
            faculties.forEach((f) => {
                const opt = document.createElement("option");
                opt.value = f.id;
                opt.textContent = f.name;
                facultySelect.appendChild(opt);
            });
        } catch {
            facultySelect.innerHTML = `<option value="">Error loading faculties</option>`;
        }
    };
    loadFaculties();

    if (facultySelect && deptSelect) {
        facultySelect.addEventListener("change", async () => {
            deptSelect.innerHTML = `<option value="">Loading...</option>`;
            courseSelect.innerHTML = `<option value="">Select Course</option>`;
            if (!facultySelect.value) {
                deptSelect.innerHTML = `<option value="">Select Department</option>`;
                return;
            }
            try {
                const response = await axios.get(
                    `${apiBase}/faculties/${facultySelect.value}/departments`
                );
                const departments = response.data.data || [];
                deptSelect.innerHTML = `<option value="">Select Department</option>`;
                departments.forEach((d) => {
                    const opt = document.createElement("option");
                    opt.value = d.id;
                    opt.textContent = d.name;
                    deptSelect.appendChild(opt);
                });
            } catch {
                deptSelect.innerHTML = `<option value="">Error loading departments</option>`;
            }
        });
    }

    if (deptSelect && courseSelect) {
        deptSelect.addEventListener("change", async () => {
            courseSelect.innerHTML = `<option value="">Loading...</option>`;
            if (!deptSelect.value) {
                courseSelect.innerHTML = `<option value="">Select Course</option>`;
                return;
            }
            try {
                const response = await axios.get(
                    `${apiBase}/departments/${deptSelect.value}/courses`
                );
                const courses = response.data.data || [];
                courseSelect.innerHTML = `<option value="">Select Course</option>`;
                if (courses.length) {
                    courses.forEach((c) => {
                        const opt = document.createElement("option");
                        opt.value = c.id;
                        // opt.value = c.name;
                        opt.textContent = c.name;
                        courseSelect.appendChild(opt);
                    });
                } else {
                    courseSelect.innerHTML = `<option value="">No courses available</option>`;
                }
            } catch {
                courseSelect.innerHTML = `<option value="">Error loading courses</option>`;
            }
        });
    }

    // Functions
    function validateField(field, el) {
        const rule = rules[field];
        if (!rule) return null;
        if (!el) return null;

        const tag = el.tagName.toLowerCase();
        const type = el.type;

        // Handle checkbox specifically
        if (type === "checkbox") {
            if (rule.required && !el.checked) {
                return rule.message || "This field is required";
            }
        }
        // Handle radio buttons
        else if (type === "radio") {
            const radios = document.querySelectorAll(`input[name="${field}"]`);
            if (rule.required && !Array.from(radios).some((r) => r.checked)) {
                return rule.message || "This field is required";
            }
        }
        // Handle other input types
        else if (type === "file") {
            if (rule.required && el.files.length === 0) {
                return rule.message || "This field is required";
            }
        } else if (tag === "select") {
            if (rule.required && (!el.value || el.value === "")) {
                return rule.message || "This field is required";
            }
        } else {
            // text, textarea, number, date, email etc.
            const value = el.value.trim();
            if (rule.required && (!value || value === "")) {
                return rule.message || "This field is required";
            }
            if (rule.pattern && !rule.pattern.test(value)) {
                return rule.message;
            }
        }

        return null;
    }
    // Live validation - specifically handle checkbox
    Object.keys(rules).forEach((field) => {
        const el = document.getElementById(field);
        if (el) {
            // For checkboxes, use 'change' event
            const eventType =
                el.type === "checkbox" ||
                    el.type === "radio" ||
                    el.tagName.toLowerCase() === "select"
                    ? "change"
                    : "blur";

            el.addEventListener(eventType, () => {
                // Special handling for checkbox
                if (el.type === "checkbox") {
                    const err = validateField(field, el);
                    showError(field, err);
                } else {
                    const err = validateField(field, el);
                    showError(field, err);
                }
            });
        }
    });

    // // Form submission
    // form.addEventListener("submit", async (e) => {
    //     e.preventDefault();
    //     clearErrors();
    //     if (errorMessage) errorMessage.classList.add("d-none");
    //     if (successContainer) successContainer.classList.add("d-none");

    //     let hasError = false;
    //     Object.keys(rules).forEach((field) => {
    //         const el = document.getElementById(field);
    //         if (el) {
    //             const err = validateField(field, el);
    //             showError(field, err);
    //             if (err && !hasError) hasError = true;
    //         }
    //     });

    //     if (hasError) {
    //         scrollToFirstError();
    //         return;
    //     }

    //     submitBtn.disabled = true;
    //     if (loading) loading.classList.remove("d-none");

    //     try {
    //       const formData = new FormData(form);
    //       console.log(formData);
    //       await axios.post(`${apiBase}/guest/register`, formData);

    //       if (successContainer) successContainer.classList.remove("d-none");
    //       form.reset();
    //     } catch (err) {
    //       if (err.response && err.response.status === 422) {
    //         const errors = err.response.data.errors;
    //         Object.keys(errors).forEach(field => {
    //           const el = document.getElementById(field);
    //           if (el) showError(field, errors[field][0]);
    //         });
    //         scrollToFirstError();
    //       } else if (errorMessage && errorMessageText) {
    //         errorMessage.classList.remove("d-none");
    //         errorMessageText.textContent = "Something went wrong. Try again.";
    //       }
    //     } finally {
    //       submitBtn.disabled = false;
    //       if (loading) loading.classList.add("d-none");
    //     }

    //     // try {
    //     //     const formData = new FormData(form);
    //     //     colosole.log ( res );
    //     //     const { data } = await axios.post(
    //     //         `${apiBase}/guest/register`,
    //     //         formData
    //     //     );

    //     //     // Success handling
    //     //     successContainer.classList.remove("d-none");
    //     //     successContainer.innerHTML = `
    //     //   <div class="alert alert-success">
    //     //     Registration successful! Redirecting to your guest panel...
    //     //   </div>
    //     // `;

    //     //     // Optional: reset form after short delay
    //     //     setTimeout(() => {
    //     //         window.location.href = "/guest/panel"; // replace with actual guest panel route
    //     //     }, 2500);
    //     // } catch (error) {
    //     //     // Server-side validation errors
    //     //     if (error.response && error.response.status === 422) {
    //     //         const errors = error.response.data.errors;
    //     //         Object.keys(errors).forEach((field) => {
    //     //             showError(field, errors[field][0]);
    //     //         });
    //     //         scrollToFirstError(); // scroll to first error
    //     //     } else {
    //     //         // Generic error
    //     //         errorMessage.classList.remove("d-none");
    //     //         errorMessageText.textContent =
    //     //             "Something went wrong. Please check your input.";
    //     //     }

    //     //     // Keep all entered form data intact
    //     // } finally {
    //     //     submitBtn.disabled = false;
    //     //     loading.classList.add("d-none");
    //     // }
    // });

    // Form submission
    // form.addEventListener("submit", async (e) => {
    //     e.preventDefault();
    //     clearErrors();

    //     // Hide old messages
    //     if (errorMessage) errorMessage.classList.add("d-none");
    //     if (successContainer) successContainer.classList.add("d-none");

    //     // Validate client-side rules
    //     let hasError = false;
    //     Object.keys(rules).forEach((field) => {
    //         const el = document.getElementById(field);
    //         if (el) {
    //             const err = validateField(field, el);
    //             showError(field, err);
    //             if (err && !hasError) hasError = true;
    //         }
    //     });

    //     if (hasError) {
    //         scrollToFirstError();
    //         return;
    //     }

    //     // Disable submit + show loading
    //     submitBtn.disabled = true;
    //     if (loading) loading.classList.remove("d-none");

    //     try {
    //         const formData = new FormData(form);



    //         // // Debugging: check values being sent
    //         // for (const [key, value] of formData.entries()) {
    //         //     console.log(key, value);
    //         // }

    //         // Collect accessories into array manually
    //         // const accessories = formData.getAll("accessories[]");
    //         // formData.delete("accessories[]"); // clear old entries
    //         // formData.append("accessories", JSON.stringify(accessories)); // send as JSON array

    //         // console.log("Submitting payload:", Object.fromEntries(formData));

    //         const { data } = await axios.post(`${apiBase}/guest`, formData);

    //         if (data.success) {
    //             successContainer.classList.remove("d-none");
    //             successContainer.innerHTML = `
    //           <div class="alert alert-success">
    //             Registration successful! Redirecting to your guest panel...
    //           </div>
    //         `;

    //             // Redirect safely after short delay
    //             setTimeout(() => {
    //                 window.location.href = "/guest/panel";
    //             }, 2000);
    //         } else {
    //             throw new Error("Unexpected server response.");
    //         }
    //     } catch (err) {
    //         // Server-side validation errors
    //         if (err.response && err.response.status === 422) {
    //             const errors = err.response.data.errors;
    //             Object.keys(errors).forEach((field) => {
    //                 showError(field, errors[field][0]);
    //             });
    //             scrollToFirstError();
    //         } else {
    //             // Generic failure
    //             if (errorMessage && errorMessageText) {
    //                 errorMessage.classList.remove("d-none");
    //                 errorMessageText.textContent =
    //                     "Something went wrong. Please try again later.";
    //             }
    //         }

    //         // ✅ Don’t reset the form — user data stays intact
    //     } finally {
    //         // Re-enable submit + hide loader
    //         submitBtn.disabled = false;
    //         if (loading) loading.classList.add("d-none");
    //     }
    // });

    // Form submission
    form.addEventListener("submit", async (e) => {
    e.preventDefault();
    clearErrors();
    if (errorMessage) errorMessage.classList.add("d-none");
    if (successContainer) successContainer.classList.add("d-none");

    let hasError = false;
    Object.keys(rules).forEach((field) => {
        const el = document.getElementById(field);
        if (el) {
            const err = validateField(field, el);
            showError(field, err);
            if (err && !hasError) hasError = true;
        }
    });

    if (hasError) {
        scrollToFirstError();
        return;
    }

    submitBtn.disabled = true;
    if (loading) loading.classList.remove("d-none");

    try {
        const formData = new FormData(form);

        // Collect and normalize accessories into one array
        const accessories = formData.getAll("accessories[]");
        formData.delete("accessories[]");
        formData.append("accessories", JSON.stringify(accessories));

        console.log("Submitting payload:", Object.fromEntries(formData));

        const { data } = await axios.post(`${apiBase}/guests`, formData, {
            headers: { "Content-Type": "multipart/form-data" },
        });

        console.log(data);

        // Success UI
        if (successContainer) {
            successContainer.classList.remove("d-none");
            successContainer.innerHTML = `
              <div class="alert alert-success">
                Registration successful! Redirecting...
              </div>`;
        }

        setTimeout(() => {
            window.location.href = "/guest/registration-status";
        }, 2000);
    } catch (err) {
        if (err.response && err.response.status === 422) {
            const errors = err.response.data.errors;
            Object.keys(errors).forEach((field) => {
                const el = document.getElementById(field);
                if (el) showError(field, errors[field][0]);
            });
            scrollToFirstError();
        } else if (errorMessage && errorMessageText) {
            errorMessage.classList.remove("d-none");
            errorMessageText.textContent = "Something went wrong. Try again.";
        }
    } finally {
        submitBtn.disabled = false;
        if (loading) loading.classList.add("d-none");
    }
});



});
</script>
