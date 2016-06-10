# aws-upload
Script to upload on aws using rsync.

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

