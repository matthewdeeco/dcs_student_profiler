DROP SCHEMA public CASCADE;
CREATE SCHEMA public;

CREATE TABLE requirements (
requirementid		SERIAL		PRIMARY KEY,
requirementname	varchar(50),
functionname		varchar(50)
);

CREATE TABLE persons (
personid		SERIAL		PRIMARY KEY,
lastname		varchar(45),
firstname		varchar(45),
middlename		varchar(45),
pedigree		varchar(45)
);

CREATE TABLE instructors (
instructorid		SERIAL	PRIMARY KEY,
personid		integer		REFERENCES persons(personid)
);

CREATE TABLE curricula (
curriculumid		SERIAL	PRIMARY KEY,
curriculumname	varchar(45)	
);

CREATE TABLE students (
studentid		SERIAL	PRIMARY KEY,
personid		integer		REFERENCES persons(personid),
studentno		varchar(9),
curriculumid		integer		REFERENCES curricula(curriculumid)
);

CREATE TABLE terms (
termid		SERIAL	PRIMARY KEY,
name		varchar(45),
year		varchar(9),
sem		varchar(3)
);

CREATE TABLE grades (
gradeid		SERIAL		PRIMARY KEY,
gradename	varchar(4),
gradevalue	real
);

CREATE TABLE courses (
courseid	SERIAL		PRIMARY KEY,
coursename	varchar(45),
credits		real,
domain		varchar(6),
commtype	varchar(2)
);

CREATE TABLE classes (
classid		SERIAL	PRIMARY KEY,
termid		integer	REFERENCES terms(termid),
courseid	integer		REFERENCES courses(courseid),
section		varchar(7),
classcode	varchar(5)
);

CREATE TABLE instructorclasses (
instructorclassid	SERIAL	PRIMARY KEY,
classid			integer		REFERENCES classes(classid),
instructorid		integer		REFERENCES instructors(instructorid)
);

CREATE TABLE studentterms (
studenttermid	SERIAL	PRIMARY KEY,
studentid	integer		REFERENCES students(studentid),
termid		integer	REFERENCES terms(termid),
ineligibilities varchar,
issettled boolean
);

CREATE TABLE studentclasses (
studentclassid		SERIAL	PRIMARY KEY,
studenttermid		integer		REFERENCES studentterms(studenttermid),
classid			integer		REFERENCES classes(classid),
gradeid			integer		REFERENCES grades(gradeid)
);

INSERT INTO curricula (curriculumname) VALUES ('new');
INSERT INTO curricula (curriculumname) VALUES ('old');

INSERT INTO grades (gradename, gradevalue) VALUES ('1.00', 1.00);
INSERT INTO grades (gradename, gradevalue) VALUES ('1.25', 1.25);
INSERT INTO grades (gradename, gradevalue) VALUES ('1.50', 1.50);
INSERT INTO grades (gradename, gradevalue) VALUES ('1.75', 1.75);
INSERT INTO grades (gradename, gradevalue) VALUES ('2.00', 2.00);
INSERT INTO grades (gradename, gradevalue) VALUES ('2.25', 2.25);
INSERT INTO grades (gradename, gradevalue) VALUES ('2.50', 2.50);
INSERT INTO grades (gradename, gradevalue) VALUES ('2.75', 2.75);
INSERT INTO grades (gradename, gradevalue) VALUES ('3.00', 3.00);
INSERT INTO grades (gradename, gradevalue) VALUES ('4.00', 4.00);
INSERT INTO grades (gradename, gradevalue) VALUES ('5.00', 5.00);
INSERT INTO grades (gradename, gradevalue) VALUES ('INC', -1.00);
INSERT INTO grades (gradename, gradevalue) VALUES ('DRP', 0.00);

INSERT INTO terms VALUES (20091, '1st Semester 2009-2010', '2009-2010', '1st');
INSERT INTO terms VALUES (20092, '2nd Semester 2009-2010', '2009-2010', '2nd');
INSERT INTO terms VALUES (20093, 'Summer Semester 2009-2010', '2009-2010', 'Sum');
INSERT INTO terms VALUES (20101, '1st Semester 2010-2011', '2010-2011', '1st');
INSERT INTO terms VALUES (20102, '2nd Semester 2010-2011', '2010-2011', '2nd');
INSERT INTO terms VALUES (20103, 'Summer Semester 2010-2011', '2010-2011', 'Sum');
INSERT INTO terms VALUES (20111, '1st Semester 2011-2012', '2011-2012', '1st');
INSERT INTO terms VALUES (20112, '2nd Semester 2011-2012', '2011-2012', '2nd');
INSERT INTO terms VALUES (20113, 'Summer Semester 2011-2012', '2011-2012', 'Sum');
INSERT INTO terms VALUES (20121, '1st Semester 2012-2013', '2012-2013', '1st');
INSERT INTO terms VALUES (20122, '2nd Semester 2012-2013', '2012-2013', '2nd');
INSERT INTO terms VALUES (20123, 'Summer Semester 2012-2013', '2012-2013', 'Sum');

INSERT INTO courses VALUES (1, 'cs 11', 3, 'MAJ', NULL);
INSERT INTO courses VALUES (2, 'cs 12', 3, 'MAJ', NULL);
INSERT INTO courses VALUES (3, 'cs 21', 4, 'MAJ', NULL);
INSERT INTO courses VALUES (4, 'cs 30', 3, 'MAJ', NULL);
INSERT INTO courses VALUES (5, 'cs 32', 3, 'MAJ', NULL);
INSERT INTO courses VALUES (6, 'cs 140', 3, 'MAJ', NULL);
INSERT INTO courses VALUES (7, 'cs 150', 3, 'MAJ', NULL);
INSERT INTO courses VALUES (8, 'cs 135', 3, 'MAJ', NULL);
INSERT INTO courses VALUES (9, 'cs 165', 3, 'MAJ', NULL);
INSERT INTO courses VALUES (10, 'cs 191', 3, 'MAJ', NULL);
INSERT INTO courses VALUES (11, 'cs 130', 3, 'MAJ', NULL);
INSERT INTO courses VALUES (12, 'cs 192', 3, 'MAJ', NULL);
INSERT INTO courses VALUES (13, 'cs 194', 1, 'MAJ', NULL);
INSERT INTO courses VALUES (14, 'cs 145', 3, 'MAJ', NULL);
INSERT INTO courses VALUES (15, 'cs 153', 3, 'MAJ', NULL);
INSERT INTO courses VALUES (16, 'cs 180', 3, 'MAJ', NULL);
INSERT INTO courses VALUES (17, 'cs 131', 3, 'MAJ', NULL);
INSERT INTO courses VALUES (18, 'cs 195', 3, 'MAJ', NULL);
INSERT INTO courses VALUES (19, 'cs 133', 3, 'MAJ', NULL);
INSERT INTO courses VALUES (20, 'cs 198', 3, 'MAJ', NULL);
INSERT INTO courses VALUES (21, 'cs 196', 1, 'MAJ', NULL);
INSERT INTO courses VALUES (22, 'cs 199', 3, 'MAJ', NULL);
INSERT INTO courses VALUES (23, 'cs 197', 3, 'CS197', NULL);
INSERT INTO courses VALUES (24, 'cs 120', 3, 'CSE', NULL);
INSERT INTO courses VALUES (27, 'cs 173', 3, 'CSE', NULL);
INSERT INTO courses VALUES (28, 'cs 174', 3, 'CSE', NULL);
INSERT INTO courses VALUES (29, 'cs 175', 3, 'CSE', NULL);
INSERT INTO courses VALUES (30, 'cs 176', 3, 'CSE', NULL);
INSERT INTO courses VALUES (25, 'cs 171', 3, 'CSE', NULL);
INSERT INTO courses VALUES (26, 'cs 172', 3, 'CSE', NULL);


INSERT INTO courses VALUES(31, 'comm 1', 3, 'AH', 'E');
INSERT INTO courses VALUES(32, 'comm 2', 3, 'AH', 'E');
INSERT INTO courses VALUES(33, 'hum 1', 3, 'AH', NULL);
INSERT INTO courses VALUES(34, 'hum 2', 3, 'AH', NULL);
INSERT INTO courses VALUES(35, 'aral pil 12', 3, 'AH', 'P');
INSERT INTO courses VALUES(36, 'art stud 1', 3, 'AH', NULL);
INSERT INTO courses VALUES(37, 'art stud 2', 3, 'AH', NULL);
INSERT INTO courses VALUES(38, 'bc 10', 3, 'AH', NULL);
INSERT INTO courses VALUES(39, 'comm 3', 3, 'AH', 'E');
INSERT INTO courses VALUES(40, 'cw 10', 3, 'AH', 'E');
INSERT INTO courses VALUES(41, 'eng 1', 3, 'AH', 'E');
INSERT INTO courses VALUES(42, 'eng 10', 3, 'AH', 'E');
INSERT INTO courses VALUES(43, 'eng 11', 3, 'AH', 'E');
INSERT INTO courses VALUES(44, 'l arch 1', 3, 'AH', NULL);
INSERT INTO courses VALUES(45, 'eng 30', 3, 'AH', 'E');
INSERT INTO courses VALUES(46, 'el 50', 3, 'AH', NULL);
INSERT INTO courses VALUES(47, 'fa 28', 3, 'AH', 'P');
INSERT INTO courses VALUES(48, 'fa 30', 3, 'AH', NULL);
INSERT INTO courses VALUES(49, 'fil 25', 3, 'AH', NULL);
INSERT INTO courses VALUES(50, 'fil 40', 3, 'AH', 'P');
INSERT INTO courses VALUES(51, 'film 10', 3, 'AH', NULL);
INSERT INTO courses VALUES(52, 'film 12', 3, 'AH', 'P');
INSERT INTO courses VALUES(53, 'humad 1', 3, 'AH', 'P');
INSERT INTO courses VALUES(54, 'j 18', 3, 'AH', NULL);
INSERT INTO courses VALUES(55, 'kom 1', 3, 'AH', 'E');
INSERT INTO courses VALUES(56, 'kom 2', 3, 'AH', 'E');
INSERT INTO courses VALUES(57, 'mps 10', 3, 'AH', 'P');
INSERT INTO courses VALUES(58, 'mud 1', 3, 'AH', NULL);
INSERT INTO courses VALUES(59, 'mul 9', 3, 'AH', 'P');
INSERT INTO courses VALUES(60, 'mul 13', 3, 'AH', NULL);
INSERT INTO courses VALUES(61, 'pan pil 12', 3, 'AH', 'P');
INSERT INTO courses VALUES(62, 'pan pil 17', 3, 'AH', 'P');
INSERT INTO courses VALUES(63, 'pan pil 19', 3, 'AH', 'P');
INSERT INTO courses VALUES(64, 'pan pil 40', 3, 'AH', 'P');
INSERT INTO courses VALUES(65, 'pan pil 50', 3, 'AH', 'P');
INSERT INTO courses VALUES(66, 'sea 30', 3, 'AH', NULL);
INSERT INTO courses VALUES(67, 'theatre 10', 3, 'AH', NULL);
INSERT INTO courses VALUES(68, 'theatre 11', 3, 'AH', 'P');
INSERT INTO courses VALUES(69, 'theatre 12', 3, 'AH', NULL);


INSERT INTO courses VALUES(70, 'bio 1', 3, 'MST', NULL);
INSERT INTO courses VALUES(71, 'chem 1', 3, 'MST', NULL);
INSERT INTO courses VALUES(72, 'eee 10', 3, 'MST', NULL);
INSERT INTO courses VALUES(73, 'env sci 1', 3, 'MST', NULL);
INSERT INTO courses VALUES(74, 'es 10', 3, 'MST', NULL);
INSERT INTO courses VALUES(75, 'ge 1', 3, 'MST', NULL);
INSERT INTO courses VALUES(76, 'geol 1', 3, 'MST', NULL);
INSERT INTO courses VALUES(77, 'l arch 1', 3, 'MST', NULL);
INSERT INTO courses VALUES(78, 'math 2', 3, 'MST', NULL);
INSERT INTO courses VALUES(79, 'mbb 1', 3, 'MST', NULL);
INSERT INTO courses VALUES(80, 'ms 1', 3, 'MST', NULL);
INSERT INTO courses VALUES(81, 'nat sci 1', 3, 'MST', NULL);
INSERT INTO courses VALUES(82, 'nat sci 2', 3, 'MST', NULL);
INSERT INTO courses VALUES(83, 'physics 10', 3, 'MST', NULL);
INSERT INTO courses VALUES(84, 'sts', 3, 'MST', NULL);
INSERT INTO courses VALUES(85, 'fn 1', 3, 'MST', NULL);
INSERT INTO courses VALUES(86, 'ce 10', 3, 'MST', NULL);

INSERT INTO courses VALUES(87, 'anthro 10', 3, 'SSP', NULL);
INSERT INTO courses VALUES(88, 'archaeo 2', 3, 'SSP', NULL);
INSERT INTO courses VALUES(89, 'arkiyoloji 1', 3, 'SSP', 'P');
INSERT INTO courses VALUES(90, 'ce 10', 3, 'SSP', NULL);
INSERT INTO courses VALUES(91, 'econ 11', 3, 'SSP', NULL);
INSERT INTO courses VALUES(92, 'econ 31', 3, 'SSP', NULL);
INSERT INTO courses VALUES(93, 'geog 1', 3, 'SSP', NULL);
INSERT INTO courses VALUES(94, 'kas 1', 3, 'SSP', 'P');
INSERT INTO courses VALUES(95, 'kas 2', 3, 'SSP', NULL);
INSERT INTO courses VALUES(96, 'l arch 1', 3, 'SSP', NULL);
INSERT INTO courses VALUES(97, 'lingg 1', 3, 'SSP', NULL);
INSERT INTO courses VALUES(98, 'philo 1', 3, 'SSP', NULL);
INSERT INTO courses VALUES(99, 'philo 10', 3, 'SSP', NULL);
INSERT INTO courses VALUES(100, 'philo 11', 3, 'SSP', NULL);
INSERT INTO courses VALUES(101, 'sea 30', 3, 'SSP', 'P');
INSERT INTO courses VALUES(102, 'soc sci 1', 3, 'SSP', NULL);
INSERT INTO courses VALUES(103, 'soc sci 2', 3, 'SSP', NULL);
INSERT INTO courses VALUES(104, 'soc sci 3', 3, 'SSP', NULL);
INSERT INTO courses VALUES(105, 'socio 10', 3, 'SSP', 'P');

INSERT INTO courses VALUES(106, 'math 17', 5, 'MAJ', NULL);
INSERT INTO courses VALUES(107, 'math 53', 5, 'MAJ', NULL);
INSERT INTO courses VALUES(108, 'math 54', 5, 'MAJ', NULL);
INSERT INTO courses VALUES(109, 'math 55', 3, 'MAJ', NULL);

INSERT INTO courses VALUES(110, 'physics 71', 4, 'MAJ', NULL);
INSERT INTO courses VALUES(111, 'physics 72', 4, 'MAJ', NULL);

INSERT INTO courses VALUES(112, 'stat 130', 3, 'MAJ', NULL);
INSERT INTO courses VALUES(113, 'pi 100', 3, 'MAJ', NULL);
INSERT INTO courses VALUES(114, 'eee 8', 3, 'MAJ', NULL);
INSERT INTO courses VALUES(115, 'eee 9', 3, 'MAJ', NULL);