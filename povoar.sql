INSERT INTO "authenticated_user" (name,email,birth_date,admin,description,password,avatar,city,is_suspended,reputation,country_id)
VALUES
  ('Jackson Hatrue','penatibus.et@protonmail.org',TO_TIMESTAMP('1970-02-04', 'YYYY-MM-DD'),true,'purus mauris a nunc. In at pede. Cras vulputate velit','neque','risus. Donec egestas.','Huntly',false,808,36),
  ('Tatyana Hunter','duis.a@icloud.ca',TO_TIMESTAMP('2014-10-29', 'YYYY-MM-DD'),true,'dignissim lacus. Aliquam rutrum lorem ac risus. Morbi metus. Vivamus','Integer','torquent per conubia','Bad Neuenahr-Ahrweiler',false,494,53),
  ('Sigourney Garcia','cras.lorem.lorem@outlook.edu',TO_TIMESTAMP('2007-05-03', 'YYYY-MM-DD'),false,'bibendum. Donec felis orci, adipiscing falsen, luctus sit amet, faucibus','ut','eu odio tristique','Galway',true,440,91),
  ('Melinda Lawson','aliquam@protonmail.org',TO_TIMESTAMP('1970-09-29', 'YYYY-MM-DD'),false,'ipsum dolor sit amet, consectetuer adipiscing elit. Curabitur sed tortor.','eu','ligula. Nullam enim.','Gorzów Wielkopolski',false,203,86),
  ('Gavin Rosa','odio.phasellus@yahoo.net',TO_TIMESTAMP('1976-10-13', 'YYYY-MM-DD'),false,'nunc nulla vulputate dui, nec tempus mauris erat eget ipsum.','enim.','est, congue a,','Hebei',true,418,84),
  ('Malcolm Schwartz','ullamcorper.eu@yahoo.edu',TO_TIMESTAMP('1990-11-15', 'YYYY-MM-DD'),false,'Proin mi. Aliquam gravida mauris ut mi. Duis risus odio,','Mauris','libero mauris, aliquam','Colorado Springs',false,497,36),
  ('Christen Faulkner','aliquam.nisl@yahoo.org',TO_TIMESTAMP('1954-09-28', 'YYYY-MM-DD'),false,'sagittis. Nullam vitae diam. Proin dolor. Nulla semper tellus id','a,','mauris a nunc.','Kaliningrad',false,433,60),
  ('Devin Kaufman','urna@google.net',TO_TIMESTAMP('1977-06-05', 'YYYY-MM-DD'),false,'vel lectus. Cum sociis natoque penatibus et magnis dis parturient','Vivamus','Nullam ut nisi','Chesapeake',true,412,38),
  ('Tad falseel','lacus@google.ca',TO_TIMESTAMP('1970-01-22', 'YYYY-MM-DD'),false,'in, cursus et, eros. Proin ultrices. Duis volutpat nunc sit','molestie','Nam interdum enim','Campina Grande',false,802,35),
  ('Hall May','turpis.vitae.purus@google.ca',TO_TIMESTAMP('2016-09-12', 'YYYY-MM-DD'),false,'ante blandit viverra. Donec tempus, lorem fringilla ornare placerat, orci','dolor.','in, tempus eu,','Cockburn',false,886,15),
  ('Baxter Hansen','ipsum@aol.edu',TO_TIMESTAMP('2008-10-14', 'YYYY-MM-DD'),false,'felis. Nulla tempor augue ac ipsum. Phasellus vitae mauris sit','nascetur','aliquam iaculis, lacus','Sechura',false,66,27),
  ('Scarlet Chapman','convallis.erat@hotmail.com',TO_TIMESTAMP('2008-09-07', 'YYYY-MM-DD'),false,'neque. Morbi quis urna. Nunc quis arcu vel quam dignissim','ultricies','dignissim magna a','Magadan',true,342,75),
  ('Darryl Noel','vulputate.dui.nec@protonmail.com',TO_TIMESTAMP('1963-12-22', 'YYYY-MM-DD'),false,'odio. Nam interdum enim non nisi. Aenean eget metus. In','at,','tincidunt vehicula risus.','Canoas',true,302,42),
  ('Jerome Jacobson','tincidunt@google.org',TO_TIMESTAMP('1955-08-20', 'YYYY-MM-DD'),false,'arcu imperdiet ullamcorper. Duis at lacus. Quisque purus sapien, gravida','a','purus, in molestie','Arequipa',true,965,2),
  ('Teegan Hayes','in@protonmail.net',TO_TIMESTAMP('1970-11-18', 'YYYY-MM-DD'),false,'Donec egestas. Aliquam nec enim. Nunc ut erat. Sed nunc','cubilia','porttitor eros nec','Chillán Viejo',true,576,46),
  ('Cecilia Quinn','arcu@hotmail.ca',TO_TIMESTAMP('202021-08-22', 'YYYY-MM-DD'),false,'cursus, diam at pretium aliquet, metus urna convallis erat, eget','faucibus','odio a purus.','Blenheim',true,254,74),
  ('Geoffrey Guerra','dictum.proin@aol.net',TO_TIMESTAMP('1995-09-05', 'YYYY-MM-DD'),false,'euismod urna. Nullam lobortis quam a felis ullamcorper viverra. Maecenas','sapien,','Sed eu nibh','Pfungstadt',true,869,9),
  ('Anastasia Jones','nisl@yahoo.couk',TO_TIMESTAMP('1998-09-28', 'YYYY-MM-DD'),false,'urna. Ut tincidunt vehicula risus. Nulla eget metus eu erat','non','congue turpis. In','Iseyin',false,54,51),
  ('Natalie Perez','ornare.elit.elit@aol.couk',TO_TIMESTAMP('1979-11-04', 'YYYY-MM-DD'),false,'Phasellus fermentum convallis ligula. Donec luctus aliquet odio. Etiam ligula','egestas','volutpat ornare, facilisis','Stevenage',true,310,15),
  ('Althea Michael','pede.nunc.sed@aol.edu',TO_TIMESTAMP('1978-05-03', 'YYYY-MM-DD'),false,'mauris id sapien. Cras dolor dolor, tempus non, lacinia at,','risus.','non nisi. Aenean','Sakhalin',true,711,52);

  
INSERT INTO "suspension" (reason,start_time,end_time,admin_id,user_id)
VALUES
  ('vel lectus. Cum sociis natoque',TO_TIMESTAMP('2019-04-23', 'YYYY-MM-DD'),TO_TIMESTAMP('2021-07-13', 'YYYY-MM-DD'),2,9),
  ('ipsum ac mi eleifend egestas.',TO_TIMESTAMP('2019-02-07', 'YYYY-MM-DD'),TO_TIMESTAMP('2021-11-18', 'YYYY-MM-DD'),1,3),
  ('Aliquam rutrum lorem ac risus.',TO_TIMESTAMP('2019-05-19', 'YYYY-MM-DD'),TO_TIMESTAMP('2020-06-27', 'YYYY-MM-DD'),1,2),
  ('Etiam ligula tortor, dictum eu,',TO_TIMESTAMP('2019-08-06', 'YYYY-MM-DD'),TO_TIMESTAMP('2021-10-30', 'YYYY-MM-DD'),2,3),
  ('Lorem ipsum dolor sit amet,',TO_TIMESTAMP('2019-04-22', 'YYYY-MM-DD'),TO_TIMESTAMP('2020-07-23', 'YYYY-MM-DD'),1,1),
  ('Vestibulum ante ipsum primis in',TO_TIMESTAMP('2019-07-30', 'YYYY-MM-DD'),TO_TIMESTAMP('2021-08-10', 'YYYY-MM-DD'),2,3),
  ('diam. Duis mi enim, condimentum',TO_TIMESTAMP('2019-11-24', 'YYYY-MM-DD'),TO_TIMESTAMP('2021-11-14', 'YYYY-MM-DD'),1,8),
  ('a, aliquet vel, vulputate eu,',TO_TIMESTAMP('2019-06-30', 'YYYY-MM-DD'),TO_TIMESTAMP('2021-05-06', 'YYYY-MM-DD'),2,8),
  ('Cras dictum ultricies ligula. Nullam',TO_TIMESTAMP('2019-10-13', 'YYYY-MM-DD'),TO_TIMESTAMP('2021-10-19', 'YYYY-MM-DD'),1,4),
  ('orci quis lectus. Nullam suscipit,',TO_TIMESTAMP('2019-06-27', 'YYYY-MM-DD'),TO_TIMESTAMP('2021-11-21', 'YYYY-MM-DD'),1,7),
  ('adipiscing. Mauris molestie pharetra nibh.',TO_TIMESTAMP('2019-06-21', 'YYYY-MM-DD'),TO_TIMESTAMP('2021-03-17', 'YYYY-MM-DD'),2,3),
  ('lorem, auctor quis, tristique ac,',TO_TIMESTAMP('2019-03-30', 'YYYY-MM-DD'),TO_TIMESTAMP('2021-09-10', 'YYYY-MM-DD'),2,2),
  ('purus, in molestie tortor nibh',TO_TIMESTAMP('2019-04-25', 'YYYY-MM-DD'),TO_TIMESTAMP('2020-10-03', 'YYYY-MM-DD'),1,8),
  ('Ut semper pretium neque. Morbi',TO_TIMESTAMP('2019-03-13', 'YYYY-MM-DD'),TO_TIMESTAMP('2020-11-21', 'YYYY-MM-DD'),2,4),
  ('facilisis, magna tellus faucibus leo,',TO_TIMESTAMP('2019-09-04', 'YYYY-MM-DD'),TO_TIMESTAMP('2021-06-07', 'YYYY-MM-DD'),1,4),
  ('egestas lacinia. Sed congue, elit',TO_TIMESTAMP('2019-09-30', 'YYYY-MM-DD'),TO_TIMESTAMP('2020-08-28', 'YYYY-MM-DD'),2,10),
  ('mauris blandit mattis. Cras eget',TO_TIMESTAMP('2019-11-03', 'YYYY-MM-DD'),TO_TIMESTAMP('2021-12-27', 'YYYY-MM-DD'),2,1),
  ('ac turpis egestas. Aliquam fringilla',TO_TIMESTAMP('2019-08-12', 'YYYY-MM-DD'),TO_TIMESTAMP('2021-11-28', 'YYYY-MM-DD'),2,4),
  ('pede sagittis augue, eu tempor',TO_TIMESTAMP('2019-04-29', 'YYYY-MM-DD'),TO_TIMESTAMP('2021-01-22', 'YYYY-MM-DD'),2,9),
  ('amet nulla. Donec non justo.',TO_TIMESTAMP('2019-04-22', 'YYYY-MM-DD'),TO_TIMESTAMP('2020-07-25', 'YYYY-MM-DD'),1,4);


  INSERT INTO "report" (reason,reported_at,is_closed,reported_id,reporter_id)
VALUES
  ('sit amet metus. Aliquam erat',TO_TIMESTAMP('2019-08-11', 'YYYY-MM-DD'),false,18,6),
  ('aliquet, sem ut cursus luctus,',TO_TIMESTAMP('2019-10-08', 'YYYY-MM-DD'),false,15,5),
  ('nec urna suscipit nonummy. Fusce',TO_TIMESTAMP('2019-08-18', 'YYYY-MM-DD'),false,18,3),
  ('cursus non, egestas a, dui.',TO_TIMESTAMP('2019-05-03', 'YYYY-MM-DD'),true,11,9),
  ('Praesent eu nulla at sem',TO_TIMESTAMP('2019-03-07', 'YYYY-MM-DD'),true,19,7),
  ('ac facilisis facilisis, magna tellus',TO_TIMESTAMP('2019-09-15', 'YYYY-MM-DD'),false,18,10),
  ('interdum feugiat. Sed nec metus',TO_TIMESTAMP('2019-11-09', 'YYYY-MM-DD'),true,12,4),
  ('quis accumsan convallis, ante lectus',TO_TIMESTAMP('2019-08-22', 'YYYY-MM-DD'),true,20,8),
  ('Curabitur sed tortor. Integer aliquam',TO_TIMESTAMP('2019-02-14', 'YYYY-MM-DD'),true,16,2),
  ('dis parturient montes, nascetur ridiculus',TO_TIMESTAMP('2019-07-04', 'YYYY-MM-DD'),true,11,2),
  ('Integer aliquam adipiscing lacus. Ut',TO_TIMESTAMP('2019-04-17', 'YYYY-MM-DD'),true,15,2),
  ('arcu imperdiet ullamcorper. Duis at',TO_TIMESTAMP('2019-05-21', 'YYYY-MM-DD'),true,12,2),
  ('Sed diam lorem, auctor quis,',TO_TIMESTAMP('2019-09-15', 'YYYY-MM-DD'),true,12,2),
  ('vulputate velit eu sem. Pellentesque',TO_TIMESTAMP('2019-03-29', 'YYYY-MM-DD'),true,18,9),
  ('laoreet, libero et tristique pellentesque,',TO_TIMESTAMP('2019-06-10', 'YYYY-MM-DD'),false,17,10),
  ('vel, convallis in, cursus et,',TO_TIMESTAMP('2019-07-24', 'YYYY-MM-DD'),true,18,4),
  ('tincidunt tempus risus. Donec egestas.',TO_TIMESTAMP('2019-06-05', 'YYYY-MM-DD'),false,20,2),
  ('Donec porttitor tellus non magna.',TO_TIMESTAMP('2019-09-03', 'YYYY-MM-DD'),true,13,3),
  ('iaculis odio. Nam interdum enim',TO_TIMESTAMP('2019-08-20', 'YYYY-MM-DD'),true,17,10),
  ('eros non enim commodo hendrerit.',TO_TIMESTAMP('2019-08-04', 'YYYY-MM-DD'),false,17,2);


INSERT INTO "tag" (name,proposed_at,state,user_id)
VALUES
  ('Dominique Bishop',TO_TIMESTAMP('2019-08-27', 'YYYY-MM-DD'),'PENDING',13),
  ('Kai Gilmore',TO_TIMESTAMP('2019-06-28', 'YYYY-MM-DD'),'ACCEPTED',14),
  ('Berk Mccall',TO_TIMESTAMP('2019-09-21', 'YYYY-MM-DD'),'REJECTED',19),
  ('Emmanuel Dickson',TO_TIMESTAMP('2019-02-03', 'YYYY-MM-DD'),'ACCEPTED',9),
  ('Chandler Stuart',TO_TIMESTAMP('2019-08-31', 'YYYY-MM-DD'),'ACCEPTED',4),
  ('Noelani Knapp',TO_TIMESTAMP('2019-05-28', 'YYYY-MM-DD'),'PENDING',15),
  ('Isabelle Johnson',TO_TIMESTAMP('2019-05-22', 'YYYY-MM-DD'),'REJECTED',11),
  ('Marsden Lloyd',TO_TIMESTAMP('2019-03-14', 'YYYY-MM-DD'),'PENDING',16),
  ('Indigo Alston',TO_TIMESTAMP('2019-05-14', 'YYYY-MM-DD'),'ACCEPTED',18),
  ('Tamekah Dyer',TO_TIMESTAMP('2019-07-17', 'YYYY-MM-DD'),'REJECTED',7),
  ('Brent Glass',TO_TIMESTAMP('2019-06-01', 'YYYY-MM-DD'),'PENDING',5),
  ('Roth Bates',TO_TIMESTAMP('2019-03-02', 'YYYY-MM-DD'),'REJECTED',2),
  ('Illiana Hoover',TO_TIMESTAMP('2019-03-17', 'YYYY-MM-DD'),'PENDING',19),
  ('Dean Macdonald',TO_TIMESTAMP('2019-09-08', 'YYYY-MM-DD'),'REJECTED',16),
  ('Hector Giles',TO_TIMESTAMP('2019-09-29', 'YYYY-MM-DD'),'REJECTED',8),
  ('Mufutau Fisher',TO_TIMESTAMP('2019-03-09', 'YYYY-MM-DD'),'PENDING',19),
  ('Avye Wolfe',TO_TIMESTAMP('2019-05-11', 'YYYY-MM-DD'),'REJECTED',10),
  ('Noah Holt',TO_TIMESTAMP('2019-11-15', 'YYYY-MM-DD'),'PENDING',5),
  ('Olga Aguirre',TO_TIMESTAMP('2019-04-04', 'YYYY-MM-DD'),'PENDING',1),
  ('Hector Richard',TO_TIMESTAMP('2019-10-28', 'YYYY-MM-DD'),'PENDING',8);


INSERT INTO "area_of_expertise" (user_id,proposed_at,reputation)
VALUES
  (6,12,43),
  (3,9,77),
  (18,10,77),
  (20,9,81),
  (12,15,63),
  (1,11,53),
  (13,20,90),
  (4,3,92),
  (13,14,81),
  (7,11,29),
  (15,13,86),
  (10,17,14),
  (3,14,65),
  (4,2,87),
  (8,20,31),
  (6,9,76),
  (10,4,82),
  (3,13,19),
  (3,6,93),
  (2,4,29);


INSERT INTO "favorite_tag" (user_id,proposed_at)
VALUES
  (20,6),
  (16,20),
  (5,3),
  (19,17),
  (11,10),
  (14,20),
  (20,14),
  (6,8),
  (11,14),
  (15,1),
  (7,1),
  (17,12),
  (17,16),
  (17,3),
  (4,13),
  (18,14),
  (15,6),
  (13,17),
  (9,15),
  (20,4);


INSERT INTO "message" (body,proposed_at,sender_id,receiver_id,is_read)
VALUES
  ('eget magna. Suspendisse tristique neque venenatis lacus. Etiam bibendum fermentum',TO_TIMESTAMP('2020-03-07', 'YYYY-MM-DD'),7,15,true),
  ('Aliquam ultrices iaculis odio. Nam interdum enim non nisi. Aenean',TO_TIMESTAMP('2021-04-16', 'YYYY-MM-DD'),8,16,false),
  ('sed sem egestas blandit. Nam nulla magna, malesuada vel, convallis',TO_TIMESTAMP('2020-11-04', 'YYYY-MM-DD'),4,18,false),
  ('Aliquam vulputate ullamcorper magna. Sed eu eros. Nam consequat dolor',TO_TIMESTAMP('2021-04-26', 'YYYY-MM-DD'),3,11,true),
  ('aliquet, sem ut cursus luctus, ipsum leo elementum sem, vitae',TO_TIMESTAMP('2021-06-05', 'YYYY-MM-DD'),2,18,true),
  ('arcu imperdiet ullamcorper. Duis at lacus. Quisque purus sapien, gravida',TO_TIMESTAMP('2021-06-20', 'YYYY-MM-DD'),5,13,true),
  ('leo, in lobortis tellus justo sit amet nulla. Donec non',TO_TIMESTAMP('2020-02-19', 'YYYY-MM-DD'),1,18,true),
  ('Pellentesque ut ipsum ac mi eleifend egestas. Sed pharetra, felis',TO_TIMESTAMP('2020-02-27', 'YYYY-MM-DD'),3,18,false),
  ('ut nisi a odio semper cursus. Integer mollis. Integer tincidunt',TO_TIMESTAMP('2020-10-10', 'YYYY-MM-DD'),6,12,true),
  ('quam, elementum at, egestas a, scelerisque sed, sapien. Nunc pulvinar',TO_TIMESTAMP('2019-12-02', 'YYYY-MM-DD'),6,18,true),
  ('purus gravida sagittis. Duis gravida. Praesent eu nulla at sem',TO_TIMESTAMP('2021-06-20', 'YYYY-MM-DD'),8,15,false),
  ('feugiat. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aliquam',TO_TIMESTAMP('2021-09-19', 'YYYY-MM-DD'),2,17,false),
  ('tempus, lorem fringilla ornare placerat, orci lacus vestibulum lorem, sit',TO_TIMESTAMP('2021-06-19', 'YYYY-MM-DD'),10,19,false),
  ('at, velit. Pellentesque ultricies dignissim lacus. Aliquam rutrum lorem ac',TO_TIMESTAMP('2020-10-07', 'YYYY-MM-DD'),3,18,false),
  ('imperdiet, erat nonummy ultricies ornare, elit elit fermentum risus, at',TO_TIMESTAMP('2019-11-30', 'YYYY-MM-DD'),9,16,false),
  ('lobortis quam a felis ullamcorper viverra. Maecenas iaculis aliquet diam.',TO_TIMESTAMP('2021-07-12', 'YYYY-MM-DD'),6,15,true),
  ('tristique pellentesque, tellus sem mollis dui, in sodales elit erat',TO_TIMESTAMP('2021-03-07', 'YYYY-MM-DD'),5,18,true),
  ('urna. Nullam lobortis quam a felis ullamcorper viverra. Maecenas iaculis',TO_TIMESTAMP('2020-09-04', 'YYYY-MM-DD'),1,13,true),
  ('purus mauris a nunc. In at pede. Cras vulputate velit',TO_TIMESTAMP('2020-03-06', 'YYYY-MM-DD'),4,19,true),
  ('vulputate, posuere vulputate, lacus. Cras interdum. Nunc sollicitudin commodo ipsum.',TO_TIMESTAMP('2021-02-21', 'YYYY-MM-DD'),9,17,true);


INSERT INTO "follow" (follower_id,followed_at)
VALUES
  (4,13),
  (2,14),
  (8,15),
  (5,19),
  (9,16),
  (2,13),
  (4,12),
  (5,12),
  (8,18),
  (7,17),
  (3,12),
  (7,17),
  (2,12),
  (6,17),
  (5,17),
  (9,12),
  (6,15),
  (2,14),
  (8,12),
  (5,15);


INSERT INTO "content" (body,published_at,is_edited,likes,dislikes,author_id)
VALUES
  ('ante ipsum primis in faucibus orci luctus et ultrices posuere',TO_TIMESTAMP('2021-03-02', 'YYYY-MM-DD'),false,95,68,19),
  ('amet, consectetuer adipiscing elit. Curabitur sed tortor. Integer aliquam adipiscing',TO_TIMESTAMP('2021-01-22', 'YYYY-MM-DD'),false,88,76,19),
  ('dictum magna. Ut tincidunt orci quis lectus. Nullam suscipit, est',TO_TIMESTAMP('2021-05-19', 'YYYY-MM-DD'),false,78,84,9),
  ('fringilla est. Mauris eu turpis. Nulla aliquet. Proin velit. Sed',TO_TIMESTAMP('2021-05-23', 'YYYY-MM-DD'),true,59,71,8),
  ('vulputate eu, odio. Phasellus at augue id ante dictum cursus.',TO_TIMESTAMP('2020-12-06', 'YYYY-MM-DD'),false,85,49,19),
  ('pede. Praesent eu dui. Cum sociis natoque penatibus et magnis',TO_TIMESTAMP('2021-10-27', 'YYYY-MM-DD'),false,3,66,12),
  ('Cras interdum. Nunc sollicitudin commodo ipsum. Suspendisse non leo. Vivamus',TO_TIMESTAMP('2021-03-04', 'YYYY-MM-DD'),false,91,41,17),
  ('vel, vulputate eu, odio. Phasellus at augue id ante dictum',TO_TIMESTAMP('2021-03-11', 'YYYY-MM-DD'),false,1,20,19),
  ('sed pede. Cum sociis natoque penatibus et magnis dis parturient',TO_TIMESTAMP('2021-08-07', 'YYYY-MM-DD'),true,23,52,8),
  ('Nullam nisl. Maecenas malesuada fringilla est. Mauris eu turpis. Nulla',TO_TIMESTAMP('2021-04-09', 'YYYY-MM-DD'),true,62,45,16),
  ('feugiat. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aliquam',TO_TIMESTAMP('2021-09-05', 'YYYY-MM-DD'),true,67,79,6),
  ('sapien, gravida non, sollicitudin a, malesuada id, erat. Etiam vestibulum',TO_TIMESTAMP('2021-02-13', 'YYYY-MM-DD'),false,3,8,10),
  ('nunc. In at pede. Cras vulputate velit eu sem. Pellentesque',TO_TIMESTAMP('2021-09-15', 'YYYY-MM-DD'),false,28,95,2),
  ('urna. Ut tincidunt vehicula risus. Nulla eget metus eu erat',TO_TIMESTAMP('2021-05-27', 'YYYY-MM-DD'),false,43,69,18),
  ('in, tempus eu, ligula. Aenean euismod mauris eu elit. Nulla',TO_TIMESTAMP('2021-04-25', 'YYYY-MM-DD'),true,60,82,12),
  ('enim, gravida sit amet, dapibus id, blandit at, nisi. Cum',TO_TIMESTAMP('2021-07-11', 'YYYY-MM-DD'),true,53,78,14),
  ('posuere, enim nisl elementum purus, accumsan interdum libero dui nec',TO_TIMESTAMP('2021-01-02', 'YYYY-MM-DD'),false,38,63,2),
  ('mauris. Integer sem elit, pharetra ut, pharetra sed, hendrerit a,',TO_TIMESTAMP('2021-03-09', 'YYYY-MM-DD'),false,4,70,13),
  ('a, magna. Lorem ipsum dolor sit amet, consectetuer adipiscing elit.',TO_TIMESTAMP('2021-07-18', 'YYYY-MM-DD'),true,37,12,10),
  ('enim diam vel arcu. Curabitur ut odio vel est tempor',TO_TIMESTAMP('2021-07-04', 'YYYY-MM-DD'),true,5,85,14);


INSERT INTO "article" (content_id,title,thumbnail)
VALUES
  (1,'velit. Cras lorem lorem,','risus. Duis a mi fringilla'),
  (2,'nunc id enim. Curabitur','sem eget massa. Suspendisse eleifend.'),
  (3,'Fusce fermentum fermentum arcu.','in aliquet lobortis, nisi nibh'),
  (4,'eget varius ultrices, mauris','eget metus. In nec orci.'),
  (5,'Curabitur vel lectus. Cum','Donec luctus aliquet odio. Etiam'),
  (6,'enim. Suspendisse aliquet, sem','lorem, vehicula et, rutrum eu,'),
  (7,'urna. Nullam lobortis quam','urna, nec luctus felis purus'),
  (8,'feugiat placerat velit. Quisque','sit amet luctus vulputate, nisi'),
  (9,'varius et, euismod et,','et malesuada fames ac turpis'),
  (10,'mauris blandit mattis. Cras','aliquet nec, imperdiet nec, leo.');


INSERT INTO "comment" (content_id,article_id,parent_comment_id)
VALUES
  (11,7,NULL),
  (12,4,NULL),
  (13,10,NULL),
  (14,6,NULL),
  (15,2,NULL),
  (16,4,3),
  (17,4,3),
  (18,2,3),
  (19,7,4),
  (20,4,3);


INSERT INTO "feedback" (user_id,content_id,is_like)
VALUES
  (7,3,false),
  (11,9,true),
  (10,6,true),
  (8,3,true),
  (16,8,true),
  (19,7,true),
  (3,10,true),
  (15,6,true),
  (18,2,false),
  (5,13,true),
  (6,2,false),
  (19,14,false),
  (7,4,false),
  (11,4,false),
  (2,12,true),
  (19,16,false),
  (3,9,false),
  (1,15,false),
  (7,15,true),
  (10,7,true);


INSERT INTO "article_tag" (article_id,tag_id)
VALUES
  (7,9),
  (4,4),
  (2,4),
  (6,2),
  (5,13),
  (2,18),
  (8,4),
  (8,12),
  (1,12),
  (10,5),
  (7,14),
  (8,16),
  (6,3),
  (10,4),
  (4,2),
  (3,13),
  (3,15),
  (9,4),
  (6,16),
  (5,11);