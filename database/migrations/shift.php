<?php
return ["
CREATE TABLE `shifts` (
  `id` int(11) NOT NULL,
  `user_name` varchar(255) DEFAULT NULL,
  `user_email` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `type` varchar(50) NOT NULL,
  `rate` float DEFAULT NULL,
  `charge` float NOT NULL DEFAULT 0,
  `area` varchar(255) DEFAULT NULL,
  `event_id` int(11) DEFAULT NULL,
  `start` int(11) NOT NULL,
  `end` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) DEFAULT NULL,
  `deleted_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `shifts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `location` (`location`);

ALTER TABLE `shifts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;
"];