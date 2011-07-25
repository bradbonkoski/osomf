
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

-- Some Incident Data
insert into incident set
    incidentId = 1,
    title = 'Test Incident #1',
    statusId = 1,
    start_time = '2010-02-01 11:00:00',
    createdBy = 1,
    severity = 1,
    impact = 'Not sure on the impact',
    revImpact = 'unknown',
    description = 'cat pulled out the power cord',
    detect_time = '2010-02-01 10:55:00',
    resolveTime = '2010-02-01 12:30:00',
    resolveSteps = 'some steps taken',
    updatedBy = 1;

insert into incident set
    incidentId = 2,
    title = 'Test Incident #2',
    statusId = 1,
    start_time = '2010-02-01 11:00:00',
    createdBy = 1,
    severity = 1,
    impact = 'Not sure on the impact',
    revImpact = 'unknown',
    description = 'cat pulled out the power cord',
    detect_time = '2010-02-01 10:55:00';

insert into incident set
    incidentId = 3,
    title = 'Test Incident #3',
    statusId = 1,
    start_time = '2010-02-01 11:00:00',
    createdBy = 1,
    severity = 1,
    impact = 'Not sure on the impact',
    revImpact = 'unknown',
    description = 'cat pulled out the power cord',
    detect_time = '2010-02-01 10:55:00',
    resolveTime = '2010-02-01 12:30:00',
    resolveSteps = 'some steps taken',
    updatedBy = 1;

    insert into incident set
    incidentId = 4,
    title = 'Test Incident #4',
    statusId = 1,
    start_time = '2010-02-01 11:00:00',
    createdBy = 1,
    severity = 1,
    impact = 'Not sure on the impact',
    revImpact = 'unknown',
    description = 'cat pulled out the power cord',
    detect_time = '2010-02-01 10:55:00',
    resolveTime = '2010-02-01 12:30:00',
    resolveSteps = 'some steps taken',
    updatedBy = 1;

    -- seed for incident controller edit test
insert into incident set
    incidentId = 5,
    title = 'Test Incident #5',
    statusId = 1,
    start_time = '2010-02-01 11:00:00',
    createdBy = 1,
    severity = 1,
    impact = 'Not sure on the impact',
    revImpact = 'unknown',
    description = 'cat pulled out the power cord',
    detect_time = '2010-02-01 10:55:00',
    resolveTime = '2010-02-01 12:30:00',
    resolveSteps = 'some steps taken',
    updatedBy = 1;

-- test impacted Data
insert into impacted set impactId = 1, incidentId = 1,
    impactType = 'asset', impactValue=1,
    impactDesc = 'Machine is down!', impactSeverity = 1;
insert into impacted set impactId = 2, incidentId = 1,
    impactType = 'project', impactValue = 1,
    impactDesc='Users unable to play', impactSeverity=2;


-- test worklog data
insert into worklog set workLogId=1, incidentId=1, userId=1, mtime=NOW(), wlType='WORKLOG', data='some worklog entry';
insert into worklog set worklogId=2, incidentId=2, userId=1, mtime='2011-07-11 19:11:36', wlType='STATUS',
data='a:3:{i:0;s:4:"OPEN";i:1;s:9:"ESCALATED";i:2;s:17:"Testing thats all";}';
