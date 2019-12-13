create table People
(
	ID integer primary key autoincrement,
	Name text not null,
	Surname text not null,
	Email int not null
);

create table Courses
(
	ID integer primary key autoincrement,
	Title text not null
);

create table Answers
(
	CourseID int not null
		constraint Answers_Courses_ID_fk
		references Courses (ID)
		on update cascade on delete restrict,
	Submitted int not null,
	Data text not null
);
INSERT INTO Courses(ID, Title) VALUES (1, 'Corso GNU/Linux base autunno 2019');

