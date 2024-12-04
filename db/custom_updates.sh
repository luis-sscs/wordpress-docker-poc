#!/bin/bash

# Load environment variables
source /docker-entrypoint-initdb.d/.env

# Execute SQL commands using the dynamic table name
mysql -uroot -p${MYSQL_ROOT_PASSWORD} ${MYSQL_DATABASE} <<EOF

UPDATE wp_options 
    SET option_value = ${WORDPRESS_PUBLIC_SITE}
    WHERE option_name = 'home';

UPDATE wp_options
    SET option_value = ${WORDPRESS_PUBLIC_SITE}
    WHERE option_name = 'siteurl';

EOF

# UPDATE wp_posts
# SET post_content = REPLACE(post_content,'http://127.0.0.1/OldSiteName','http://127.0.0.1/NewSiteName');

# UPDATE wp_posts
# SET guid = REPLACE(guid,'http://127.0.0.1/OldSiteName','http://127.0.0.1/NewSiteName');