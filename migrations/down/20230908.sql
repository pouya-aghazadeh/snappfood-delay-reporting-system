drop table delay_reports;

alter table orders
drop foreign key orders_trips_id_fk;

drop table trackers;

drop table trips;

drop table orders;

drop table vendors;