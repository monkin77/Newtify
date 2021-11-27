-- Trigger to update likes/dislikes of a content when feedback is given (inserted into feedback table)
-- it also adds a notification
CREATE OR REPLACE FUNCTION feedback_content() RETURNS TRIGGER AS
$BODY$
DECLARE author_id authenticated_user.id%type = (SELECT author_id FROM content INNER JOIN authenticated_user ON (content.author_id = authenticated_user.id) WHERE content.id = NEW.content_id);
BEGIN
    IF (NEW.is_like) THEN
        UPDATE "content" SET likes = likes + 1 WHERE id = NEW.content_id;
        
        UPDATE "authenticated_user" SET reputation = reputation + 1 
            WHERE id = author_id;
    ELSE 
        UPDATE "content" SET dislikes = dislikes + 1 WHERE id = NEW.content_id;
        
        UPDATE "authenticated_user" SET reputation = reputation - 1
            WHERE id = author_id;
    END IF;

    INSERT INTO "notification"(date, receiver_id, is_read, msg, fb_giver, rated_content, new_comment, type) VALUES (CURRENT_TIMESTAMP, author_id, FALSE, NULL, NEW.user_id, NULL, NULL, 'FEEDBACK');

    RETURN NULL;
END
$BODY$
LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS feedback_content ON "feedback";
CREATE TRIGGER feedback_content
    AFTER INSERT ON feedback
    FOR EACH ROW
    EXECUTE PROCEDURE feedback_content();




-- Trigger to remove like/dislike of a content when feedback on it is removed and to update authenticated user reputation
CREATE OR REPLACE FUNCTION remove_feedback() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF (OLD.is_like) THEN
        UPDATE "content" SET likes = likes - 1 WHERE id = OLD.user_id;
        
        UPDATE "authenticated_user" SET reputation = reputation - 1 
            WHERE id = (SELECT author_id FROM content INNER JOIN authenticated_user ON (content.author_id = authenticated_user.id) WHERE content.id = OLD.content_id);

    ELSE 
        UPDATE "authenticated_user" SET reputation = reputation + 1 
            WHERE id = (SELECT author_id FROM content INNER JOIN authenticated_user ON (content.author_id = authenticated_user.id) WHERE content.id = OLD.content_id);
        
        UPDATE "content" SET dislikes = dislikes - 1 WHERE id = OLD.user_id;
    END IF;
    RETURN NULL;
END
$BODY$

LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS remove_feedback ON "feedback";
CREATE TRIGGER remove_feedback
    AFTER DELETE ON feedback
    FOR EACH ROW
    EXECUTE PROCEDURE remove_feedback();




-- Trigger to prevent user from like or dislike his own content (articles or comments)
CREATE OR REPLACE FUNCTION check_feedback() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF (NEW.user_id in (
        SELECT content.author_id 
        FROM content 
        WHERE content.author_id = NEW.content_id)) THEN
            RAISE EXCEPTION 'You cannot give feedback on your own content';
    ELSE
        INSERT INTO "feedback"(user_id, content_id, is_like) VALUES (NEW.user_id, NEW.content_id, NEW.is_like);
    END IF;
END;
$BODY$

LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS check_feedback ON "feedback";
CREATE TRIGGER check_feedback
    BEFORE INSERT ON feedback
    FOR EACH ROW
    EXECUTE PROCEDURE check_feedback();

----------------------------------------------------------------------------------------------------------------


-- trigger to add notification when a message is sent form an user to another or to remove in case of being read
CREATE OR REPLACE FUNCTION message_sent_notification() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF (NEW.is_read) THEN
        DELETE FROM "notification" WHERE msg = NEW.id;
    ELSE 
        INSERT INTO "notification"(receiver_id, date, is_read, msg, fb_giver, rated_content, new_comment, type) 
            VALUES (NEW.receiver_id, CURRENT_TIMESTAMP, FALSE, NEW.id, NULL, NULL, NULL, 'MESSAGE');
    END IF;
    RETURN NULL;
END
$BODY$

LANGUAGE plpgsql;


DROP TRIGGER IF EXISTS message_sent_notification ON "message";
CREATE TRIGGER message_sent_notification
    AFTER INSERT OR UPDATE ON message
    FOR EACH ROW
    EXECUTE PROCEDURE message_sent_notification();


INSERT INTO "authenticated_user"(name, email, birth_date, password, is_suspended, reputation) 
    VALUES ('rui', 'rui@gmail.com', CURRENT_TIMESTAMP, '1234567', false, 0);

INSERT INTO "authenticated_user"(name, email, birth_date, password, is_suspended, reputation) 
    VALUES ('joao', 'joao@gmail.com', CURRENT_TIMESTAMP, '1234567', false, 0);  

INSERT INTO "authenticated_user"(name, email, birth_date, password, is_suspended, reputation) 
    VALUES ('forever', 'forever@gmail.com', CURRENT_TIMESTAMP, '1234567', false, 0);  

INSERT INTO "message"(body, published_at, sender_id, receiver_id, is_read) VALUES ('oi', CURRENT_TIMESTAMP, 1, 2, FALSE);
INSERT INTO "message"(body, published_at, sender_id, receiver_id, is_read) VALUES ('oioi', CURRENT_TIMESTAMP, 1, 3, FALSE);


----------------------------------------------------------------------------------------------------------------

-- trigger to prevent user to delete a comment or article (content) with likes or dislikes or with subcomments
CREATE OR REPLACE FUNCTION check_content_delete() RETURNS TRIGGER AS
$BODY$
BEGIN 
    IF (OLD.likes != 0 or OLD.dislikes != 0) THEN
        RAISE EXCEPTION 'You cannot delete a content that has likes/dislikes';
    ELSE 
        IF (OLD.id in (SELECT article_id FROM comment WHERE comment.content_id = OLD.id) OR 
            OLD.id in (SELECT parent_comment_id FROM comment WHERE comment.parent_comment_id = OLD.id)) THEN -- is an article with comments or is a comment with sub comments
            RAISE EXCEPTION 'You cannot delete a content that has comments';
        ELSE 
            DELETE FROM "content" WHERE content.id = OLD.id;
        END IF;
    END IF;
    RETURN NULL;
END
$BODY$

LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS check_content_delete ON "content";
CREATE TRIGGER check_content_delete
    BEFORE DELETE ON content
    FOR EACH ROW
    EXECUTE PROCEDURE check_content_delete();


INSERT INTO "authenticated_user"(name, email, birth_date, password, is_suspended, reputation) 
    VALUES ('rui', 'rui@gmail.com', CURRENT_TIMESTAMP, '1234567', false, 0);

INSERT INTO "authenticated_user"(name, email, birth_date, password, is_suspended, reputation) 
    VALUES ('joao', 'joao@gmail.com', CURRENT_TIMESTAMP, '1234567', false, 0);  

INSERT INTO "authenticated_user"(name, email, birth_date, password, is_suspended, reputation) 
    VALUES ('forever', 'forever@gmail.com', CURRENT_TIMESTAMP, '1234567', false, 0);  

INSERT INTO "content"(body, published_at, is_edited, likes, dislikes, author_id) VALUES ('oi artigo', CURRENT_TIMESTAMP, false, 5, 1, 1);
INSERT INTO "article"(content_id, title) VALUES (1, 'Article');

INSERT INTO "content"(body, published_at, is_edited, likes, dislikes, author_id) VALUES ('ola filho', CURRENT_TIMESTAMP, false, 0, 0, 2);
INSERT INTO "comment"(content_id, article_id, parent_comment_id) VALUES (2, 1, NULL);

INSERT INTO "content"(body, published_at, is_edited, likes, dislikes, author_id) VALUES ('ola pai', CURRENT_TIMESTAMP, false, 3, 1, 3);
INSERT INTO "comment"(content_id, article_id, parent_comment_id) VALUES (3, 1, 2);

INSERT INTO "content"(body, author_id) VALUES ('tentar apagar', 3);

DELETE FROM "content" WHERE content.id = 5; -- in case we just insert this one from line behind

----------------------------------------------------------------------------------------------------------------


-- trigger to delete all the information about an article that was deleted
-- it just needs to delete the content represented by that article 
-- since its that deletion is cascaded to the comments and other elements of the article
CREATE OR REPLACE FUNCTION delete_article() RETURNS TRIGGER AS
$BODY$
BEGIN 
    DELETE FROM content WHERE content.id = OLD.content_id;
    RETURN NULL;
END
$BODY$

LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS delete_article ON "article";
CREATE TRIGGER delete_article
    BEFORE DELETE ON article
    FOR EACH ROW
    EXECUTE PROCEDURE delete_article();

-- need to test this in order to check the cascade

----------------------------------------------------------------------------------------------------------------

CREATE OR REPLACE FUNCTION check_add_article_tag() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF ()
END
$BODY$

LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS check_add_article_tag ON "article";
CREATE TRIGGER check_add_article_tag
    BEFORE INSERT ON article
    FOR EACH ROW
    EXECUTE PROCEDURE check_add_article_tag();

----------------------------------------------------------------------------------------------------------------

CREATE OR REPLACE FUNCTION create_area_expertise() RETURNS TRIGGER AS
$BODY$
DECLARE author_id INTEGER = (
    SELECT author_id FROM "content" WHERE id = NEW.article_id
);
BEGIN
    IF NEW.tag_id NOT IN (
        SELECT tag_id FROM "area_of_expertise" where user_id = author_id
    )
    THEN
        INSERT INTO "area_of_expertise" VALUES(author_id, NEW.tag_id, 0);
	END IF;
	RETURN NULL;
END
$BODY$

LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS create_area_expertise ON "article_tag";
CREATE TRIGGER create_area_expertise
    AFTER INSERT ON "article_tag"
    FOR EACH ROW
    EXECUTE PROCEDURE create_area_expertise();


CREATE OR REPLACE FUNCTION set_content_is_edited() RETURNS TRIGGER AS
$BODY$
BEGIN
    UPDATE "content" SET is_edited = TRUE
    WHERE id = NEW.id;
	RETURN NULL;
END
$BODY$

LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS set_content_is_edited ON "content";
CREATE TRIGGER set_content_is_edited
    AFTER UPDATE ON "content"
    FOR EACH ROW
    WHEN (OLD.body IS DISTINCT FROM NEW.body)
    EXECUTE PROCEDURE set_content_is_edited();


CREATE OR REPLACE FUNCTION set_article_is_edited() RETURNS TRIGGER AS
$BODY$
BEGIN
    UPDATE "content" SET is_edited = TRUE
    WHERE id = NEW.content_id;
	RETURN NULL;
END
$BODY$

LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS set_article_is_edited ON "article";
CREATE TRIGGER set_article_is_edited
    AFTER UPDATE ON "article"
    FOR EACH ROW
    WHEN (OLD.title IS DISTINCT FROM NEW.title)
    EXECUTE PROCEDURE set_article_is_edited();
