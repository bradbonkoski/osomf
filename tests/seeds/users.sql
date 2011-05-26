
-- seeds for the user table
delete from users;

insert into users set userId = 1, uname='bradb', fname='Brad', lname='Bonkoski', email='brad.bonkoski@yahoo.com', phone='727-698-5555';
insert into users set userId = 2, uname='bradb1', fname='Brad', lname='Bonkoski', email='brad.bonkoski@yahoo.com', phone='727-698-5555';
insert into users set userId = 3, uname='brad2', fname='Brad', lname='Bonkoski', email='brad.bonkoski@yahoo.com', phone='727-698-5555';


-- seeds for userGroup
delete from userGroup;

insert into userGroup set ugid=1, groupName ='group1', groupDesc = 'Some Description Here', phone = '727-333-4444';
insert into userGroup set ugid=2, groupName ='oncall1', groupDesc = 'Top Level On Call', phone = '800-555-1234';

-- seeds for users_groups
delete from users_groups;

insert into users_groups set userid = 1, ugid=2, status = 'admin';
insert into users_groups set userid = 2, ugid=2, status='member';

