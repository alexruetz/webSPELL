# -*- mode: ruby -*-
# vi: set ft=ruby :

$prepare = <<SCRIPT
sudo apt-get -y install puppet vim apt-transport-https build-essential git
sudo puppet module install puppetlabs-apache
sudo puppet module install puppetlabs-mysql
sudo puppet module install nodes/php
sudo puppet module install puppet-nodejs
sudo puppet module install puppetlabs-apt
SCRIPT

$post = <<SCRIPT
curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin
sudo ln -s /usr/local/bin/composer.phar /usr/local/bin/composer
cd /vagrant
sudo npm install -g grunt-cli
sudo npm install -g bower
npm install
bower install
composer install
SCRIPT

Vagrant.configure(2) do |config|
  config.vm.provider "virtualbox" do |vb|
      vb.memory = 4096
      vb.cpus = 2
  end
  config.vm.define "webspell-dev"
  config.vm.hostname = "webspell-dev"
  config.vm.box = "debian/contrib-jessie64"
  config.vm.network "forwarded_port", guest: 80, host: 8080
  config.vm.network "public_network"
  config.vm.provision "fix-no-tty", type: "shell", inline: "sudo sed -i '/tty/!s/mesg n/tty -s \\&\\& mesg n/' /root/.profile", privileged: false
  config.vm.provision "prepare", type: "shell", inline: $prepare, privileged: false
  config.vm.provision "puppet" do |puppet|
      puppet.manifests_path = "development"
      puppet.manifest_file = "Provision.pp"
  end
  config.vm.provision "post", type: "shell", inline: $post, privileged: false
end
