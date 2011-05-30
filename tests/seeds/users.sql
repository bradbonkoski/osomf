
-- seeds for the user table
delete from users;

insert into users set userId = 1, uname='bradb', fname='Brad', lname='Bonkoski', email='bradley@ymail.com', phone='800-698-5555';
insert into users set userId = 2, uname='bradb1', fname='Brad', lname='Bonkoski', email='bradb@ymail.com', phone='800-555-5555';
insert into users set userId = 3, uname='brad2', fname='Brad', lname='Bonkoski', email='bradleyo@gmail.com', phone='800-332-5555';

-- a few for test updates
insert into users set userId = 10, uname='fitzer', fname='Fitzgerald', lname='Bonkoski', email='fitzy@yahoo.com', phone='800-555-2123';


-- seeds for userGroup
delete from userGroup;

insert into userGroup set ugid=1, groupName ='group1', groupDesc = 'Some Description Here', phone = '727-333-4444';
insert into userGroup set ugid=2, groupName ='oncall1', groupDesc = 'Top Level On Call', phone = '800-555-1234';

-- seeds for users_groups
delete from users_groups;

insert into users_groups set userid = 1, ugid=2, status = 'admin';
insert into users_groups set userid = 2, ugid=2, status='member';

