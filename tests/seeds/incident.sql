
-- base severity test load
insert into severity set sevId = 1, sevName='S0', sevDesc='Highest Severity, drop all and work it!';
insert into severity set sevId = 2, sevName='S1', sevDesc='High Severity, getter done';
insert into severity set sevId = 3, sevName='S2', sevDesc='Upper Severity, needs attention';
insert into severity set sevId = 4, sevName='S3', sevDesc='Mid Level Severity, look into when there is time';
insert into severity set sevId = 5, sevName='S4', sevDesc='Lower Level, likely degradation in service';
insert into severity set sevId = 6, sevName='S5', sevDesc='low, if you are working this, things are good!';

-- base incident status
insert into status set statusId=1, statusName='OPEN', statusDesc='Incident is Open and Impacting', orderNum=1;
insert into status set statusId=2, statusName='ESCALATED', statusDesc='Incident is open, tracking and escalated', orderNum=2;
insert into status set statusId=3, statusName='RESOLVED', statusDesc='Incident is Resolved', orderNum=3;
insert into status set statusId=4, statusName='PM_DONE', statusDesc='Post Mortem Done, waiting for Remediation Items', orderNum=4;
insert into status set statusId=5, statusName='REM_OVERDUE', statusDesc='Remediation Now Overdue', orderNum=5;
insert into status set statusId=6, statusName='CLOSED', statusDesc='Resolved, Remediation Clear, all done', orderNum=6;