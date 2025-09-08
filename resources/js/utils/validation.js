// // resources/js/utils/validation.js
// export const rules = {
//     scholar_number: {
//         required: true,
//         pattern: /^[a-zA-Z0-9]+$/,
//         message: "Only letters and numbers allowed"
//     },
//     name: { required: true, message: "Name is required" },
//     email: {
//         required: true,
//         pattern: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
//         message: "Enter a valid email"
//     },
//     mobile: {
//         required: true,
//         pattern: /^[0-9]{10}$/,
//         message: "Enter a valid 10-digit mobile number"
//     },
//     faculty_id: { required: true, message: "Select a faculty" },
//     department_id: { required: true, message: "Select a department" },
//     course_id: { required: true, message: "Select a course" },
//     gender_id: { required: true, message: "Select gender" },
//     agree: { required: true, message: "You must agree to terms" }
// };

// export function validateField(field, value) {
//     const rule = rules[field];
//     if (!rule) return null;

//     if (rule.required && (!value || value.trim() === "")) {
//         return rule.message;
//     }

//     if (rule.pattern && !rule.pattern.test(value)) {
//         return rule.message;
//     }

//     return null; // valid
// }

// export function showError(field, message) {
//     const errorDiv = document.getElementById(`${field}Error`);
//     const input = document.getElementById(field);

//     if (errorDiv) errorDiv.textContent = message || "";
//     if (input) {
//         if (message) {
//             input.classList.add("is-invalid");
//         } else {
//             input.classList.remove("is-invalid");
//         }
//     }
// }


// export const rules = {
//     scholar_number: { required: true, pattern: /^[a-zA-Z0-9]+$/, title: "Only letters and digits allowed" },
//     name: { required: true },
//     email: { required: true, type: "email" },
//     mobile: { required: true, pattern: /^[0-9]{10}$/, title: "10 digit number required" },
//     faculty_id: { required: true },
//     department_id: { required: true },
//     course_id: { required: true },
//     gender_id: { required: true },
//     agree: { required: true }
// };

// export const validateField = (field, value) => {
//     const rule = rules[field];
//     if (!rule) return null;

//     if (rule.required && (!value || value.trim() === "")) return "This field is required.";

//     if (rule.pattern && !rule.pattern.test(value)) return rule.title || "Invalid format";

//     if (rule.type === "email") {
//         const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
//         if (!emailPattern.test(value)) return "Invalid email address.";
//     }

//     return null;
// };

// export const showError = (fieldId, message) => {
//     const field = document.getElementById(fieldId);
//     if (!field) return;
//     const errorDiv = document.getElementById(`${fieldId}Error`);
//     if (errorDiv) {
//         errorDiv.textContent = message || "";
//         errorDiv.classList.toggle("d-block", !!message);
//     }
//     field.classList.toggle("is-invalid", !!message);
// };

// export const clearErrors = () => Object.keys(rules).forEach(f => showError(f, ""));

// export const scrollToField = (field) => {
//     field.scrollIntoView({ behavior: "smooth", block: "center" });
//     field.focus();
// };











// resources/js/utils/validation.js

export const rules = {
    scholar_number: {
        required: true,
        pattern: /^[a-zA-Z0-9]+$/,
        message: "Only letters and numbers allowed",
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
    gender_id: { required: true, message: "Select a gender" },
    fee_waiver: { required: false },
    attachment: { required: false },
    fathers_name: { required: true, message: "Father's Name is required" },
    mothers_name: { required: true, message: "Mother's Name is required" },
    parent_contact: { required: true, message: "Parent Contact is required" },
    local_guardian_name: { required: true, message: "Local Guardian Name is required" },
    guardian_contact: { required: true, message: "Guardian Contact is required" },
    emergency_contact: { required: true, message: "Emergency Contact is required" },
    Food_id: { required: true, message: "Select Food Preference" },
    Bed_id: { required: true, message: "Select Bed Preference" },
    months: { required: true, message: "Select Stay Duration" },
    agree: { required: true, message: "You must agree to terms" },
};

// Validate single field
export function validateField(field, el) {
    const rule = rules[field];
    if (!rule || !el) return null;

    const type = el.type;
    const tag = el.tagName;

    // Checkbox
    if (type === "checkbox") {
        if (rule.required && !el.checked) return rule.message || "This field is required";
    } 
    // Radio group
    else if (type === "radio") {
        const radios = document.querySelectorAll(`input[name="${field}"]`);
        if (rule.required && ![...radios].some(r => r.checked)) return rule.message || "This field is required";
    } 
    // File input
    else if (type === "file") {
        if (rule.required && el.files.length === 0) return rule.message || "This field is required";
    } 
    // Select
    else if (tag === "SELECT") {
        if (rule.required && (!el.value || el.value === "")) return rule.message || "This field is required";
    } 
    // Text, email, number, textarea, date
    else {
        const value = el.value.trim();
        if (rule.required && !value) return rule.message || "This field is required";
        if (rule.pattern && !rule.pattern.test(value)) return rule.message;
    }

    return null; // valid
}

// Show error message
export function showError(field, message) {
    const el = document.getElementById(field);
    const errorDiv = document.getElementById(`${field}Error`);
    if (errorDiv) errorDiv.textContent = message || "";
    if (el) el.classList.toggle("is-invalid", !!message);
}

// Clear all errors in form
export function clearErrors(formId) {
    const form = document.getElementById(formId);
    if (!form) return;
    Object.keys(rules).forEach((field) => {
        const el = form.querySelector(`#${field}`);
        if (el) showError(field, "");
    });
}

// Scroll to first error
export function scrollToFirstError(formId) {
    const form = document.getElementById(formId);
    if (!form) return;
    const firstErrorEl = form.querySelector(".is-invalid");
    if (firstErrorEl) firstErrorEl.scrollIntoView({ behavior: "smooth", block: "center" });
}
