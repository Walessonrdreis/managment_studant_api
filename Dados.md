Inserir: Models, Providers, Services, Controllers

# Student
# User
# Teacher
# Student_Subject
# Subject
# School
# Appointment 
# Attendance
1. Student (Aluno)
Model: Student
Controller: StudentController
Service: StudentService
Provider: StudentServiceProvider
2. User
Model: User
Controller: UserController
Service: UserService
Provider: UserServiceProvider
3. Teacher (Professor)
Model: Teacher
Controller: TeacherController
Service: TeacherService
Provider: TeacherServiceProvider
4. Student_Subject (Associação entre Student e Subject)
Model: StudentSubject (ou StudentSubjectAssociation)
Controller: StudentSubjectController
Service: StudentSubjectService
Provider: StudentSubjectServiceProvider
5. Subject (Aulas)
Model: Subject
Controller: SubjectController
Service: SubjectService
Provider: SubjectServiceProvider
6. School
Model: School
Controller: SchoolController
Service: SchoolService
Provider: SchoolServiceProvider
7. Appointments (Agendamentos)
Model: Appointment
Controller: AppointmentController
Service: AppointmentService
Provider: AppointmentServiceProvider
8. Attendance (Presenças)
Model: Attendance
Controller: AttendanceController
Service: AttendanceService
Provider: AttendanceServiceProvider