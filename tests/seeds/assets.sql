
-- test projects
insert into projects set projId = 1, projName="Game1", projDesc="Game1 Team", projOwner=1;
insert into projects set projId = 2, projName="Game2", projDesc="Game 2 Team", projOwner=2;
insert into projects set projId = 3, projName="Game3", projDesc="Game 3 team", projOwner=1;
insert into projects set projId = 4, projname="Game4", projDesc="Game 4 team", projOwner=3;

-- CI Type Seeds
insert into ciType set ciTypeId = 1, typeName="Network", typeDesc="Networking Equipment";
insert into ciType set ciTypeId = 2, typeName="Physical", typeDesc="Physical hardware";
insert into ciType set ciTypeId = 3, typeName="Virtual", typeDesc="Virtual Server";


-- Location Seeds
insert into location set locId = 1, locName="DCC1", locDesc="First Data Center", locOwner=1, locAddr="123 Main Street";
insert into location set locId = 2, locName="DC2", locDesc="Second Data Center", locOwner=1, locAddr="255 Main Street";
insert into location set locId=3, locName="DC3", locDesc="Thrid Data Center", locOwner=2, locAddr="4303 Campbell Road";
insert into location set locId=4, locName="DC4", locOwner=3, locAddr="111 Home Street";

--base/test assets
insert into ci set ciid=1, ciName='ci1.home.com', ciDesc='Virtual CI for home.com',
ownerId=1, projectId=1, statusId=4, ciTypeId=3, ciSerialNum='abc12345', locId=1;
