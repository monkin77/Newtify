-- Trigger to update likes/dislikes of a content when feedback is given (inserted into feedback table)
-- it also adds a notification
CREATE OR REPLACE FUNCTION feedback_content() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF (NEW.is_like) THEN
        UPDATE "content" SET likes = likes + 1 WHERE id = NEW.user_id;
        
        UPDATE "authenticated_user" SET reputation = reputation + 1 
            WHERE id = (SELECT author_id FROM content INNER JOIN authenticated_user ON (content.author_id = authenticated_user.id) WHERE content.id = NEW.content_id);

        INSERT INTO "notification"(date, receiver_id, is_read, msg, fb_giver, rated_content, new_comment, type) VALUES (CURRENT_TIMESTAMP, 1, FALSE, NULL, NEW.user_id, NULL, NULL, 'FEEDBACK');
    ELSE 
        UPDATE "content" SET dislikes = dislikes + 1 WHERE id = NEW.user_id;
        
        UPDATE "authenticated_user" SET reputation = reputation - 1 WHERE id = 
            (SELECT author_id 
             FROM content INNER JOIN authenticated_user ON (authenticated_user.id = content.author_id)
             WHERE content.id = NEW.content_id);
        
        INSERT INTO "notification"(date, receiver_id, is_read, msg, fb_giver, rated_content, new_comment, type) VALUES (CURRENT_TIMESTAMP, 1, FALSE, NULL, NEW.user_id, NULL, NULL, 'FEEDBACK');
    END IF;
    RETURN NULL;
END
$BODY$

LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS feedback_content ON feedback;
CREATE TRIGGER feedback_content
    AFTER INSERT ON feedback
    FOR EACH ROW
    EXECUTE PROCEDURE feedback_content();




-- Trigger to remove like/dislike of a content when feedback is removed (removed from feedback table)
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

DROP TRIGGER IF EXISTS remove_feedback ON feedback;
CREATE TRIGGER remove_feedback
    AFTER DELETE ON feedback
    FOR EACH ROW
    EXECUTE PROCEDURE remove_feedback();

----------------------------------------------------------------------------------------------------------------


-- trigger to add notification when a message is sent form an user to another
-- or to remove in case of being read
CREATE OR REPLACE FUNCTION message_update_notification() RETURN TRIGGER AS
$BODY$
BEGIN
    IF (NEW.is_read) THEN
        DELETE FROM "notification" WHERE msg = NEW.id;
    ELSE 
        INSERT INTO "notification" VALUES (CURRENT_TIMESTAMP, NEW.is_read, NEW.id, NULL, NULL, NULL, "MESSAGE");
    END IF;
END
$BODY$

LANGUAGE plpgsql;


DROP TRIGGER IF EXISTS message_sent_notification ON message;
CREATE TRIGGER message_sent_notification
    AFTER INSERT OR UPDATE ON message
    FOR EACH ROW
    EXECUTE PROCEDURE message_update_notification();



----------------------------------------------------------------------------------------------------------------

-- trigger to prevent user to delete a comment or article (content) with likes or dislikes
CREATE OR REPLACE FUNCTION check_content_delete() RETURN TRIGGER AS
$BODY$
BEGIN 
    IF (OLD.likes != 0 or OLD.dislikes != 0 or ) THEN
        RAISE EXPECTION 'You cannot delete a content that has likes/dislikes';
    ELSIF (OLD.id in (SELECT article_id FROM comment) or OLD.id in (SELECT parent_comment_id FROM comment)) -- is an article with comments or is a comment with sub comments
        RAISE EXCEPTION 'You cannot delete a content that has comments'; 
    END IF;
END
$BODY$

LANGUAGE plpgsql;


DROP TRIGGER IF EXISTS check_content_delete ON content;
CREATE TRIGGER check_content_delete
    BEFORE DELETE ON content
    FROM EACH ROW
    EXECUTE PROCEDURE check_content_delete();


----------------------------------------------------------------------------------------------------------------




