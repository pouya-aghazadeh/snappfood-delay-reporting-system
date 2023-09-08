INSERT INTO vendors VALUES(id, 'TEST VENDOR A');
INSERT INTO vendors VALUES(id, 'TEST VENDOR B');
INSERT INTO vendors VALUES(id, 'TEST VENDOR C');
INSERT INTO vendors VALUES(id, 'TEST VENDOR D');


INSERT INTO trackers VALUES(id, 'TEST TRACKER A');
INSERT INTO trackers VALUES(id, 'TEST TRACKER B');


INSERT INTO orders VALUES(id, 1, null, current_timestamp, 50);
INSERT INTO orders VALUES(id, 1, null, current_timestamp, 50);
INSERT INTO orders VALUES(id, 2, null, current_timestamp, 30);
INSERT INTO orders VALUES(id, 2, null, current_timestamp, 70);
INSERT INTO orders VALUES(id, 3, null, current_timestamp, 25);
INSERT INTO orders VALUES(id, 3, null, current_timestamp, 60);
INSERT INTO orders VALUES(id, 4, null, current_timestamp, 35);
INSERT INTO orders VALUES(id, 4, null, current_timestamp, 45);


INSERT INTO trips VALUES (id,'ASSIGNED',1,20,current_timestamp);
INSERT INTO trips VALUES (id,'PICKED',3,25,current_timestamp);
INSERT INTO trips VALUES (id,'AT_VENDOR',5,30,current_timestamp);
INSERT INTO trips VALUES (id,'DELIVERED',7,35,current_timestamp);



UPDATE orders SET trip_id=1 where id=1;
UPDATE trips SET order_id=1 where id=1;

UPDATE orders SET trip_id=2 where id=3;
UPDATE trips SET order_id=3 where id=2;

UPDATE orders SET trip_id=3 where id=5;
UPDATE trips SET order_id=5 where id=3;

UPDATE orders SET trip_id=4 where id=7;
UPDATE trips SET order_id=7 where id=4;

