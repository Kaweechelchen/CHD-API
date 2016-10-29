# -*- mode: ruby -*-
# vi: set ft=ruby :

VMname = "CHD-API-dev"

Vagrant.configure(2) do |config|

  config.vm.hostname = "#{VMname}"
  config.vm.box = "ubuntu/xenial64"

  config.vm.network "forwarded_port", guest: 80, host: 8080
  config.vm.network "forwarded_port", guest: 3306, host: 13306

  config.vm.synced_folder ".", "/var/www",
    owner: "ubuntu",
    group: "www-data",
    mount_options: ["dmode=775,fmode=664"]

  config.vm.provider "virtualbox" do |vb|
        vb.memory = 1024
        vb.cpus   = 2
        vb.name   = "#{VMname}"
    end
        # Set name of VM
        config.vm.define "#{VMname}" do |vb|
    end

    # Ubutnu no TTY fix
    # https://github.com/mitchellh/vagrant/issues/1673#issuecomment-211568829
    config.vm.provision "ubuntu-fix-no-tty", type: "shell" do |s|
      s.privileged = false
      s.inline = "sudo sed -i '/tty/!s/mesg n/tty -s \\&\\& mesg n/' /root/.profile"
    end

    # Setting locale
    ENV["LC_ALL"] = "en_GB.UTF-8"

    #https://stackoverflow.com/questions/7739645/install-mysql-on-ubuntu-without-password-prompt
    config.vm.provision :shell, :inline => <<-SCRIPT

        apt-get update
        apt-get -y autoremove
        apt-get -y upgrade
        locale-gen en_GB.UTF-8

        echo '[client]\nuser=root\npassword=root\ndatabase=chd' > /home/ubuntu/.my.cnf

        debconf-set-selections <<< 'mysql-server mysql-server/root_password password root'
        debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password root'

        apt-get install -y apache2 php mysql-server libapache2-mod-php php-mysql composer
        sed -i -e \'s/var\\/www\\/html/var\\/www\\/web/g\' /etc/apache2/sites-available/000-default.conf
        service apache2 reload
        apt-get -yf install

        rm -rf /var/www/html ubuntu-xenial-16.04-cloudimg-console.log

        a2enmod rewrite

        mysql -uroot -proot < /var/www/sql/mysql.config.sql



    SCRIPT
    #sed -i -e \'s/var\\/www\\/html/var\\/www\\/web/g\' /etc/apache2/sites-available/000-default.conf
    #mysql -uroot -proot chd < /var/www/sql/db_init.sql

end
