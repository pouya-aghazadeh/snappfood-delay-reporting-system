create table trackers
(
    id   int auto_increment
        primary key,
    name varchar(255) not null
);

create table vendors
(
    id   int auto_increment
        primary key,
    name varchar(250) not null
);

create table orders
(
    id            int auto_increment
        primary key,
    vendor_id     int                                not null,
    trip_id       int null,
    created_at    datetime default CURRENT_TIMESTAMP not null,
    delivery_time int null,
    constraint orders_vendors_id_fk
        foreign key (vendor_id) references vendors (id)
);

create table delay_reports
(
    id                  int auto_increment
        primary key,
    order_id            int                                not null,
    tracker_id          int null,
    approx_delay_amount int null,
    created_at          datetime default CURRENT_TIMESTAMP not null,
    tracked_at          datetime null,
    constraint delay_reports_orders_id_fk
        foreign key (order_id) references orders (id),
    constraint delay_reports_trackers_id_fk
        foreign key (tracker_id) references trackers (id)
);

create table trips
(
    id         int auto_increment
        primary key,
    status     enum ('ASSIGNED', 'AT_VENDOR', 'PICKED', 'DELIVERED') not null,
    order_id   int                                not null,
    duration   int                                not null,
    created_at datetime default CURRENT_TIMESTAMP not null,
    constraint trips_orders_id_fk
        foreign key (order_id) references orders (id)
);

alter table orders
    add constraint orders_trips_id_fk
        foreign key (trip_id) references trips (id);

