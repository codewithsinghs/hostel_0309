<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Faculty;
use App\Models\Department;
use Illuminate\Database\Seeder;

class FacultyCourse extends Seeder
{
    public function run()
    {
        $university_id = 1;
        // Seed Faculties
        $science = Faculty::create(['name' => 'Faculty of Science', 'university_id' => $university_id,]);
        $arts = Faculty::create(['name' => 'Faculty of Arts', 'university_id' => $university_id,]);
        $engineering = Faculty::create(['name' => 'Faculty of Engineering', 'university_id' => $university_id,]);

        // Seed Departments
        $physics = Department::create(['name' => 'Physics Department', 'faculty_id' => $science->id]);
        $chemistry = Department::create(['name' => 'Chemistry Department', 'faculty_id' => $science->id]);

        $history = Department::create(['name' => 'History Department', 'faculty_id' => $arts->id]);
        $hindi = Department::create(['name' => 'Hindi Department', 'faculty_id' => $arts->id]);

        $mechanical = Department::create(['name' => 'Mechanical Engineering', 'faculty_id' => $engineering->id]);
        $electrical = Department::create(['name' => 'Electrical Engineering', 'faculty_id' => $engineering->id]);

        // Seed Courses
        Course::create(['name' => 'Quantum Mechanics', 'faculty_id' => $science->id, 'department_id' => $physics->id]);
        Course::create(['name' => 'Masters In Chemistry', 'faculty_id' => $science->id, 'department_id' => $chemistry->id]);
        Course::create(['name' => 'World History', 'faculty_id' => $arts->id, 'department_id' => $history->id]);
        Course::create(['name' => 'Hindi Language', 'faculty_id' => $arts->id, 'department_id' => $hindi->id]);

        Course::create(['name' => 'Thermodynamics', 'faculty_id' => $engineering->id, 'department_id' => $mechanical->id]);
        Course::create(['name' => 'Electical Engineering', 'faculty_id' => $engineering->id, 'department_id' => $electrical->id]);

        Course::create(['name' => 'Environmental Science', 'faculty_id' => $science->id, 'department_id' => null]);
        Course::create(['name' => 'Creative Writing', 'faculty_id' => $arts->id, 'department_id' => null]);
    }
}
