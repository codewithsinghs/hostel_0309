import axios from "axios";
import { rules, validateField, showError, clearErrors, scrollToField } from "./validation.js";

export const handleForm = ({ formSelector, submitUrl, apiBase }) => {
    const form = document.querySelector(formSelector);
    if (!form) return;

    const submitBtn = form.querySelector("button[type=submit]");
    const loading = form.querySelector("#loading");
    const errorMessage = form.querySelector("#errorMessage");
    const errorMessageText = form.querySelector("#errorMessageText");
    const successContainer = form.querySelector("#registrationSuccessContainer");

    const facultySelect = form.querySelector("#faculty");
    const deptSelect = form.querySelector("#department");
    const courseSelect = form.querySelector("#course");

    // Live field validation
    Object.keys(rules).forEach(field => {
        const el = document.getElementById(field);
        if (el) el.addEventListener("blur", () => showError(field, validateField(field, el.value)));
    });

    // Load faculties dynamically
    const loadFaculties = async () => {
        if (!facultySelect) return;
        facultySelect.innerHTML = `<option value="">Loading...</option>`;
        try {
            const res = await axios.get(`${apiBase}/faculties/active`);
            const faculties = res.data.data || [];
            facultySelect.innerHTML = `<option value="">Select Faculty</option>`;
            faculties.forEach(f => {
                const opt = document.createElement("option");
                opt.value = f.id;
                opt.textContent = f.name;
                facultySelect.appendChild(opt);
            });
        } catch {
            facultySelect.innerHTML = `<option value="">Error loading faculties</option>`;
        }
    };

    const loadDepartments = async (facultyId) => {
        if (!deptSelect) return;
        deptSelect.innerHTML = `<option value="">Loading...</option>`;
        courseSelect.innerHTML = `<option value="">Select Course</option>`;
        if (!facultyId) {
            deptSelect.innerHTML = `<option value="">Select Department</option>`;
            return;
        }
        try {
            const res = await axios.get(`${apiBase}/faculties/${facultyId}/departments`);
            const depts = res.data.data || [];
            deptSelect.innerHTML = `<option value="">Select Department</option>`;
            depts.forEach(d => {
                const opt = document.createElement("option");
                opt.value = d.id;
                opt.textContent = d.name;
                deptSelect.appendChild(opt);
            });
        } catch {
            deptSelect.innerHTML = `<option value="">Error loading departments</option>`;
        }
    };

    const loadCourses = async (deptId) => {
        if (!courseSelect) return;
        courseSelect.innerHTML = `<option value="">Loading...</option>`;
        if (!deptId) {
            courseSelect.innerHTML = `<option value="">Select Course</option>`;
            return;
        }
        try {
            const res = await axios.get(`${apiBase}/departments/${deptId}/courses`);
            const courses = res.data.data || [];
            courseSelect.innerHTML = `<option value="">Select Course</option>`;
            if (courses.length) {
                courses.forEach(c => {
                    const opt = document.createElement("option");
                    opt.value = c.id;
                    opt.textContent = c.name;
                    courseSelect.appendChild(opt);
                });
            } else {
                courseSelect.innerHTML = `<option value="">No courses available</option>`;
            }
        } catch {
            courseSelect.innerHTML = `<option value="">Error loading courses</option>`;
        }
    };

    if (facultySelect) {
        facultySelect.addEventListener("change", () => loadDepartments(facultySelect.value));
        loadFaculties();
    }

    if (deptSelect) deptSelect.addEventListener("change", () => loadCourses(deptSelect.value));

    // Form submit
    form.addEventListener("submit", async e => {
        e.preventDefault();
        errorMessage.classList.add("d-none");
        successContainer.classList.add("d-none");
        clearErrors();

        let firstError = null;
        let hasError = false;

        Object.keys(rules).forEach(field => {
            const el = document.getElementById(field);
            if (el) {
                const err = validateField(field, el.value);
                showError(field, err);
                if (err && !firstError) firstError = el;
                if (err) hasError = true;
            }
        });

        if (hasError && firstError) {
            scrollToField(firstError);
            return;
        }

        submitBtn.disabled = true;
        loading.classList.remove("d-none");

        try {
            const formData = new FormData(form);
            await axios.post(submitUrl, formData);
            successContainer.classList.remove("d-none");
            form.reset();
            if (deptSelect) deptSelect.innerHTML = `<option value="">Select Department</option>`;
            if (courseSelect) courseSelect.innerHTML = `<option value="">Select Course</option>`;
        } catch (err) {
            if (err.response?.status === 422) {
                const errors = err.response.data.errors || {};
                let firstField = null;
                Object.keys(errors).forEach(field => {
                    showError(field, errors[field][0]);
                    if (!firstField) firstField = document.getElementById(field);
                });
                if (firstField) scrollToField(firstField);
            } else {
                errorMessage.classList.remove("d-none");
                errorMessageText.textContent = "Something went wrong. Try again.";
            }
        } finally {
            submitBtn.disabled = false;
            loading.classList.add("d-none");
        }
    });
};
