<?php
return ["
CREATE TABLE `shift_department` (
  `shift_id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
COMMIT;
"];