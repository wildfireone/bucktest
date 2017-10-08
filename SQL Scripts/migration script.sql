/*
***** POPULATE THE NEW TABLES *****
*/

/* ***** Days ***** */
INSERT INTO days VALUES
(1, "Monday"),
(2, "Tuesday"),
(3, "Wednesday"),
(4, "Thursday"),
(5, "Friday"),
(6, "Saturday"),
(7, "Sunday");

/* ***** Status ***** */
INSERT INTO status VALUES 
(1, 'Current','This person is a current member of BASC'),
(2, 'Past','This person is a previous member of BASC'),
(3, 'Past Record Holder','This person is a previous member of BASC who held a record');

/*  ***** Squads ***** */
INSERT INTO squads VALUES
(1,'Yellow','', 'catmck26'),
(2,'Green','', 'stehar26'),
(3,'Green-Plus','', 'kelfin14'),
(4,'Blue','', 'chrbre20'),
(5, 'Blue-Plus','', 'aildaw21'),
(6, 'Red', '', 'kelfin14'),
(7,'Red-Plus','', 'seabre19'),
(8,'Bronze','', 'kelfin14'),
(9,'Silver','', 'zanbla12'),
(10,'Gold','', 'alihic20'),
(11,'Coast','', 'aildaw21'),
(12,'Disability','', 'aildaw21'),
(13,'Dynamo','', 'aildaw21') ;

/* ***** Venues ***** */
INSERT INTO venues VALUES
(1, 'Aberdeen Sports Village Aquatics Centre','Linksfield Road','','Aberdeen','','AB24 5RU','01224438900','','www.aberdeensportsvillage.com'),
(2, 'Aboyne Swimming Pool','Bridgeview Road','','Aboyne','Aberdeenshire','AB34 5JN','01339886222','email','website'),
(3, 'Banff Swimming Pool','addr1','addr2','Banff','county','postcode','','email','website'),
(4, 'Bridge of Don Swimming Pool','addr1','addr2','Aberdeen','county','postcode','','email','website'),
(5, 'Bon-Accord Swimming Pool','addr1','addr2','Aberdeen','county','postcode','','email','website'),
(6, 'Buckie Swimming Pool','addr1','addr2','Buckie','county','postcode','','email','website'),
(7, 'Bucksburn Swimming Pool','addr1','addr2','Bucksburn','county','postcode','','email','website'),
(8, 'Carnegie Leisure Centre','addr1','addr2','Dunfermline','county','postcode','','email','website'),
(9, 'Cults Swimming Pool','addr1','addr2','Aberdeen','county','postcode','','email','website'),
(10, 'Ellon Swimming Pool','addr1','addr2','Ellon','county','postcode','','email','website'),
(11, 'Forres Swimming Pool','addr1','addr2','Forres','county','postcode','','email','website'),
(12, 'Glenrothes Swimming Pool','addr1','addr2','Glenrothes','county','postcode','','email','website'),
(13, 'Grangemouth Sports Complex','addr1','addr2','Grangemouth','county','postcode','','email','website'),
(14, 'Haddington','addr1','addr2','Haddington','county','postcode','','email','website'),
(15, 'Hazelhead Swimming Pool','addr1','addr2','Aberdeen','county','postcode','','email','website'),
(16, 'International Swimming Pool','addr1','addr2','Aberdeen','county','postcode','','email','website'),
(17, 'Inverleith','addr1','addr2','Edinburgh','county','postcode','','email','website'),
(18, 'Inverness Leisure Centre','addr1','addr2','Inverness','county','postcode','','email','website'),
(19, 'Inverness Aquadome','addr1','addr2','Inverness','county','postcode','','email','website'),
(20, 'Inverurie Swimming Pool','addr1','addr2','Inverurie','county','postcode','','email','website'),
(21, 'Kincorth Swimming Pool','addr1','addr2','Aberdeen','county','postcode','','email','website'),
(22, 'Kirkcaldy Swimming Pool','addr1','addr2','Kirkcaldy','county','postcode','','email','website'),
(23, 'Linksfield Swimming Pool','addr1','addr2','Aberdeen','county','postcode','','email','website'),
(24, 'Northfield Swimming Pool','addr1','addr2','Aberdeen','county','postcode','','email','website'),
(25, 'Olympia Leisure Centre','addr1','addr2','Dundee','county','postcode','','email','website'),
(26, 'Peterhead Swimming Pool','addr1','addr2','Peterhead','county','postcode','','email','website'),
(27, 'Ponds Forge International Sports Centre','addr1','addr2','Sheffield','county','postcode','','email','website'),
(28, 'Prestonpans Swimming Pool','addr1','addr2','Prestonpans','county','postcode','','email','website'),
(29, 'Royal Commonwealth Pool','addr1','addr2','Edinburgh','county','postcode','','email','website'),
(30, 'Sheffield Swimming Pool','addr1','addr2','Sheffield','county','postcode','','email','website'),
(31, 'Stirling Swimming Pool','addr1','addr2','Stirling','county','postcode','','email','website'),
(32, 'Sunderland Swimming Pool','addr1','addr2','Sunderland','county','postcode','','email','website'),
(33, 'Tollcross International Swimming Centre','350 Wellshot Road','','Glasgow','','G32 7QP','01412768200','email','website'),
(34, 'Tryst Sports Centre','addr1','','Cumbernauld','','','','email','website'),
(35, 'Tullos Swimming Pool','addr1','','Aberdeen','','','','email','website'),
(36, 'Westhill Swimming Pool','addr1','','Aberdeen','','','','email','website');

/* ***** Roles ***** */
INSERT INTO roles VALUES 
(1, 'Member',''),
(2, 'Swimmer',''),
(3, 'Swimming Technical Officer (STO)','');

/* ***** STROKES ***** */
INSERT INTO strokes (id, stroke) VALUES
(1, 'Butterfly'),
(2, 'Backstroke'),
(3, 'Breaststroke'),
(4, 'Freestyle'),
(5, 'Individual Medley'),
(6, 'Freestyle Relay'),
(7, 'Medley Relay'),
(8, 'Kick Backstroke'),
(9, 'Kick Breaststroke'),
(10, 'Kick Freestyle'),
(11, 'Kickboard'),
(-1, 'Not Specified');

/* ***** LENGTHS ***** */
INSERT INTO lengths VALUES 
(1, 25),
(2, 50),
(3, 100),
(4, 200),
(5, 400),
(6, 800),
(7, 1500);

/* ***** EVENT TYPE ***** */
INSERT INTO swim_events_type(id, type) VALUES
(1,'BASC'),
(2,'non-BASC');

/* Run SQL queries on server */

/* ***** USERS ***** */
INSERT INTO users (username, password)
SELECT username, password
FROM bmember;

/* ****** MEMBERS *****
/* To migrate member details into the Members table*/
INSERT INTO members (SASANumber, SASACategory, username, firstname, middlename, lastname, gender, dob, address1, address2, city, county, postcode, telephone, mobile, email, registerdate, parenttitle, parentname, lastlogindate, notes, statusold, swimmingHours, monthlyfee, feeadjustment)
SELECT mid, category, username, fname, mname, lname, gender, dob, address, address2, city, region, postcode, hometel, mobile, email, memregdate, ptitle, parentname, lastlogindate, comment, status, swimhours, monthlyfee, feeadjust
FROM bmember;
/* Update the genders */
UPDATE members SET gender = 'M' WHERE gender = 'B';
UPDATE members SET gender = 'F' WHERE gender = 'G';
/* Update the status to reference the status table */
UPDATE members SET status = 1 WHERE statusOld = 'C';
UPDATE members SET status = 2 WHERE statusOld = 'P';
UPDATE members SET status = 3 WHERE statusOld = 'R';
/* Delete the statusOld column */
ALTER TABLE members DROP COLUMN statusOld;
/* Update the SASACategory */
UPDATE members SET SASACategory = CAST(SASACategoryOld AS UNSIGNED) WHERE SASACategoryOld <> '';
/* Delete the SASACategoryold column */
ALTER TABLE members DROP COLUMN SASACategoryold;

/* ***** NEWS *****
/* To update black authors */
UPDATE bnews SET inputby = 'administ' WHERE inputby = '' OR inputby = 'julale26';
/* To migrate news items into the News table*/
INSERT INTO news (id, title, subtitle, date, author, mainbody)
SELECT id, headline, title, date, inputby, details
FROM bnews;

/* ***** GALA RESPONSE ***** */
INSERT INTO gala_response (galaid, member, responseold)
SELECT gyrid, username, confirm
FROM bgalaresponse;
/* Update the response records */
UPDATE gala_response SET response = true WHERE responseold = 1;
UPDATE gala_response SET response = false WHERE responseold = 0;
UPDATE gala_response SET response = null WHERE responseold = -1;
/* Delete the old column */
ALTER TABLE gala_response DROP COLUMN responseold;

/* ***** GALA EVENTS ***** */
INSERT INTO gala_events (id, galaid, strokeold, lengthold,gender,agelower,ageupper, subgroup)
SELECT eid, gyrid, sid, length, gender, agel, ageu, subgroup
FROM bgalaevent;
/* Update Length */
UPDATE gala_events SET lengthID = 1 WHERE lengthold = 25;
UPDATE gala_events SET lengthID = 2 WHERE lengthold = 50;
UPDATE gala_events SET lengthID = 3 WHERE lengthold = 100;
UPDATE gala_events SET lengthID = 4 WHERE lengthold = 200;
UPDATE gala_events SET lengthID = 5 WHERE lengthold = 400;
UPDATE gala_events SET lengthID = 6 WHERE lengthold = 800;
UPDATE gala_events SET lengthID = 7 WHERE lengthold = 1500;
ALTER TABLE gala_events DROP COLUMN lengthold;
/* Update StrokeID */
UPDATE gala_events SET strokeID = 1 WHERE strokeold = 'BU';
UPDATE gala_events SET strokeID = 2 WHERE strokeold = 'BA';
UPDATE gala_events SET strokeID = 3 WHERE strokeold = 'BR';
UPDATE gala_events SET strokeID = 4 WHERE strokeold = 'FR';
UPDATE gala_events SET strokeID = 5 WHERE strokeold = 'IM';
UPDATE gala_events SET strokeID = 6 WHERE strokeold = 'RR';
UPDATE gala_events SET strokeID = 7 WHERE strokeold = 'MR';
UPDATE gala_events SET strokeID = 8 WHERE strokeold = 'KA';
UPDATE gala_events SET strokeID = 9 WHERE strokeold = 'KB';
UPDATE gala_events SET strokeID = 10 WHERE strokeold = 'KF';
UPDATE gala_events SET strokeID = 11 WHERE strokeold = 'KI';
ALTER TABLE gala_events DROP COLUMN strokeOld;
/* Update Age Limits */
UPDATE gala_events SET AgeUpper = null WHERE AgeUpper = 100;
UPDATE gala_events SET AgeLower = null WHERE AgeLower = 1;
/* Update Gender Types */
UPDATE gala_events SET gender = 'M' WHERE gender = 'B';
UPDATE gala_events SET gender = 'F' WHERE gender = 'G';



/* ***** galas ***** */
INSERT INTO galas (id, title, description, date, venueold, venueID, warmuptime, organiser, fees, confirmationdate, cutoffdate, eventTypeOld)
SELECT 	gyrid, title, details, date, venue,NULL, warmup, organiser, swimfees, confirmdate, cutoffdate, eventType
FROM bgaladetail;
/* Update Time Criteria */
UPDATE galas SET isLongCourse = true WHERE eventTypeOld = 'L';
UPDATE galas SET isAccredited = false WHERE eventTypeOld = null;
UPDATE galas SET isAccredited = true WHERE eventTypeOld = 'A';
ALTER TABLE galas DROP COLUMN eventTypeOld;
/* Update Venues */
UPDATE galas SET VenueID = 1 WHERE venueold = 'Aberdeen Sports Village Aquatics Centre' OR venueold = 'Aberdeen Aquatics Centre';
UPDATE galas SET VenueID = 2 WHERE venueold = 'Aboyne Swimmig Pool' OR venueold = 'Aboyne Swimming Pool' OR venueold = 'Aboyne Community Centre';
UPDATE galas SET VenueID = 3 WHERE venueold = 'Banff Swimming Pool';
UPDATE galas SET VenueID = 4 WHERE venueold = 'BOD Swimming Pool' OR venueold = 'Bridge of Don' OR venueold = 'Bridge of Don Swimming Pool';
UPDATE galas SET VenueID = 5 WHERE venueold = 'Bon Accord Baths' OR venueold = 'Bon Accord Swimming Pool' OR venueold = 'Bon-Accord Swimming Pool';
UPDATE galas SET VenueID = 6 WHERE venueold = 'Buckie' OR venueold = 'Buckie Leisure Centre' OR venueold = 'Buckie Swimming Pool' OR venueold = 'Buckie Swimming Pool.';
UPDATE galas SET VenueID = 7 WHERE venueold = 'Bucksburn' OR venueold = 'bucksburn Pool' OR venueold = 'Bucksburn Swimming Pool';
UPDATE galas SET VenueID = 8 WHERE venueold = 'Carnegie Leisure Centre Dunfermline' OR venueold = 'Carnegie Sports Complex'; 
UPDATE galas SET VenueID = 9 WHERE venueold = 'Cults' OR venueold = 'Cults Pool' OR venueold = 'CULTS SWIMMING POOL' OR venueold = 'Cults Swimming Pool,  Quarry Road, Aberdeen  '; 
UPDATE galas SET VenueID = 10 WHERE venueold = 'Ellon Swimming Pool'; 
UPDATE galas SET VenueID = 11 WHERE venueold = 'Forres Swimming Pool'; 
UPDATE galas SET VenueID = 12 WHERE venueold = 'Glenrothes' OR venueold = 'Glenrothes Swimming Pool'; 
UPDATE galas SET VenueID = 13 WHERE venueold = 'Grangemouth' OR venueold = 'Grangemouth Sports complex'; 
UPDATE galas SET VenueID = 14 WHERE venueold = 'Haddington'; 
UPDATE galas SET VenueID = 15 WHERE venueold = 'Hazel head pool' OR venueold = 'Hazelhead' OR venueold = 'Hazelhead Swimming Pool'; 
UPDATE galas SET VenueID = 16 WHERE venueold = 'International Swimming Pool'; 
UPDATE galas SET VenueID = 17 WHERE venueold = 'INVERLEITH'; 
UPDATE galas SET VenueID = 18 WHERE venueold = 'Inverness Aquadome'; 
UPDATE galas SET VenueID = 19 WHERE venueold = 'INVERNESS' OR venueold = 'Inverness Leisure Centre' OR venueold = 'Inverness Swimming pool'; 
UPDATE galas SET VenueID = 20 WHERE venueold = 'INVERUIE SWIMMING POOL' OR venueold = 'Inverurie' OR venueold = 'Inverurie Swimming Pool'; 
UPDATE galas SET VenueID = 21 WHERE venueold = 'Kincorth Swimmig Pool' OR venueold = 'Kincorth Swimming Pool' OR venueold = 'Kinkorth Swim Pool' OR venueold = 'Kinkorth Swimming Pool';
UPDATE galas SET VenueID = 22 WHERE venueold = 'Kirkcaldy Swimming Pool';
UPDATE galas SET VenueID = 23 WHERE venueold = 'Linksfield' OR venueold = 'Linksfield Pool' OR venueold = 'Linksfield Swimming Pool';
UPDATE galas SET VenueID = 24 WHERE venueold = 'Norhfield Swimming Pool' OR venueold = 'Northfield' OR venueold = 'Northfield Pool' OR venueold = 'Northfield Swimming Pool';
UPDATE galas SET VenueID = 25 WHERE venueold = 'Olympia Leisure Centre' OR venueold = 'Olympia Leisure Centre Dundee' OR venueold = 'Olympia Swimming Centre' OR venueold = 'dundee';
UPDATE galas SET VenueID = 26 WHERE venueold = 'Peterhead' OR venueold = 'Peterhead Pool' OR venueold = 'Peterhead Swimming Pool' OR venueold = 'PeterheadGraded Meet';
UPDATE galas SET VenueID = 26 WHERE venueold = 'Peterhead' OR venueold = 'Peterhead Pool' OR venueold = 'Peterhead Swimming Pool' OR venueold = 'PeterheadGraded Meet';
UPDATE galas SET VenueID = 27 WHERE venueold = 'Ponds Forge Sheffield';
UPDATE galas SET VenueID = 28 WHERE venueold = 'Prestonpans';
UPDATE galas SET VenueID = 29 WHERE venueold = 'Royal Commonwealth Pool';
UPDATE galas SET VenueID = 30 WHERE venueold = 'Sheffield' OR venueold = 'Sheffield Swimming Pool';
UPDATE galas SET VenueID = 31 WHERE venueold = 'Stirling Swimming Pool';
UPDATE galas SET VenueID = 32 WHERE venueold = 'Sunderland';
UPDATE galas SET VenueID = 33 WHERE venueold = 'Tollcross' OR venueold = 'Tollcross  Glasgow' OR venueold = 'Tollcross / Glasgow' OR venueold = 'Tollcross Glasgow' OR venueold = 'Tollcross Leisure Centre' OR venueold = 'Tollcross Leisure Centre, Glasgow' OR venueold = 'Tollcross Park Leisure Centre' OR venueold = 'Tollcross Swimming Pool , Glasgow' OR venueold = 'Tollcross Swimming Pool, Glasgow' OR venueold = 'Tollcross, Glasgow';
UPDATE galas SET VenueID = 34 WHERE venueold = 'Tryst Sport Centre Cumbernauld' OR venueold = 'Tryst Sports Centre';
UPDATE galas SET VenueID = 35 WHERE venueold = 'Tullos Swimming Pool';
UPDATE galas SET VenueID = 36 WHERE venueold = 'Weshill Swimming Pool' OR venueold = 'Westhill' OR venueold = 'westhill pool' OR venueold = 'Westhill Swimming Pool' OR venueold = 'WESTHILLS SWIMMING POOL' OR venueold = 'Westhills Swimmng Pool';
UPDATE galas SET VenueID = 37 WHERE venueold = 'Cumbernauld';
ALTER TABLE galas DROP COLUMN venueold;


/* ***** CLUB RECORDS ***** */
INSERT INTO club_records (strokeOld, lengthOld, agelower, ageupper, femalemember, femaletime, femaledate, malemember, maletime, maledate, eventTypeOld)
SELECT sid, length, agel, ageu, girl, gtime, gdate, boy, btime, bdate, coAll
FROM bclubrecord;
/* Update Event Types */
UPDATE club_records SET eventtype = 1 WHERE eventTypeOld = 'C';
UPDATE club_records SET eventtype = 2 WHERE eventTypeOld = 'A';
/* Update Length */
UPDATE club_records SET lengthID = 1 WHERE lengthold = 25;
UPDATE club_records SET lengthID = 2 WHERE lengthold = 50;
UPDATE club_records SET lengthID = 3 WHERE lengthold = 100;
UPDATE club_records SET lengthID = 4 WHERE lengthold = 200;
UPDATE club_records SET lengthID = 5 WHERE lengthold = 400;
UPDATE club_records SET lengthID = 6 WHERE lengthold = 800;
UPDATE club_records SET lengthID = 7 WHERE lengthold = 1500;
/* Update StrokeID */
UPDATE club_records SET strokeID = 1 WHERE strokeold = 'BU';
UPDATE club_records SET strokeID = 2 WHERE strokeold = 'BA';
UPDATE club_records SET strokeID = 3 WHERE strokeold = 'BR';
UPDATE club_records SET strokeID = 4 WHERE strokeold = 'FR';
UPDATE club_records SET strokeID = 5 WHERE strokeold = 'IM';
UPDATE club_records SET strokeID = 6 WHERE strokeold = 'RR';
UPDATE club_records SET strokeID = 7 WHERE strokeold = 'MR';
UPDATE club_records SET strokeID = 8 WHERE strokeold = 'KA';
UPDATE club_records SET strokeID = 9 WHERE strokeold = 'KB';
UPDATE club_records SET strokeID = 10 WHERE strokeold = 'KF';
UPDATE club_records SET strokeID = 11 WHERE strokeold = 'KI';
/* Update Member 
UPDATE club_records SET member = femalemember WHERE femalemember <> null;
UPDATE club_records SET member = malemember WHERE malemember <> null;
UPDATE club_records SET time = femaletime WHERE femaletime <> null;
UPDATE club_records SET time = maletime WHERE maletime <> null;
UPDATE club_records SET date = femaledate WHERE femaledate <> null;
UPDATE club_records SET date = maledate WHERE maledate <> null;*/
/* Drop Columns*/
ALTER TABLE club_records DROP COLUMN strokeOld;
ALTER TABLE club_records DROP COLUMN eventTypeOld;
/*ALTER TABLE club_records DROP COLUMN lengthold;
ALTER TABLE club_records DROP COLUMN femaledate;
ALTER TABLE club_records DROP COLUMN femalemember;
ALTER TABLE club_records DROP COLUMN femaletime;
ALTER TABLE club_records DROP COLUMN maledate;
ALTER TABLE club_records DROP COLUMN malemember;
ALTER TABLE club_records DROP COLUMN maletime;*/



/* ***** SWIM TIMES ***** */
INSERT INTO swim_times (member, galaID, eventID, time, rank)
SELECT username, gyrid, eid, time, rank
FROM bgalaresult;

UPDATE swim_times 
SET rank = null
WHERE rank = 0;

/* ****** Timetable ***** */
INSERT INTO timetable (dayID, squadOld, venueOld, time)
SELECT 	Day, Squad, PID, time
FROM btimetable;

UPDATE timetable SET venueID = 7 WHERE venueOld = 'Bucksburn';
ALTER TABLE timetable DROP COLUMN venueOld;

UPDATE timetable SET squadID = 1 WHERE squadOld = 'Yellow';
UPDATE timetable SET squadID = 2 WHERE squadOld = 'Green';
UPDATE timetable SET squadID = 3 WHERE squadOld = 'Green-Plus';
UPDATE timetable SET squadID = 4 WHERE squadOld = 'Blue';
UPDATE timetable SET squadID = 5 WHERE squadOld = 'Blue-Plus';
UPDATE timetable SET squadID = 6 WHERE squadOld = 'Red';
UPDATE timetable SET squadID = 7 WHERE squadOld = 'Red-Plus';
UPDATE timetable SET squadID = 8 WHERE squadOld = 'Bronze';
UPDATE timetable SET squadID = 9 WHERE squadOld = 'Silver';
UPDATE timetable SET squadID = 10 WHERE squadOld = 'Gold';
UPDATE timetable SET squadID = 11 WHERE squadOld = 'Coast';
UPDATE timetable SET squadID = 12 WHERE squadOld = 'Disability';
UPDATE timetable SET squadID = 13 WHERE squadOld = 'Dynamo';
ALTER TABLE timetable DROP COLUMN squadOld;

UPDATE timetable SET dayID = DayID + 1;