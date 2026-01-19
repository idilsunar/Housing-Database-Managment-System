CREATE PROCEDURE sp_room_occupancy_report(IN p_dorm_id INT)
BEGIN
    SELECT 
        r.dorm_id,
        r.roomno,
        r.capacity,
        COUNT(c.c_id) AS current_occupancy,
        (r.capacity - COUNT(c.c_id)) AS free_beds
    FROM Room r
    LEFT JOIN Contract c
      ON r.dorm_id = c.dorm_id
     AND r.roomno  = c.roomno
    WHERE r.dorm_id = p_dorm_id
    GROUP BY r.dorm_id, r.roomno, r.capacity
    ORDER BY r.roomno;
END//

DELIMITER ;