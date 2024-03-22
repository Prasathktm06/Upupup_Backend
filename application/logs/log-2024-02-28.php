<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2024-02-28 12:22:09 --> 404 Page Not Found: /index
ERROR - 2024-02-28 12:27:08 --> Severity: Warning --> Invalid argument supplied for foreach() /home/migrate2new/public_html/partnerup/application/modules/acl/views/form/add_user.php 159
ERROR - 2024-02-28 12:28:30 --> Severity: Warning --> Invalid argument supplied for foreach() /home/migrate2new/public_html/partnerup/application/modules/acl/views/form/add_user.php 159
ERROR - 2024-02-28 12:42:38 --> Severity: Warning --> Invalid argument supplied for foreach() /home/migrate2new/public_html/partnerup/application/modules/acl/views/form/add_user.php 159
ERROR - 2024-02-28 12:56:37 --> 404 Page Not Found: /index
ERROR - 2024-02-28 18:26:44 --> Query error: Expression #8 of SELECT list is not in GROUP BY clause and contains nonaggregated column 'migrate2_partnerup_db.locations.id' which is not functionally dependent on columns in GROUP BY clause; this is incompatible with sql_mode=only_full_group_by - Invalid query: SELECT `users`.`id`, `users`.`name`, `users`.`email`, `users`.`address`, `users`.`phone_no`, GROUP_CONCAT(DISTINCT sports.id) as sports, GROUP_CONCAT(DISTINCT area.id) as area, `locations`.`id` as `location`, `users`.`image`
FROM `users`
LEFT JOIN `user_area` ON `user_area`.`user_id`=`users`.`id`
LEFT JOIN `area` ON `user_area`.`area_id`=`area`.`id`
LEFT JOIN `user_sports` ON `user_sports`.`user_id`=`users`.`id`
LEFT JOIN `sports` ON `user_sports`.`sports_id`=`sports`.`id`
LEFT JOIN `locations` ON `locations`.`id`=`area`.`location_id`
WHERE `users`.`id` = '3260'
GROUP BY `users`.`id`
ERROR - 2024-02-28 18:26:44 --> Severity: error --> Exception: Call to a member function row() on bool /home/migrate2new/public_html/partnerup/application/modules/users/models/Users_model.php 152
ERROR - 2024-02-28 13:44:43 --> 404 Page Not Found: /index
ERROR - 2024-02-28 19:15:07 --> Query error: Expression #8 of SELECT list is not in GROUP BY clause and contains nonaggregated column 'migrate2_partnerup_db.locations.id' which is not functionally dependent on columns in GROUP BY clause; this is incompatible with sql_mode=only_full_group_by - Invalid query: SELECT `users`.`id`, `users`.`name`, `users`.`email`, `users`.`address`, `users`.`phone_no`, GROUP_CONCAT(DISTINCT sports.id) as sports, GROUP_CONCAT(DISTINCT area.id) as area, `locations`.`id` as `location`, `users`.`image`
FROM `users`
LEFT JOIN `user_area` ON `user_area`.`user_id`=`users`.`id`
LEFT JOIN `area` ON `user_area`.`area_id`=`area`.`id`
LEFT JOIN `user_sports` ON `user_sports`.`user_id`=`users`.`id`
LEFT JOIN `sports` ON `user_sports`.`sports_id`=`sports`.`id`
LEFT JOIN `locations` ON `locations`.`id`=`area`.`location_id`
WHERE `users`.`id` = '3260'
GROUP BY `users`.`id`
ERROR - 2024-02-28 19:15:07 --> Severity: error --> Exception: Call to a member function row() on bool /home/migrate2new/public_html/partnerup/application/modules/users/models/Users_model.php 152
ERROR - 2024-02-28 19:15:36 --> Query error: Expression #8 of SELECT list is not in GROUP BY clause and contains nonaggregated column 'migrate2_partnerup_db.locations.id' which is not functionally dependent on columns in GROUP BY clause; this is incompatible with sql_mode=only_full_group_by - Invalid query: SELECT `users`.`id`, `users`.`name`, `users`.`email`, `users`.`address`, `users`.`phone_no`, GROUP_CONCAT(DISTINCT sports.id) as sports, GROUP_CONCAT(DISTINCT area.id) as area, `locations`.`id` as `location`, `users`.`image`
FROM `users`
LEFT JOIN `user_area` ON `user_area`.`user_id`=`users`.`id`
LEFT JOIN `area` ON `user_area`.`area_id`=`area`.`id`
LEFT JOIN `user_sports` ON `user_sports`.`user_id`=`users`.`id`
LEFT JOIN `sports` ON `user_sports`.`sports_id`=`sports`.`id`
LEFT JOIN `locations` ON `locations`.`id`=`area`.`location_id`
WHERE `users`.`id` = '2092'
GROUP BY `users`.`id`
ERROR - 2024-02-28 19:15:36 --> Severity: error --> Exception: Call to a member function row() on bool /home/migrate2new/public_html/partnerup/application/modules/users/models/Users_model.php 152
ERROR - 2024-02-28 13:47:52 --> Severity: Warning --> Invalid argument supplied for foreach() /home/migrate2new/public_html/partnerup/application/modules/acl/views/form/add_user.php 159
ERROR - 2024-02-28 13:48:22 --> Severity: Warning --> Invalid argument supplied for foreach() /home/migrate2new/public_html/partnerup/application/modules/acl/views/form/add_user.php 159
ERROR - 2024-02-28 13:50:44 --> Severity: Warning --> Invalid argument supplied for foreach() /home/migrate2new/public_html/partnerup/application/modules/acl/views/form/add_user.php 159
