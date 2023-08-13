ALTER TABLE alumnus_scientific_degree
  ADD year YEAR;

DELIMITER $$

CREATE PROCEDURE debug_msg(enabled INTEGER, msg VARCHAR(255))
BEGIN
  IF enabled THEN
    select concat('** ', msg) AS '** DEBUG:';
  END IF;
END $$

CREATE PROCEDURE move_years ()
BEGIN
    DECLARE finished INTEGER DEFAULT 0;
    DECLARE aid BIGINT UNSIGNED;
    DECLARE sid BIGINT UNSIGNED;

    DECLARE asd_cursor CURSOR FOR
        SELECT alumnus_id, scientific_degree_id FROM alumnus_scientific_degree asd
          WHERE year IS NULL
          AND EXISTS
            (SELECT *
             FROM years_and_ids
             WHERE alumnus_id = asd.alumnus_id
             AND scientific_degree_id = asd.scientific_degree_id);
    
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET finished = 1;
    
    -- must be a table because it can't depend on the table we want to update
    CREATE TABLE years_and_ids AS
      SELECT alumnus_scientific_degree.alumnus_id, alumnus_scientific_degree.scientific_degree_id, scientific_degrees.obtain_year
      FROM alumni
      INNER JOIN alumnus_scientific_degree ON alumni.id = alumnus_scientific_degree.alumnus_id
      INNER JOIN scientific_degrees ON alumnus_scientific_degree.scientific_degree_id = scientific_degrees.id
      WHERE scientific_degrees.id IN (select id from scientific_degrees where obtain_year is not null);
    
    OPEN asd_cursor;
    asd_loop: LOOP
        FETCH asd_cursor INTO aid, sid;
        IF finished = 1 THEN
            LEAVE asd_loop;
        END IF;
        CALL debug_msg(TRUE, (select concat_ws('','aid:', aid, ' sid:', sid)));

        UPDATE alumnus_scientific_degree
          SET year =
            (SELECT obtain_year FROM years_and_ids WHERE alumnus_id = aid AND scientific_degree_id = sid)
          WHERE alumnus_id = aid AND scientific_degree_id = sid;
    END LOOP asd_loop;
    CLOSE asd_cursor;
    
    DROP TABLE years_and_ids;
END$$
DELIMITER ;

-- for some reason, it says '0 rows affected', but actually it does work

-- and for running it:
-- CALL move_years;

-- for checking:

SELECT alumnus_id, scientific_degree_id, year FROM alumnus_scientific_degree where year IS NOT NULL ORDER BY scientific_degree_id;

SELECT alumnus_scientific_degree.alumnus_id, alumnus_scientific_degree.scientific_degree_id, scientific_degrees.obtain_year
    FROM alumni
    INNER JOIN alumnus_scientific_degree ON alumni.id = alumnus_scientific_degree.alumnus_id      
    INNER JOIN scientific_degrees ON alumnus_scientific_degree.scientific_degree_id = scientific_degrees.id
    WHERE scientific_degrees.id IN (select id from scientific_degrees where obtain_year is not null)
    ORDER BY alumnus_scientific_degree.scientific_degree_id;


-- and when you're absolutely sure it's done:
-- ALTER TABLE scientific_degrees
--   DROP COLUMN obtain_year;
