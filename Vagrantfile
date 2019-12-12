Vagrant.configure("2") do |config|
  config.vm.define "phone-book"
    config.vm.box = "debian/jessie64"
    config.vm.hostname = "phone-book"
    config.vm.network 'private_network', ip: '172.21.99.4'

    config.vm.provider "virtualbox" do |vb|
            vb.memory = "1024"
    end

    config.vm.synced_folder '.', '/var/deployments/phone-book/releases/1', type: 'nfs'

    config.vm.provision "ansible" do |ansible|
        ansible.compatibility_mode = "2.0"
        ansible.extra_vars = {
          hostname: "phone-book"
        }
        ansible.playbook = "etc/devel/vagrant/provision/ansible/playbooks/playbook.yml"
    end
end
