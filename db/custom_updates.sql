-- Need to hard code the internatl site url since the appraoch using ENV variables did not work...
UPDATE wp_options 
    SET option_value = 'http://localhost:2000'
    WHERE option_name = 'home';

UPDATE wp_options
    SET option_value =  'http://localhost:2000'
    WHERE option_name = 'siteurl';


-- Scrubbing default user to keep havinf=g default password:  AdminLogin123
UPDATE `wp_users` 
    SET `user_pass` = '$P$BA0El8cveWN9i9fi5k2VJFGSOLiyTF/'
WHERE (`user_login` = 'admin-user');

-- Changing sensitive info 
UPDATE `wp_users`  SET 
`user_email` = CONCAT(user_nicename, '@fake.com');