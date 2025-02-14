WordPress Setup on AWS EC2 with PHP 8.2, MySQL 8.1, and Apache
Server Specs 

AWS EC2 t2.micro instance
Ubuntu 22.04.5 LTS
PHP v8.1
mysql v8.0
Apache/2.4.52 (Ubuntu)

Prerequisites

An AWS account

SSH client (e.g., Terminal, PuTTY)

A registered domain (optional but recommended)

Basic knowledge of Linux commands

Step 1: Launch an EC2 Instance

Log in to AWS Management Console.

Navigate to EC2 Dashboard > Instances.

Click Launch Instance.

Choose an Amazon Machine Image (AMI):

Select Ubuntu 22.04 LTS (or later).

Choose an instance type:

t2.micro (Free Tier) or t3.medium (recommended for production).

Configure instance details:

Enable Auto-assign Public IP.

Set up security groups to allow HTTP (80), HTTPS (443), and SSH (22).

Attach an Elastic IP (optional but recommended).

Click Launch, then download and save the .pem key pair.

Connect to the instance via SSH:

ssh -i your-key.pem ubuntu@your-ec2-public-ip

Step 2: Update System Packages

sudo apt update && sudo apt upgrade -y

Step 3: Install Apache Web Server

sudo apt install apache2 -y
sudo systemctl enable apache2
sudo systemctl start apache2

Verify Apache:

sudo systemctl status apache2

Step 4: Install PHP 8.2

sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
sudo apt install php8.2 php8.2-mysql php8.2-cli php8.2-curl php8.2-gd php8.2-xml php8.2-mbstring php8.2-zip -y

Verify PHP:

php -v

Step 5: Install MySQL 8.1

sudo apt install mysql-server -y
sudo systemctl enable mysql
sudo systemctl start mysql

Secure MySQL installation:

sudo mysql_secure_installation

Create a WordPress database:

sudo mysql -u root -p

CREATE DATABASE wordpress;
CREATE USER 'wp_user'@'localhost' IDENTIFIED BY 'your_secure_password';
GRANT ALL PRIVILEGES ON wordpress.* TO 'wp_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;

Step 6: Install WordPress

## Step 6.1: Clone WordPress Repository
1. Navigate to the web root directory:
   ```bash
   cd /var/www/html
   ```
2. Clone the WordPress repository:
   ```bash
   sudo git clone https://github.com/umair-afzal-uat/Take-Home-Assignment.
   ```
3. Set the correct ownership and permissions:
   ```bash
   sudo chown -R www-data:www-data /var/www/html/
   sudo chmod -R 755 /var/www/html/
   ```

## Step 6.2: Create `wp-config.php`
1. Copy the sample config file:
   ```bash
   cp wp-config-sample.php wp-config.php
   ```
2. Open `wp-config.php` for editing:
   ```bash
   nano wp-config.php
   ```
3. Update the following lines with your database details:
   ```php
   define('DB_NAME', 'your_database_name');
   define('DB_USER', 'your_database_user');
   define('DB_PASSWORD', 'your_database_password');
   define('DB_HOST', 'localhost');
   ```
4. Save and exit (Press `CTRL + X`, then `Y`, then `ENTER`).

## Step 6.3: Set Correct Permissions
1. Change the ownership of WordPress files:
   ```bash
   sudo chown -R www-data:www-data /var/www/html/
   ```
2. Set the appropriate permissions:
   ```bash
   sudo chmod -R 755 /var/www/html/
   ```

## Step 6.4: Configure Apache or Nginx
### Apache:
1. Enable the rewrite module:
   ```bash
   sudo a2enmod rewrite
   ```
2. Restart Apache:
   ```bash
   sudo systemctl restart apache2
   ```

### Nginx:
Ensure your server block configuration allows WordPress rewriting.

## Step 6.5: Complete Installation via Browser
1. Open your browser and go to `http://your-server-ip`
2. Follow the on-screen instructions to complete the installation.

## Step 6.6: Keeping WordPress Updated
1. Navigate to the WordPress directory:
   ```bash
   cd /var/www/html
   ```
2. Pull the latest updates:
   ```bash
   sudo git pull origin master
   ```

---
Your WordPress installation is now complete! 

Step 7: Configure Apache for WordPress

Create a new virtual host file:

sudo nano /etc/apache2/sites-available/wordpress.conf

Add the following configuration:

<VirtualHost *:80>
    ServerAdmin admin@yourdomain.com
    DocumentRoot /var/www/html/
    ServerName yourdomain.com
    ServerAlias www.yourdomain.com

    <Directory /var/www/html/>
        AllowOverride All
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>

Save and exit (CTRL+X, then Y, then ENTER).

Enable the WordPress site and Apache modules:

sudo a2ensite wordpress.conf
sudo a2enmod rewrite
sudo systemctl restart apache2

Step 8: Finalize WordPress Setup

Visit http://your-ec2-public-ip or http://yourdomain.com.

Follow the on-screen instructions to complete WordPress installation.

Enter the database details (wp_user and your_secure_password).

Create an admin account and log in to the dashboard.

Step 9: Secure the Server (Optional but Recommended)

sudo apt install certbot python3-certbot-apache -y
sudo certbot --apache

Follow the prompts to install an SSL certificate from Let's Encrypt.

Troubleshooting

If Apache or MySQL fails to start, check logs:

sudo journalctl -xe
sudo systemctl status apache2
sudo systemctl status mysql

If permissions cause issues:

sudo chown -R www-data:www-data /var/www/html/

Conclusion

Your WordPress site is now running on AWS EC2 with PHP 8.2, MySQL 8.1, and Apache. 🎉
