USE housing_mgmt;

DELIMITER //
CREATE TRIGGER trg_request_auto_assign
BEFORE INSERT ON MaintenanceRequest
FOR EACH ROW
BEGIN
    IF NEW.assignee_staff_id IS NULL THEN
        SET NEW.assignee_staff_id = 501;
    END IF;
END//

DELIMITER ;
