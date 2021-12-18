-----------------------------------------
-- Drop old schema
-----------------------------------------
DROP SCHEMA IF EXISTS lbaw2111 CASCADE;
CREATE SCHEMA lbaw2111;

SET search_path TO lbaw2111;

-----------------------------------------
-- DOMAINS
-----------------------------------------

CREATE DOMAIN VALID_EMAIL AS TEXT CHECK(VALUE LIKE '_%@_%.__%');

-----------------------------------------
-- TYPES
-----------------------------------------

CREATE TYPE PROPOSED_TAG_STATES AS ENUM ('PENDING', 'ACCEPTED', 'REJECTED');
CREATE TYPE NOTIFICATION_TYPE AS ENUM ('MESSAGE', 'FEEDBACK', 'COMMENT');

-----------------------------------------
-- Tables
-----------------------------------------

CREATE TABLE country(
  id SERIAL PRIMARY KEY,
  code TEXT NOT NULL UNIQUE,
  name TEXT NOT NULL UNIQUE
);

-----------------------------------------

CREATE TABLE authenticated_user(
  id SERIAL PRIMARY KEY, 
  name TEXT NOT NULL, 
  email VALID_EMAIL UNIQUE, 
  birth_date TIMESTAMP NOT NULL CHECK (CURRENT_TIMESTAMP >= birth_date),
  is_admin BOOLEAN DEFAULT false,
  description TEXT, 
  password TEXT NOT NULL, 
  avatar TEXT, 
  city TEXT, 
  is_suspended BOOLEAN NOT NULL,
  reputation INTEGER NOT NULL DEFAULT 0,
  country_id INTEGER REFERENCES country(id) ON DELETE CASCADE ON UPDATE CASCADE
);


-----------------------------------------

CREATE TABLE suspension(
  id SERIAL PRIMARY KEY,
  reason TEXT NOT NULL,
  start_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  end_time TIMESTAMP NOT NULL CHECK (end_time >= start_time),
  admin_id INTEGER NOT NULL REFERENCES authenticated_user(id) ON DELETE CASCADE ON UPDATE CASCADE,
  user_id INTEGER NOT NULL REFERENCES authenticated_user(id) ON DELETE CASCADE ON UPDATE CASCADE
  CONSTRAINT diff_entities CHECK (admin_id != user_id)
);

-----------------------------------------

CREATE TABLE report(
  id SERIAL PRIMARY KEY, 
  reason TEXT NOT NULL, 
  reported_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
  is_closed BOOLEAN DEFAULT false, 
  reported_id INTEGER NOT NULL REFERENCES authenticated_user(id) ON DELETE CASCADE ON UPDATE CASCADE, 
  reporter_id INTEGER REFERENCES authenticated_user(id) ON UPDATE CASCADE ON DELETE SET NULL,
  CONSTRAINT different_ids CHECK (reporter_id != reported_id)
);

-----------------------------------------

CREATE TABLE tag(
  id SERIAL PRIMARY KEY,
  name TEXT NOT NULL UNIQUE,
  proposed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  state PROPOSED_TAG_STATES NOT NULL DEFAULT 'PENDING',
  user_id INTEGER REFERENCES authenticated_user(id) ON DELETE SET NULL ON UPDATE CASCADE
);

-----------------------------------------

CREATE TABLE area_of_expertise(
  user_id INTEGER REFERENCES authenticated_user(id) ON DELETE CASCADE ON UPDATE CASCADE,
  tag_id  INTEGER REFERENCES tag(id) ON DELETE CASCADE ON UPDATE CASCADE,
  reputation INTEGER NOT NULL,
  PRIMARY KEY (user_id, tag_id)
);

-----------------------------------------

CREATE TABLE favorite_tag(
  user_id INTEGER REFERENCES authenticated_user(id) ON DELETE CASCADE ON UPDATE CASCADE,
  tag_id  INTEGER REFERENCES tag(id) ON DELETE CASCADE ON UPDATE CASCADE,
  PRIMARY KEY (user_id, tag_id)
);


-----------------------------------------

CREATE TABLE message(
  id SERIAL PRIMARY KEY,
  body TEXT NOT NULL,
  published_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  sender_id INTEGER NOT NULL REFERENCES authenticated_user(id) ON DELETE CASCADE ON UPDATE CASCADE,
  receiver_id INTEGER NOT NULL REFERENCES authenticated_user(id) ON DELETE CASCADE ON UPDATE CASCADE,
  is_read BOOLEAN DEFAULT false
);

-----------------------------------------

CREATE TABLE follow(
  follower_id INTEGER REFERENCES authenticated_user(id) ON DELETE CASCADE ON UPDATE CASCADE,
  followed_id INTEGER REFERENCES authenticated_user(id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT own_follows CHECK (follower_id != followed_id),
  PRIMARY KEY(follower_id, followed_id)
);

-----------------------------------------

CREATE TABLE content(
  id SERIAL PRIMARY KEY,
  body TEXT NOT NULL,
  published_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  is_edited BOOLEAN DEFAULT false,
  likes INTEGER DEFAULT 0 CHECK (likes >= 0),
  dislikes INTEGER DEFAULT 0 CHECK (dislikes >= 0),
  author_id INTEGER REFERENCES authenticated_user(id) ON DELETE SET NULL ON UPDATE CASCADE
);

-----------------------------------------

CREATE TABLE article(
  content_id INTEGER PRIMARY KEY REFERENCES content(id) ON DELETE CASCADE ON UPDATE CASCADE, 
  title TEXT NOT NULL, 
  thumbnail TEXT
);

-----------------------------------------

CREATE TABLE comment(
  content_id INTEGER PRIMARY KEY REFERENCES content(id) ON DELETE CASCADE ON UPDATE CASCADE,
  article_id INTEGER NOT NULL REFERENCES article(content_id) ON DELETE CASCADE ON UPDATE CASCADE,
  parent_comment_id INTEGER REFERENCES comment(content_id) ON DELETE CASCADE ON UPDATE CASCADE
);

-----------------------------------------

CREATE TABLE feedback(
  user_id INTEGER REFERENCES authenticated_user(id) ON DELETE SET NULL ON UPDATE CASCADE, 
  content_id INTEGER REFERENCES content(id) ON DELETE CASCADE ON UPDATE CASCADE, 
  is_like BOOLEAN NOT NULL,
  PRIMARY KEY (user_id, content_id)
);

-----------------------------------------

CREATE TABLE article_tag(
  article_id INTEGER REFERENCES article(content_id) ON DELETE CASCADE ON UPDATE CASCADE,
  tag_id INTEGER REFERENCES tag(id) ON DELETE CASCADE ON UPDATE CASCADE,
  PRIMARY KEY(article_id, tag_id)
);


-----------------------------------------

CREATE TABLE notification(
  id SERIAL PRIMARY KEY,
  receiver_id INTEGER NOT NULL REFERENCES authenticated_user(id) ON DELETE CASCADE ON UPDATE CASCADE,
  date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
  is_read BOOLEAN DEFAULT false,
  msg INTEGER REFERENCES message(id) ON DELETE CASCADE ON UPDATE CASCADE,
  fb_giver INTEGER REFERENCES authenticated_user(id) ON DELETE CASCADE ON UPDATE CASCADE,
  rated_content INTEGER REFERENCES content(id) ON DELETE CASCADE ON UPDATE CASCADE,
  new_comment INTEGER REFERENCES comment(content_id) ON DELETE CASCADE ON UPDATE CASCADE,
  type NOTIFICATION_TYPE NOT NULL
);


-----------------------------------------
-- PERFORMANCE INDICES
-----------------------------------------

CREATE INDEX content_author ON content USING hash (author_id);

CREATE INDEX user_messages ON message USING btree (receiver_id, sender_id);

CREATE INDEX notification_receiver ON notification USING hash (receiver_id);


-----------------------------------------
-- FULL-TEXT SEARCH INDICES
-----------------------------------------

ALTER TABLE article ADD COLUMN tsvectors TSVECTOR;

CREATE FUNCTION article_search_update() RETURNS TRIGGER AS $$
DECLARE new_body text = (select body from content where id = NEW.content_id);
DECLARE old_body text = (select body from content where id = OLD.content_id);
BEGIN
  IF TG_OP = 'INSERT' THEN
    NEW.tsvectors = (
      setweight(to_tsvector('english', NEW.title), 'A') ||
      setweight(to_tsvector('english', new_body), 'B')
    );
  END IF;

  IF TG_OP = 'UPDATE' THEN
      IF (NEW.title <> OLD.title OR new_body <> old_body) THEN
        NEW.tsvectors = (
          setweight(to_tsvector('english', NEW.title), 'A') ||
          setweight(to_tsvector('english', new_body), 'B')
        );
      END IF;
  END IF;

  RETURN NEW;
END $$
LANGUAGE plpgsql;

CREATE TRIGGER article_search_update
  BEFORE INSERT OR UPDATE ON article
  FOR EACH ROW
  EXECUTE PROCEDURE article_search_update();

CREATE INDEX article_search ON article USING GIST (tsvectors);

-----------------------------------------

ALTER TABLE authenticated_user ADD COLUMN tsvectors TSVECTOR;

CREATE FUNCTION user_search_update() RETURNS TRIGGER AS $$
BEGIN
  IF TG_OP = 'INSERT' THEN
    NEW.tsvectors = (
      setweight(to_tsvector('english', NEW.name), 'A') ||
      setweight(to_tsvector('english', NEW.email), 'B')
    );
  END IF;

  IF TG_OP = 'UPDATE' THEN
      IF (NEW.name <> OLD.name OR NEW.email <> OLD.email) THEN
        NEW.tsvectors = (
          setweight(to_tsvector('english', NEW.name), 'A') ||
          setweight(to_tsvector('english', NEW.email), 'B')
        );
      END IF;
  END IF;

  RETURN NEW;
END $$
LANGUAGE plpgsql;

CREATE TRIGGER user_search_update
  BEFORE INSERT OR UPDATE ON authenticated_user
  FOR EACH ROW
  EXECUTE PROCEDURE user_search_update();

CREATE INDEX user_search ON authenticated_user USING GIST (tsvectors);

-----------------------------------------
-- TRIGGERS
-----------------------------------------

/*
Trigger to update likes/dislikes of a content when feedback is given,
creates a notification on that feedback and updates user reputation, as well as its areas of expertise.
*/
CREATE FUNCTION feedback_content() RETURNS TRIGGER AS
$BODY$
DECLARE author_id authenticated_user.id%type = (
  SELECT author_id FROM content INNER JOIN authenticated_user ON (content.author_id = authenticated_user.id)
  WHERE content.id = NEW.content_id
);
DECLARE feedback_value INTEGER = 1;
BEGIN
    IF (NOT NEW.is_like)
        THEN feedback_value = -1;
    END IF;

    IF (NEW.is_like) THEN
        UPDATE content SET likes = likes + 1 WHERE id = NEW.content_id;
    ELSE 
        UPDATE content SET dislikes = dislikes + 1 WHERE id = NEW.content_id;
    END IF;
    
    UPDATE authenticated_user SET reputation = reputation + feedback_value
    WHERE id = author_id;

    UPDATE area_of_expertise SET reputation = reputation + feedback_value
    WHERE 
        user_id = author_id AND 
        tag_id IN (
			SELECT tag_id FROM article_tag
    		WHERE article_id=NEW.content_id
		);

    INSERT INTO notification(receiver_id, is_read, msg, fb_giver, rated_content, new_comment, type)
    VALUES (author_id, FALSE, NULL, NEW.user_id, NEW.content_id, NULL, 'FEEDBACK');

    RETURN NULL;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER feedback_content
    AFTER INSERT ON feedback
    FOR EACH ROW
    EXECUTE PROCEDURE feedback_content();

-----------------------------------------

-- Trigger to remove like/dislike of a content when feedback on it is removed and to update authenticated user reputation, as well as its areas of expertise
CREATE FUNCTION remove_feedback() RETURNS TRIGGER AS
$BODY$
DECLARE author_id authenticated_user.id%type = (SELECT author_id FROM content INNER JOIN authenticated_user ON (content.author_id = authenticated_user.id) WHERE content.id = OLD.content_id);
DECLARE feedback_value INTEGER = -1;
BEGIN
    IF (NOT OLD.is_like)
        THEN feedback_value = 1;
    END IF;

    IF (OLD.is_like) THEN
        UPDATE content SET likes = likes - 1 WHERE id = OLD.content_id;
    ELSE 
        UPDATE content SET dislikes = dislikes - 1 WHERE id = OLD.content_id;
    END IF;
    
    UPDATE authenticated_user SET reputation = reputation + feedback_value
    WHERE id = author_id;

    UPDATE area_of_expertise SET reputation = reputation + feedback_value
    WHERE 
        user_id = author_id AND 
        tag_id IN (
			SELECT tag_id FROM article_tag
    		WHERE article_id=OLD.content_id
		);

    RETURN NULL;
END
$BODY$

LANGUAGE plpgsql;

CREATE TRIGGER remove_feedback
    AFTER DELETE ON feedback
    FOR EACH ROW
    EXECUTE PROCEDURE remove_feedback();

-----------------------------------------

-- Trigger to prevent users from liking or disliking his own content (articles or comments)
CREATE FUNCTION check_feedback() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF (NEW.user_id in (
        SELECT content.author_id 
        FROM content 
        WHERE content.id = NEW.content_id)) THEN
            RAISE EXCEPTION 'You cannot give feedback on your own content';
    END IF;
    RETURN NEW;
END;
$BODY$

LANGUAGE plpgsql;

CREATE TRIGGER check_feedback
    BEFORE INSERT ON feedback
    FOR EACH ROW
    EXECUTE PROCEDURE check_feedback();

-----------------------------------------

-- Trigger to add notification when a message is sent form an user to another or to remove, in case of being read
CREATE FUNCTION message_sent_notification() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF (NEW.is_read) THEN
        DELETE FROM notification WHERE msg = NEW.id;
    ELSE 
        INSERT INTO notification(receiver_id, is_read, msg, fb_giver, rated_content, new_comment, type) 
            VALUES (NEW.receiver_id, FALSE, NEW.id, NULL, NULL, NULL, 'MESSAGE');
    END IF;
    RETURN NULL;
END
$BODY$

LANGUAGE plpgsql;

CREATE TRIGGER message_sent_notification
    AFTER INSERT ON message
    FOR EACH ROW
    EXECUTE PROCEDURE message_sent_notification();

-----------------------------------------

/*
Trigger to delete all the information about an article that was deleted
it just needs to delete the content represented by that article 
since its that deletion is cascaded to the comments and other elements of the article
*/
CREATE FUNCTION delete_article() RETURNS TRIGGER AS
$BODY$
BEGIN 
    DELETE FROM content WHERE content.id = OLD.content_id;
    RETURN OLD;
END
$BODY$

LANGUAGE plpgsql;

CREATE TRIGGER delete_article
    AFTER DELETE ON article
    FOR EACH ROW
    EXECUTE PROCEDURE delete_article();


-----------------------------------------

/*
Trigger to delete the respective content of a comment when a comment
is deleted. */
CREATE FUNCTION delete_comment() RETURNS TRIGGER AS
$BODY$
BEGIN 
    DELETE FROM content WHERE content.id = OLD.content_id;
    RETURN OLD;
END
$BODY$

LANGUAGE plpgsql;


CREATE TRIGGER delete_comment
    AFTER DELETE ON comment
    FOR EACH ROW
    EXECUTE PROCEDURE delete_comment();

-----------------------------------------

-- Trigger to prevent an article from having an unaccepted tag or more than 3 tags
CREATE FUNCTION add_article_tag_check() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF ((SELECT state FROM tag WHERE NEW.tag_id = tag.id) <> 'ACCEPTED')
    THEN
        RAISE EXCEPTION 'You cannot associate an article to an Unaccepted tag';
    END IF;
    
    IF ((SELECT COUNT(*) FROM article_tag WHERE article_id = NEW.article_id)) >= 3
    THEN
        RAISE EXCEPTION 'You cannot associate anymore tags to this article';
    END IF;
    RETURN NEW;
END
$BODY$

LANGUAGE plpgsql;

CREATE TRIGGER add_article_tag_check
    BEFORE INSERT ON article_tag
    FOR EACH ROW
    EXECUTE PROCEDURE add_article_tag_check();

-----------------------------------------

/*
Trigger to create an area of expertise when an article_tag is inserted,
in case the author the article doesn’t have it yet
*/
CREATE FUNCTION create_area_expertise() RETURNS TRIGGER AS
$BODY$
DECLARE author_id INTEGER = (
    SELECT author_id FROM content WHERE id = NEW.article_id
);
BEGIN
    IF NEW.tag_id NOT IN (
        SELECT tag_id FROM area_of_expertise where user_id = author_id
    )
    THEN
        INSERT INTO area_of_expertise VALUES(author_id, NEW.tag_id, 0);
	END IF;
	RETURN NULL;
END
$BODY$

LANGUAGE plpgsql;

CREATE TRIGGER create_area_expertise
    AFTER INSERT ON article_tag
    FOR EACH ROW
    EXECUTE PROCEDURE create_area_expertise();

-----------------------------------------

-- Triggers to update the *is_edited* flag when a content's body or an article's title is updated
CREATE FUNCTION set_content_is_edited() RETURNS TRIGGER AS
$BODY$
BEGIN
    UPDATE content SET is_edited = TRUE
    WHERE id = NEW.id;
	RETURN NULL;
END
$BODY$

LANGUAGE plpgsql;

CREATE TRIGGER set_content_is_edited
    AFTER UPDATE ON content
    FOR EACH ROW
    WHEN (OLD.body IS DISTINCT FROM NEW.body)
    EXECUTE PROCEDURE set_content_is_edited();

-----------------------------------------

-- Trigger to mark the content as edited when an article's title is changed
CREATE FUNCTION set_article_is_edited() RETURNS TRIGGER AS
$BODY$
BEGIN
    UPDATE content SET is_edited = TRUE
    WHERE id = NEW.content_id;
	RETURN NULL;
END
$BODY$

LANGUAGE plpgsql;

CREATE TRIGGER set_article_is_edited
    AFTER UPDATE ON article
    FOR EACH ROW
    WHEN (OLD.title IS DISTINCT FROM NEW.title)
    EXECUTE PROCEDURE set_article_is_edited();
  
-----------------------------------------

-- Trigger to put authenticated_user flag to true if a suspension on him is created
CREATE FUNCTION is_suspended_flag_true() RETURNS TRIGGER AS
$BODY$
BEGIN
    UPDATE authenticated_user SET is_suspended = true
    WHERE id = NEW.user_id;
	RETURN NEW;
END
$BODY$

LANGUAGE plpgsql;

CREATE TRIGGER is_suspended_flag_true
    AFTER INSERT ON suspension
    FOR EACH ROW
    EXECUTE PROCEDURE is_suspended_flag_true();


-----------------------------------------

-- Trigger to create a notification when a comment is created

CREATE FUNCTION create_comment_notification() RETURNS TRIGGER AS
$BODY$
DECLARE article_author INTEGER = (
  SELECT id FROM content WHERE id = NEW.article_id
);
DECLARE parent_author INTEGER = (
  SELECT id FROM content WHERE id = NEW.parent_comment_id
);
BEGIN
  IF parent_author IS NULL THEN
    INSERT INTO notification(receiver_id, is_read, msg, fb_giver, rated_content, new_comment, type) 
        VALUES (article_author, FALSE, NULL, NULL, NULL, NEW.content_id, 'COMMENT');
  ELSE
    INSERT INTO notification(receiver_id, is_read, msg, fb_giver, rated_content, new_comment, type) 
        VALUES (parent_author, FALSE, NULL, NULL, NULL, NEW.content_id, 'COMMENT');
  END IF;
  RETURN NULL;
END
$BODY$

LANGUAGE plpgsql;
CREATE TRIGGER create_comment_notification
    AFTER INSERT ON comment
    FOR EACH ROW
    EXECUTE PROCEDURE create_comment_notification();

INSERT INTO country (code,name)
VALUES
  ('NG','Nigeria'),
  ('PH','Philippines'),
  ('CN','China'),
  ('PL','Poland'),
  ('UA','Ukraine'),
  ('AL','Albania'),
  ('BG','Bulgaria'),
  ('CZ','Czech Republic'),
  ('CU','Cuba'),
  ('FM','Micronesia'),
  ('PT','Portugal'),
  ('SY','Syria'),
  ('LU','Luxembourg'),
  ('ID','Indonesia'),
  ('GR','Greece'),
  ('US','United States'),
  ('SV', 'El Salvador');

  
  
INSERT INTO authenticated_user (name,email,birth_date,is_admin,description,password,avatar,city,is_suspended,country_id)
VALUES
  ('Rui Alves', 'rui@gmail.com', TO_TIMESTAMP('2003-03-23', 'YYYY-MM-DD'), true, 'o maior debugger', '$2a$12$R7eIoU2USu.eQinxW65F6.nX4WTh274CP5jQruGGpGzV0YzerD4gS', 'https://rui-image.com', 'Tchabes', false, 11),
  ('Jackson Hatrue','penatibus.et@protonmail.org',TO_TIMESTAMP('1970-02-04', 'YYYY-MM-DD'),true,'purus mauris a nunc. In at pede. Cras vulputate velit','neque','risus. Donec egestas.','Huntly',false,1),
  ('Tatyana Hunter','duis.a@icloud.ca',TO_TIMESTAMP('2014-10-29', 'YYYY-MM-DD'),true,'dignissim lacus. Aliquam rutrum lorem ac risus. Morbi metus. Vivamus','Integer','torquent per conubia','Bad Neuenahr-Ahrweiler',false,3),
  ('Sigourney Garcia','cras.lorem.lorem@outlook.edu',TO_TIMESTAMP('2007-05-03', 'YYYY-MM-DD'),false,'bibendum. Donec felis orci, adipiscing falsen, luctus sit amet, faucibus','ut','eu odio tristique','Galway',true,4),
  ('Melinda Lawson','aliquam@protonmail.org',TO_TIMESTAMP('1970-09-29', 'YYYY-MM-DD'),false,'ipsum dolor sit amet, consectetuer adipiscing elit. Curabitur sed tortor.','eu','ligula. Nullam enim.','Gorzów Wielkopolski',false,7),
  ('Gavin Rosa','odio.phasellus@yahoo.net',TO_TIMESTAMP('1976-10-13', 'YYYY-MM-DD'),false,'nunc nulla vulputate dui, nec tempus mauris erat eget ipsum.','enim.','est, congue a,','Hebei',true,2),
  ('Malcolm Schwartz','ullamcorper.eu@yahoo.edu',TO_TIMESTAMP('1990-11-15', 'YYYY-MM-DD'),false,'Proin mi. Aliquam gravida mauris ut mi. Duis risus odio,','Mauris','libero mauris, aliquam','Colorado Springs',false,6),
  ('Christen Faulkner','aliquam.nisl@yahoo.org',TO_TIMESTAMP('1954-09-28', 'YYYY-MM-DD'),false,'sagittis. Nullam vitae diam. Proin dolor. Nulla semper tellus id','a,','mauris a nunc.','Kaliningrad',false,3),
  ('Devin Kaufman','urna@google.net',TO_TIMESTAMP('1977-06-05', 'YYYY-MM-DD'),false,'vel lectus. Cum sociis natoque penatibus et magnis dis parturient','Vivamus','Nullam ut nisi','Chesapeake',true,2),
  ('Tad falseel','lacus@google.ca',TO_TIMESTAMP('1970-01-22', 'YYYY-MM-DD'),false,'in, cursus et, eros. Proin ultrices. Duis volutpat nunc sit','molestie','Nam interdum enim','Campina Grande',false,7),
  ('Hall May','turpis.vitae.purus@google.ca',TO_TIMESTAMP('2016-09-12', 'YYYY-MM-DD'),false,'ante blandit viverra. Donec tempus, lorem fringilla ornare placerat, orci','dolor.','in, tempus eu,','Cockburn',false,1),
  ('Baxter Hansen','ipsum@aol.edu',TO_TIMESTAMP('2008-10-14', 'YYYY-MM-DD'),false,'felis. Nulla tempor augue ac ipsum. Phasellus vitae mauris sit','nascetur','aliquam iaculis, lacus','Sechura',false,3),
  ('Scarlet Chapman','convallis.erat@hotmail.com',TO_TIMESTAMP('2008-09-07', 'YYYY-MM-DD'),false,'neque. Morbi quis urna. Nunc quis arcu vel quam dignissim','ultricies','dignissim magna a','Magadan',true,6),
  ('Darryl Noel','vulputate.dui.nec@protonmail.com',TO_TIMESTAMP('1963-12-22', 'YYYY-MM-DD'),false,'odio. Nam interdum enim non nisi. Aenean eget metus. In','at,','tincidunt vehicula risus.','Canoas',true,5),
  ('Jerome Jacobson','tincidunt@google.org',TO_TIMESTAMP('1955-08-20', 'YYYY-MM-DD'),false,'arcu imperdiet ullamcorper. Duis at lacus. Quisque purus sapien, gravida','a','purus, in molestie','Arequipa',true,2),
  ('Teegan Hayes','in@protonmail.net',TO_TIMESTAMP('1970-11-18', 'YYYY-MM-DD'),false,'Donec egestas. Aliquam nec enim. Nunc ut erat. Sed nunc','cubilia','porttitor eros nec','Chillán Viejo',true,7),
  ('Cecilia Quinn','arcu@hotmail.ca',TO_TIMESTAMP('2020-08-22', 'YYYY-MM-DD'),false,'cursus, diam at pretium aliquet, metus urna convallis erat, eget','faucibus','odio a purus.','Blenheim',true,2),
  ('Geoffrey Guerra','dictum.proin@aol.net',TO_TIMESTAMP('1995-09-05', 'YYYY-MM-DD'),false,'euismod urna. Nullam lobortis quam a felis ullamcorper viverra. Maecenas','sapien,','Sed eu nibh','Pfungstadt',true,4),
  ('Anastasia Jones','nisl@yahoo.couk',TO_TIMESTAMP('1998-09-28', 'YYYY-MM-DD'),false,'urna. Ut tincidunt vehicula risus. Nulla eget metus eu erat','non','congue turpis. In','Iseyin',false,1),
  ('Natalie Perez','ornare.elit.elit@aol.couk',TO_TIMESTAMP('1979-11-04', 'YYYY-MM-DD'),false,'Phasellus fermentum convallis ligula. Donec luctus aliquet odio. Etiam ligula','egestas','volutpat ornare, facilisis','Stevenage',true,2),
  ('Althea Michael','pede.nunc.sed@aol.edu',TO_TIMESTAMP('1978-05-03', 'YYYY-MM-DD'),false,'mauris id sapien. Cras dolor dolor, tempus non, lacinia at,','risus.','non nisi. Aenean','Sakhalin',true,5);

 
INSERT INTO suspension (reason,start_time,end_time,admin_id,user_id)
VALUES
  ('vel lectus. Cum sociis natoque',TO_TIMESTAMP('2019-04-23', 'YYYY-MM-DD'),TO_TIMESTAMP('2021-07-13', 'YYYY-MM-DD'),2,3),
  ('ipsum ac mi eleifend egestas.',TO_TIMESTAMP('2019-02-07', 'YYYY-MM-DD'),TO_TIMESTAMP('2021-11-18', 'YYYY-MM-DD'),1,5),
  ('Aliquam rutrum lorem ac risus.',TO_TIMESTAMP('2019-05-19', 'YYYY-MM-DD'),TO_TIMESTAMP('2020-06-27', 'YYYY-MM-DD'),1,8),
  ('Etiam ligula tortor, dictum eu,',TO_TIMESTAMP('2019-08-06', 'YYYY-MM-DD'),TO_TIMESTAMP('2021-10-30', 'YYYY-MM-DD'),2,12);


INSERT INTO report (reason,reported_at,is_closed,reported_id,reporter_id)
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


INSERT INTO tag (name,proposed_at,state,user_id)
VALUES
  ('Anime', TO_TIMESTAMP('2018-11-25', 'YYYY-MM-DD'), 'ACCEPTED', 1),
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
  ('Dean Macdonald',TO_TIMESTAMP('2019-09-08', 'YYYY-MM-DD'),'ACCEPTED',16),
  ('Hector Giles',TO_TIMESTAMP('2019-09-29', 'YYYY-MM-DD'),'REJECTED',8), 
  ('Mufutau Fisher',TO_TIMESTAMP('2019-03-09', 'YYYY-MM-DD'),'ACCEPTED',19), 
  ('Avye Wolfe',TO_TIMESTAMP('2019-05-11', 'YYYY-MM-DD'),'REJECTED',10),  
  ('Noah Holt',TO_TIMESTAMP('2019-11-15', 'YYYY-MM-DD'),'ACCEPTED',5), 
  ('Olga Aguirre',TO_TIMESTAMP('2019-04-04', 'YYYY-MM-DD'),'PENDING',1), 
  ('Hector Richard',TO_TIMESTAMP('2019-10-28', 'YYYY-MM-DD'),'PENDING',8); 


INSERT INTO area_of_expertise (user_id,tag_id,reputation)
VALUES
  (6,2,0),
  (3,9,0),
  (18,14,0),
  (20,9,0),
  (12,16,0),
  (1,16,0),
  (13,18,0),
  (4,2,0),
  (13,14,0),
  (7,2,0),
  (15,14,0),
  (10,18,0),
  (3,14,0),
  (4,4,0),
  (8,18,0),
  (6,9,0),
  (10,4,0),
  (3,18,0),
  (3,5,0),
  (2,4,0);


INSERT INTO favorite_tag (user_id,tag_id)
VALUES
  (1,1),
  (20,2),
  (16,18),
  (5,4),
  (19,16),
  (11,9),
  (14,18),
  (20,14),
  (6,5),
  (11,14),
  (15,2),
  (7,4),
  (17,14),
  (17,16),
  (17,2),
  (4,14),
  (18,14),
  (15,5),
  (13,18),
  (9,18),
  (20,4);


INSERT INTO message (body,published_at,sender_id,receiver_id,is_read)
VALUES
  ('ola individuo', TO_TIMESTAMP('2021-04-16', 'YYYY-MM-DD'), 1, 2, false),
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


INSERT INTO follow (follower_id,followed_id)
VALUES
  (1,2),
  (4,13),
  (8,15),
  (5,19),
  (9,16),
  (2,13),
  (4,12),
  (5,12),
  (8,18),
  (7,17),
  (3,12),
  (2,12),
  (6,17),
  (5,17),
  (9,12),
  (6,15),
  (2,14),
  (8,12),
  (5,15);


INSERT INTO content (body,published_at,is_edited,author_id)
VALUES
  ('ola este artigo e do rui', TO_TIMESTAMP('2021-04-03', 'YYYY-MM-DD'), false, 1),
  ('ante ipsum primis in faucibus orci luctus et ultrices posuere',TO_TIMESTAMP('2021-03-02', 'YYYY-MM-DD'),false,19),
  ('amet, consectetuer adipiscing elit. Curabitur sed tortor. Integer aliquam adipiscing',TO_TIMESTAMP('2021-01-22', 'YYYY-MM-DD'),false,19),
  ('dictum magna. Ut tincidunt orci quis lectus. Nullam suscipit, est',TO_TIMESTAMP('2021-05-19', 'YYYY-MM-DD'),false,9),
  ('fringilla est. Mauris eu turpis. Nulla aliquet. Proin velit. Sed',TO_TIMESTAMP('2021-05-23', 'YYYY-MM-DD'),true,8),
  ('vulputate eu, odio. Phasellus at augue id ante dictum cursus.',TO_TIMESTAMP('2020-12-06', 'YYYY-MM-DD'),false,19),
  ('pede. Praesent eu dui. Cum sociis natoque penatibus et magnis',TO_TIMESTAMP('2021-10-27', 'YYYY-MM-DD'),false,12),
  ('Cras interdum. Nunc sollicitudin commodo ipsum. Suspendisse non leo. Vivamus',TO_TIMESTAMP('2021-03-04', 'YYYY-MM-DD'),false,17),
  ('vel, vulputate eu, odio. Phasellus at augue id ante dictum',TO_TIMESTAMP('2021-03-11', 'YYYY-MM-DD'),false,19),
  ('sed pede. Cum sociis natoque penatibus et magnis dis parturient',TO_TIMESTAMP('2021-08-07', 'YYYY-MM-DD'),true,8),
  ('O artigo do rui é muito bom',TO_TIMESTAMP('2021-04-09', 'YYYY-MM-DD'),true,16),
  ('feugiat. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aliquam',TO_TIMESTAMP('2021-09-05', 'YYYY-MM-DD'),true,6),
  ('sapien, gravida non, sollicitudin a, malesuada id, erat. Etiam vestibulum',TO_TIMESTAMP('2021-02-13', 'YYYY-MM-DD'),false,10),
  ('nunc. In at pede. Cras vulputate velit eu sem. Pellentesque',TO_TIMESTAMP('2021-09-15', 'YYYY-MM-DD'),false,2),
  ('urna. Ut tincidunt vehicula risus. Nulla eget metus eu erat',TO_TIMESTAMP('2021-05-27', 'YYYY-MM-DD'),false,18),
  ('in, tempus eu, ligula. Aenean euismod mauris eu elit. Nulla',TO_TIMESTAMP('2021-04-25', 'YYYY-MM-DD'),true,12),
  ('enim, gravida sit amet, dapibus id, blandit at, nisi. Cum',TO_TIMESTAMP('2021-07-11', 'YYYY-MM-DD'),true,14),
  ('posuere, enim nisl elementum purus, accumsan interdum libero dui nec',TO_TIMESTAMP('2021-01-02', 'YYYY-MM-DD'),false,2),
  ('mauris. Integer sem elit, pharetra ut, pharetra sed, hendrerit a,',TO_TIMESTAMP('2021-03-09', 'YYYY-MM-DD'),false,13),
  ('a, magna. Lorem ipsum dolor sit amet, consectetuer adipiscing elit.',TO_TIMESTAMP('2021-07-18', 'YYYY-MM-DD'),true,10),
  ('enim diam vel arcu. Curabitur ut odio vel est tempor',TO_TIMESTAMP('2021-07-04', 'YYYY-MM-DD'),true,14);


INSERT INTO article (content_id,title,thumbnail)
VALUES
  (1,'Artigo do rui', 'https://artigo-do-rui.com'),
  (2,'nunc id enim. Curabitur','sem eget massa. Suspendisse eleifend.'),
  (3,'Fusce fermentum fermentum arcu.','in aliquet lobortis, nisi nibh'),
  (4,'eget varius ultrices, mauris','eget metus. In nec orci.'),
  (5,'Curabitur vel lectus. Cum','Donec luctus aliquet odio. Etiam'),
  (6,'enim. Suspendisse aliquet, sem','lorem, vehicula et, rutrum eu,'),
  (7,'urna. Nullam lobortis quam','urna, nec luctus felis purus'),
  (8,'feugiat placerat velit. Quisque','sit amet luctus vulputate, nisi'),
  (9,'varius et, euismod et,','et malesuada fames ac turpis'),
  (10,'mauris blandit mattis. Cras','aliquet nec, imperdiet nec, leo.');


INSERT INTO comment (content_id,article_id,parent_comment_id)
VALUES
  (11,1,NULL),
  (12,4,NULL),
  (13,10,NULL),
  (14,6,NULL),
  (15,2,NULL),
  (16,4,11),
  (17,4,12),
  (18,2,12),
  (19,7,12),
  (20,4,13);


INSERT INTO article_tag (article_id,tag_id)
VALUES
  (1, 1),
  (7,10),
  (4,5),
  (2,5),
  (6,3),
  (5,15),
  (2,19),
  (8,5),
  (8,15),
  (1,15),
  (10,6),
  (7,15),
  (8,17),
  (6,5),
  (10,5),
  (4,3),
  (3,15),
  (3,17),
  (9,5),
  (6,17),
  (5,19);


INSERT INTO feedback (user_id,content_id,is_like)
VALUES
  (7, 1, true),
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
