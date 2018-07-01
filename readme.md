# PHP WorkTime Manager

## Install

Configure webserver to point worktime-php/public directory.

Example **httpd-vhosts.conf**
````
<VirtualHost *:80>
    DocumentRoot "C:/xampp/htdocs/worktime-php/public"
    ServerName worktime.local
    <Directory "C:/xampp/htdocs/worktime-php/public">
    </Directory>
</VirtualHost>
````
**hosts file:**
```
127.0.0.1       worktime.local
```

Example data hosted in **sql.dump.sql**

## Database

MySQL connection informations hosted in **src/controllers/DBController.php**
```
private $host       = 'localhost';
private $db_name    = 'worktime';
private $username   = 'root';
private $password   = '';
```

## Assets

**webpack.config** and **package.json** available at **public** directory

`yarn` or
`npm install`

Then

`yarn webpack` or
`npm run webpack`


## Technology
- Apache 2.4.33
- PHP version: 7.2.6
- MariaDB 10.1.32 (MySQL)
- (xampp v3.2.2)


- ES6, Webpack
- jQuery@3.3.1
- Bootstrap@4.1.1
- Bootstrap4 DataTable
- Bootstrap4 DateTimePicker
- FontAwesome