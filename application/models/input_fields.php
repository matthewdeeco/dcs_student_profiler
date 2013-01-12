<?php

/** Defines what fields are in which columns in the input file. */
final class InputFields {
	const AcadYear		 = 1; // Acad year is in the 1st column
	const Semester		 = 2; // etc...
	const StudentNo		 = 3;
	const LastName		 = 4;
	const FirstName		 = 5;
	const MiddleName	 = 6;
	const Pedigree		 = 7;
	const ClassCode		 = 8;
	const ClassName		 = 9;
	const Grade			 = 10;
	const CompGrade		 = 11;
	const SecondCompGrade = 12;
	
	/** Private constructor to prevent instantiation of class. */
	private function InputFields(){}
}

?>
