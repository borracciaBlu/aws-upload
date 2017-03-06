
# aws-upload
Script to upload on aws using rsync.

[![Turbo Commit](https://img.shields.io/badge/Turbo_Commit-on-3DD1F2.svg)](https://github.com/labs-js/turbo-git/blob/master/README.md)

## How to install the script

    cp ~/aws-upload.php /usr/bin/aws-upload
    chmod +x /usr/bin/aws-upload

## How to install the oh-my-zsh plugin

Assuming that you have already oh-my-zsh installed.

###Copy the plugin:

    mkdir ~/.oh-my-zsh/plugins/aws-upload/
    cp aws-upload.plugin.zsh ~/.oh-my-zsh/plugins/aws-upload/aws-upload.plugin.zsh
    
###Enable the plugin:

    vim ~/.zshrc 
    plugins=(history grunt laravel laravel5 git composer tmuxinator aws-upload)

## How to generate a project file

Before to generate a project file you have to create a `.aws-upload` folder in you `$HOME`.

    cd ~
    mkdir .aws-upload

Once you generated the `.aws-upload` folder you can start to populated with project file.

### The project file name

The project file name follow a convetion `[project]-[env].json` where:
 - *project* is the name of the project 
 - *env* is the environment

### Project file name examples

    // - project : myProject
    // - env : testing
    myProject-testing.json

    // - project : myProject
    // - env : staging
    myProject-staging.json 

    // - project : myProject
    // - env : production
    myProject-production.json 

### The project file content

    {
		"pem" : "/home/keys/your-key.pem ",
		"local" : "/var/www/project/* ",
		"remote" : "ubuntu@ec2-xxx-xxx-xxx-xxx.compute-1.amazonaws.com:/var/www/html",
		"exclude" : [
						".env",
						".git/",
						"storage/",
						"tests/",
						"node_modules/"
					]
    }
