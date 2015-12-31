# README #

## Server Setup

### DNS
- Put ns.sourcedns.com and ns1.sourcedns.com as DNS in registrar (it could be ns.liquidweb.com and ns1.liquidweb.com as well, they are the same)
- Create DNS zone in Manage interface (this overwrites WHM settings)
- Create account on WHM

### Install Composer

- Install Composer as root globally. Follow https://getcomposer.org/doc/00-intro.md#globally
	- Composer asks to add a line to php.ini file. Then it installs fine.
    
- Upload create composer.json
    {
        "require": {
            "stripe/stripe-php": "2.\*",
            "parse/php-sdk" : "1.1.\*"
        }
    }

- Install Composer "Composer install"
	- Composer gives memory error. Increase the PHP memory limit. (32M -> 35M -> 40M -> 64M (worked))

- Install Git: http://www.liquidweb.com/kb/how-to-install-git-on-centos-6/

- If you get 500 error, it might be because of file permissions. Do 644 and 755
	- find * -type d -print0 | xargs -0 chmod 0755 # for directories
	- find . -type f -print0 | xargs -0 chmod 0644 # for files

## Git

### Pull code to server (new) ###
    sudo git pull
    find * -type d -print0 | xargs -0 chmod 0755 # for directories
    find . -type f -print0 | xargs -0 chmod 0644 # for files


### Clone dev branch
    git clone https://ukaner@bitbucket.org/ukaner/invoice.to.git/ --branch dev --single-branch

### Pull dev branch 
    git pull https://ukaner@bitbucket.org/ukaner/invoice.to.git/ dev

### Overwrite manual file updates
    git reset --hard

### In case there are new files on Bitbucket but not on server (deletes other files/folders)
    git add * 
    git stash
    git pull

### Copy a folder with all content
    cp -a X/. Y/

### How to pull the code to server? (old) ###
    sudo rm -rf invoice.to
    sudo git clone https://ukaner@bitbucket.org/ukaner/invoice.to.git
    sudo rsync -a invoice.to/ .

### Error Handling
    error: insufficient permission for adding an object to repository database .git/objects
    sudo chown -R invoice:invoice /var/www/html/

### Robots disable indexing
    User-agent: *
    Disallow: /

### Don't ask password git pull
    git config --global credential.helper cache
