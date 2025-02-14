````markdown
# WordPress Setup on AWS EC2 with PHP 8.2, MySQL 8.1, and Apache

## Server Specs

- **AWS EC2 Instance**: t2.micro
- **Operating System**: Ubuntu 22.04.5 LTS
- **PHP Version**: 8.2
- **MySQL Version**: 8.1
- **Apache Version**: 2.4.52 (Ubuntu)

---

## Prerequisites

- An **AWS account**
- **SSH client** (e.g., Terminal, PuTTY)
- A **registered domain** (optional but recommended)
- Basic knowledge of **Linux commands**

---

## Step 1: Launch an EC2 Instance

1. Log in to the **AWS Management Console**.
2. Navigate to **EC2 Dashboard > Instances**.
3. Click **Launch Instance**.
4. Choose an **Amazon Machine Image (AMI)**:
   - Select **Ubuntu 22.04 LTS** (or later).
5. Choose an **instance type**:
   - **t2.micro** (Free Tier) or **t3.medium** (recommended for production).
6. Configure instance details:
   - Enable **Auto-assign Public IP**.
   - Set up **security groups** to allow HTTP (80), HTTPS (443), and SSH (22).
   - Attach an **Elastic IP** (optional but recommended).
7. Click **Launch**, then download and save the `.pem` key pair.
8. Connect to the instance via SSH:
   ```bash
   ssh -i your-key.pem ubuntu@your-ec2-public-ip
   ```
````

---

## Step 2: Update System Packages

```bash
sudo apt update && sudo apt upgrade -y
```

---

## Step 3: Install Apache Web Server

```bash
sudo apt install apache2 -y
sudo systemctl enable apache2
sudo systemctl start apache2
```

Verify Apache:

```bash
sudo systemctl status apache2
```

---

## Step 4: Install PHP 8.2

```bash
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
sudo apt install php8.2 php8.2-mysql php8.2-cli php8.2-curl php8.2-gd php8.2-xml php8.2-mbstring php8.2-zip -y
```

Verify PHP:

```bash
php -v
```

---

## Step 5: Install MySQL 8.1

```bash
sudo apt install mysql-server -y
sudo systemctl enable mysql
sudo systemctl start mysql
```

Secure MySQL installation:

```bash
sudo mysql_secure_installation
```

Create a WordPress database:

```sql
sudo mysql -u root -p
CREATE DATABASE wordpress;
CREATE USER 'wp_user'@'localhost' IDENTIFIED BY 'your_secure_password';
GRANT ALL PRIVILEGES ON wordpress.* TO 'wp_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

---

## Step 6: Install WordPress

### Step 6.1: Clone WordPress Repository

Navigate to the web root directory:

```bash
cd /var/www/html
```

Clone the WordPress repository:

```bash
sudo git clone https://github.com/umair-afzal-uat/Take-Home-Assignment.git .
```

Set the correct ownership and permissions:

```bash
sudo chown -R www-data:www-data /var/www/html/
sudo chmod -R 755 /var/www/html/
```

### Step 6.2: Create `wp-config.php`

Copy the sample config file:

```bash
cp wp-config-sample.php wp-config.php
```

Open `wp-config.php` for editing:

```bash
nano wp-config.php
```

Update the following lines with your database details:

```php
define('DB_NAME', 'your_database_name');
define('DB_USER', 'your_database_user');
define('DB_PASSWORD', 'your_database_password');
define('DB_HOST', 'localhost');
```

Save and exit (Press `CTRL + X`, then `Y`, then `ENTER`).

### Step 6.3: Set Correct Permissions

Change the ownership of WordPress files:

```bash
sudo chown -R www-data:www-data /var/www/html/
```

Set the appropriate permissions:

```bash
sudo chmod -R 755 /var/www/html/
```

### Step 6.4: Configure Apache or Nginx

#### Apache:

Enable the rewrite module:

```bash
sudo a2enmod rewrite
```

Restart Apache:

```bash
sudo systemctl restart apache2
```

#### Nginx:

Ensure your server block configuration allows WordPress rewriting.

### Step 6.5: Complete Installation via Browser

Open your browser and go to:

```
http://your-server-ip
```

Follow the on-screen instructions to complete the installation.

### Step 6.6: Keeping WordPress Updated

Navigate to the WordPress directory:

```bash
cd /var/www/html
```

Pull the latest updates:

```bash
sudo git pull origin master
```

Your WordPress installation is now complete!

---

## Step 7: Configure Apache for WordPress

Create a new virtual host file:

```bash
sudo nano /etc/apache2/sites-available/wordpress.conf
```

Add the following configuration:

```apache
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
```

Save and exit (`CTRL+X`, then `Y`, then `ENTER`).

Enable the WordPress site and Apache modules:

```bash
sudo a2ensite wordpress.conf
sudo a2enmod rewrite
sudo systemctl restart apache2
```

---

## Step 8: Finalize WordPress Setup

Visit:

```
http://your-ec2-public-ip
```

or

```
http://yourdomain.com
```

Follow the on-screen instructions to complete the WordPress installation. Enter the database details (`wp_user` and `your_secure_password`). Create an admin account and log in to the dashboard.

---

## Step 9: Secure the Server (Optional but Recommended)

Install Certbot for SSL:

```bash
sudo apt install certbot python3-certbot-apache -y
sudo certbot --apache
```

Follow the prompts to install an SSL certificate from **Let's Encrypt**.

---

## Troubleshooting

- If Apache or MySQL fails to start, check logs:
  ```bash
  sudo journalctl -xe
  sudo systemctl status apache2
  sudo systemctl status mysql
  ```
- If permissions cause issues:
  ```bash
  sudo chown -R www-data:www-data /var/www/html/
  ```

---

## Conclusion

Your WordPress site is now running on AWS EC2 with **PHP 8.2**, **MySQL 8.1**, and **Apache**! 🎉

**Public URL**: [http://3.79.186.55](http://3.79.186.55)

```

```
