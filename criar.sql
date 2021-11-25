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

CREATE TABLE "country"(
  id SERIAL PRIMARY KEY,
  code TEXT NOT NULL UNIQUE,
  name TEXT NOT NULL UNIQUE
);

-----------------------------------------


CREATE TABLE "authenticated_user"(
  id SERIAL PRIMARY KEY, 
  name TEXT NOT NULL, 
  email VALID_EMAIL NOT NULL UNIQUE, 
  birth_date TIMESTAMP NOT NULL CHECK (CURRENT_TIMESTAMP >= birth_date),
  admin BOOLEAN DEFAULT false,
  description TEXT, 
  password TEXT NOT NULL, 
  avatar TEXT, 
  city TEXT, 
  is_suspended BOOLEAN NOT NULL,
  reputation INTEGER NOT NULL DEFAULT 0,
  country_id INTEGER REFERENCES "country"(id) ON DELETE CASCADE ON UPDATE CASCADE
);


-----------------------------------------

CREATE TABLE "suspension"(
  id SERIAL PRIMARY KEY,
  reason TEXT NOT NULL,
  start_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  end_time TIMESTAMP NOT NULL CHECK (end_time >= start_time),
  admin_id INTEGER NOT NULL REFERENCES "authenticated_user"(id) ON DELETE CASCADE ON UPDATE CASCADE,
  user_id INTEGER NOT NULL REFERENCES "authenticated_user"(id) ON DELETE CASCADE ON UPDATE CASCADE
  CONSTRAINT diff_entities CHECK (admin_id != user_id)
);

-----------------------------------------

CREATE TABLE "report"(
  id SERIAL PRIMARY KEY, 
  reason TEXT NOT NULL, 
  reported_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
  is_closed BOOLEAN DEFAULT false, 
  reported_id INTEGER NOT NULL REFERENCES "authenticated_user"(id) ON DELETE CASCADE ON UPDATE CASCADE, 
  reporter_id INTEGER REFERENCES "authenticated_user"(id) ON UPDATE CASCADE,
  CONSTRAINT different_ids CHECK (reporter_id != reported_id)
);

-----------------------------------------

CREATE TABLE "tag"(
  id SERIAL PRIMARY KEY,
  name TEXT NOT NULL UNIQUE,
  proposed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  state PROPOSED_TAG_STATES NOT NULL DEFAULT 'PENDING',
  user_id INTEGER NOT NULL REFERENCES "authenticated_user"(id) ON DELETE CASCADE ON UPDATE CASCADE
);

-----------------------------------------

CREATE TABLE "area_of_expertise"(
  user_id INTEGER REFERENCES "authenticated_user"(id) ON DELETE CASCADE ON UPDATE CASCADE,
  tag_id  INTEGER REFERENCES "tag"(id) ON DELETE CASCADE ON UPDATE CASCADE,
  reputation INTEGER NOT NULL,
  PRIMARY KEY (user_id, tag_id)
);

-----------------------------------------

CREATE TABLE "favorite_tag"(
  user_id INTEGER REFERENCES "authenticated_user"(id) ON DELETE CASCADE ON UPDATE CASCADE,
  tag_id  INTEGER REFERENCES "tag"(id) ON DELETE CASCADE ON UPDATE CASCADE,
  PRIMARY KEY (user_id, tag_id)
);


-----------------------------------------

CREATE TABLE "message"(
  id SERIAL PRIMARY KEY,
  body TEXT NOT NULL,
  published_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  sender_id INTEGER NOT NULL REFERENCES "authenticated_user"(id) ON DELETE CASCADE ON UPDATE CASCADE,
  receiver_id INTEGER NOT NULL REFERENCES "authenticated_user"(id) ON DELETE CASCADE ON UPDATE CASCADE,
  is_read BOOLEAN DEFAULT false
);

-----------------------------------------

CREATE TABLE "follow"(
  follower_id INTEGER REFERENCES "authenticated_user"(id) ON DELETE CASCADE ON UPDATE CASCADE,
  followed_id INTEGER REFERENCES "authenticated_user"(id) ON DELETE CASCADE ON UPDATE CASCADE,
  PRIMARY KEY(follower_id, followed_id)
);

-----------------------------------------

CREATE TABLE "content"(
  id SERIAL PRIMARY KEY,
  body TEXT NOT NULL,
  published_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  is_edited BOOLEAN DEFAULT false,
  likes INTEGER CHECK (likes >= 0),
  dislikes INTEGER CHECK (dislikes >= 0),
  author_id INTEGER NOT NULL REFERENCES "authenticated_user"(id) ON DELETE CASCADE ON UPDATE CASCADE -- trigger must update user instead of deleting it
);

-----------------------------------------

CREATE TABLE "article"(
  content_id INTEGER PRIMARY KEY REFERENCES "content"(id) ON DELETE CASCADE ON UPDATE CASCADE, 
  title TEXT NOT NULL, 
  thumbnail TEXT
);

-----------------------------------------

CREATE TABLE "comment"(
  content_id INTEGER PRIMARY KEY REFERENCES "content"(id) ON DELETE CASCADE ON UPDATE CASCADE,
  article_id INTEGER NOT NULL REFERENCES "article"(content_id) ON DELETE CASCADE ON UPDATE CASCADE,
  parent_comment_id INTEGER REFERENCES "comment"(content_id) ON UPDATE CASCADE
);

-----------------------------------------

CREATE TABLE "feedback"(
  user_id INTEGER REFERENCES "authenticated_user"(id) ON DELETE CASCADE ON UPDATE CASCADE, 
  content_id INTEGER REFERENCES "content"(id) ON DELETE CASCADE ON UPDATE CASCADE, 
  is_like BOOLEAN NOT NULL,
  PRIMARY KEY (user_id, content_id)
);

-----------------------------------------

CREATE TABLE "article_tag"(
  article_id INTEGER REFERENCES "article"(content_id) ON DELETE CASCADE ON UPDATE CASCADE,
  tag_id INTEGER REFERENCES "tag"(id) ON DELETE CASCADE ON UPDATE CASCADE,
  PRIMARY KEY(article_id, tag_id)
);

-----------------------------------------

CREATE TABLE "notification"(
  id SERIAL PRIMARY KEY,
  receiver_id INTEGER NOT NULL REFERENCES "authenticated_user"(id),
  date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
  is_read BOOLEAN DEFAULT false,
  msg INTEGER REFERENCES "message"(id),
  fb_giver INTEGER REFERENCES "authenticated_user"(id),
  rated_content INTEGER REFERENCES "content"(id),
  new_comment INTEGER REFERENCES "comment"(content_id),
  type NOTIFICATION_TYPE NOT NULL
);


-----------------------------------------
-- INDICES
-----------------------------------------

CREATE INDEX content_author ON content USING hash (author_id);

CREATE INDEX user_messages ON message USING btree (receiver_id, sender_id);

CREATE INDEX notification_receiver ON notification USING hash (receiver_id);


-----------------------------------------
-- FULL-TEXT SEARCH INDICES
-----------------------------------------

ALTER TABLE "article" ADD COLUMN tsvectors TSVECTOR;

CREATE FUNCTION article_search_update() RETURNS TRIGGER AS $$
DECLARE new_body text = (select body from "content" where id = NEW.content_id);
BEGIN
  IF TG_OP = 'INSERT' THEN
    NEW.tsvectors = (
      setweight(to_tsvector('english', NEW.title), 'A') ||
      setweight(to_tsvector('english', new_body), 'B')
    );
  END IF;

  IF TG_OP = 'UPDATE' THEN
      IF (NEW.title <> OLD.title OR new_body <> OLD.text) THEN
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
  BEFORE INSERT OR UPDATE ON "article"
  FOR EACH ROW
  EXECUTE PROCEDURE article_search_update();

CREATE INDEX article_search ON "article" USING GIST (tsvectors);



ALTER TABLE "authenticated_user" ADD COLUMN tsvectors TSVECTOR;

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
  BEFORE INSERT OR UPDATE ON "authenticated_user"
  FOR EACH ROW
  EXECUTE PROCEDURE user_search_update();

CREATE INDEX user_search ON "authenticated_user" USING GIST (tsvectors);

-----------------------------------------
