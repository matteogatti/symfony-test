VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|

    config.ssh.forward_agent = true

    config.vm.define "deploy" do |deploy|
	deploy.vm.box = "bento/ubuntu-16.04"
        deploy.vm.network "private_network", ip: "192.168.56.111"
        deploy.vm.host_name = "development"
        deploy.vm.synced_folder "src/", "/var/www/deploy", id: "vagrant-root",
		    :owner => "vagrant",
		    :group => "www-data",
		    :mount_options => ["dmode=775,fmode=664"]
        deploy.vm.provider :virtualbox do |v|
            v.customize ["modifyvm", :id, "--memory", 2048]
        end
	deploy.vm.provision "ansible" do |ansible|
            ansible.playbook = "ansible/playbooks.yml"
        end
    end


end
