
-- Base Status Information (Can be altered based on implementation
insert into ciStatus set ciStatusId = 1, statusName='ordered', statusDesc='CI is on order, not currently in house';
insert into ciStatus set ciStatusId = 2, statusName='unassigned', statusDesc='CI is in house, but not allocated';
insert into ciStatus set ciStatusId = 3, statusName='dev', statusDesc='CI is assigned, but currently under development';
insert into ciStatus set ciStatusId = 4, statusName='prod', statusDesc='CI is in production';
insert into ciStatus set ciStatusId = 5, statusName='missing', statusDesc='CI is currently unaccounted for';
insert into ciStatus set ciStatusId = 6, statusName='retired', statusDesc='CI is retired, not currently in use';