-- Need to hard code the internatl site url since the appraoch using ENV variables did not work...
UPDATE wp_options 
    SET option_value = 'http://localhost:2000'
    WHERE option_name = 'home';

UPDATE wp_options
    SET option_value =  'http://localhost:2000'
    WHERE option_name = 'siteurl';