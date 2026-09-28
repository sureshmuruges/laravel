# cPanel GitHub SSH Setup (No Passphrase)

This guide helps you connect **cPanel → GitHub** using SSH safely (no
passphrase).\
Perfect for beginners and Laravel deployments.

------------------------------------------------------------------------

## Step 1: Open Terminal in cPanel

1.  Login to **cPanel**

2.  Go to **Advanced → Terminal**

3.  You should see:

        track759@tracksen.in [~]#

------------------------------------------------------------------------

## Step 2: Go to Home Directory

``` bash
cd ~
```

------------------------------------------------------------------------

## Step 3: Create `.ssh` Directory

``` bash
mkdir -p ~/.ssh
chmod 700 ~/.ssh
```

------------------------------------------------------------------------

## Step 4: Generate SSH Key (No Passphrase)

``` bash
ssh-keygen -t ed25519 -f ~/.ssh/id_ed25519 -C "track759@tracksen.in"
```

When asked for passphrase → **Press ENTER twice**

------------------------------------------------------------------------

## Step 5: Copy Public Key

``` bash
cat ~/.ssh/id_ed25519.pub
```

------------------------------------------------------------------------

## Step 6: Add Key to GitHub

1.  GitHub → **Settings**
2.  **SSH and GPG keys**
3.  **New SSH key**
4.  Title: `cPanel - tracksen`
5.  Paste key → **Save**

------------------------------------------------------------------------

## Step 7: Configure SSH

``` bash
nano ~/.ssh/config
```

Paste:

    Host github.com
      HostName github.com
      User git
      IdentityFile ~/.ssh/id_ed25519
      IdentitiesOnly yes

Save: - CTRL + O → ENTER - CTRL + X

------------------------------------------------------------------------

## Step 8: Fix Permissions

``` bash
chmod 600 ~/.ssh/config
chmod 600 ~/.ssh/id_ed25519
chmod 644 ~/.ssh/id_ed25519.pub
```

------------------------------------------------------------------------

## Step 9: Test GitHub Connection

``` bash
ssh -T git@github.com
```

Expected:

    Hi sureshapp2025! You've successfully authenticated, but GitHub does not provide shell access.

------------------------------------------------------------------------

## Step 10: Pull Laravel Code

``` bash
cd ~/public_html/laravel
git fetch --all
git pull origin main
```

If main fails:

``` bash
git pull origin master
```

------------------------------------------------------------------------

## Step 11: Laravel Setup

``` bash
composer install --no-dev
cp .env.example .env
php artisan key:generate
php artisan migrate --force
```

------------------------------------------------------------------------

## Step 12: Set Document Root

cPanel → **Domains**\
Set document root to:

    public_html/laravel/public

------------------------------------------------------------------------

🎉 **Done! Your Laravel app is live and GitHub-connected.**

copy the public folder inside build folder and add in the root directroy of
public_html


------------------------------------------------------------------------

🎉 **Images folder **

In Local to move to server 
 public/images folder[only images] move to  public_html/images  and provide the permission

chmod -R 755 public/images
chmod 644 public/images/logo.png
chmod 644 public/images/favicon.ico


------------------------------------------------------------------------

🎉 **Images folder **

In Local to move to server 
 public/images folder[only images] move to  public_html/images  and provide the permission

chmod -R 755 public/images
chmod 644 public/images/logo.png
chmod 644 public/images/favicon.ico


# Laravel Artisan Commands Cheat Sheet

## 🚀 Basic Commands

### Run Development Server
```bash
php artisan serve


php artisan route:list


php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

php artisan migrate

php artisan migrate:rollback


php artisan migrate:refresh

php artisan migrate:fresh

php artisan db:seed
php artisan make:controller UserController
php artisan make:model User

php artisan make:model Product -mcr
php artisan queue:work
php artisan queue:listen
php artisan queue:restart

php artisan optimize
php artisan optimize:clear

vendor follder issue server  
Run command local
composer install --no-dev --optimize-autoloader
and zip the vendor floder move to hosting