
# aws-upload
A delicious CLI Tool for uploading files to ec2.

[![Build Status](https://travis-ci.org/borracciaBlu/aws-upload.svg?branch=master)](https://travis-ci.org/borracciaBlu/aws-upload)
[![Latest Stable Version](https://poser.pugx.org/aws-upload/aws-upload/version)](https://packagist.org/packages/aws-upload/aws-upload)
[![Turbo Commit](https://img.shields.io/badge/Turbo_Commit-on-3DD1F2.svg)](https://github.com/labs-js/turbo-git/blob/master/README.md)  

<p align="center">
  <img src="https://cloud.githubusercontent.com/assets/2061731/23747869/51d51c6e-0515-11e7-9a72-25d134380d1f.gif" alt="aws-upload Demo"/>
</p>

## What?
aws-upload allow you to rapid upload files from cli in an efficient way (tnx rsync).
You define a setting file for your project, and then you have just to tab.
aws-upload will let compress and upload only the files you change it. You can even setup different environments for the same project.

## Why?
If you have a lot of small projects with different environments, without a proper deployment system, and you don't want to remember all the time the rsync cmd (or worst manually upload using FileZilla), aws-upload is definitely for you.

## Why not?
If you are playing with a huge project, you should have a proper deployment automation in place, maybe with some CI system. 

## How to install

    composer global require aws-upload/aws-upload


## Enabling tab-completion

In order to achieve the tab-completion like in the screen shot you have to install `aws-upload-zsh`.  
`aws-upload-zsh` is the oh-my-zsh plugin to boost your productivity with `aws-upload`.  

To install `aws-upload-zsh` follow the instructions [here](https://github.com/borracciaBlu/aws-upload-zsh).

## How it works?

All the times you'll type `aws-upload [project] [env]`, `aws-upload` will:

1 - check if in `~/.aws-upload/` you have a setting file called `project.env.json`  
2 - read the settings from it  
3 - upload the files for you through rsync/ssh  

## The Setting File

The setting file name use the convetion `[project].[env].json` where:

 - *project* is the name of the project 
 - *env* is the environment

The folder that contains all the setting files is `~/.aws-upload/`.

### Setting file name examples

Some cases of files and command. 

    // - project: myProject
    // - env: dev
    ~/.aws-upload/myProject.dev.json
    aws-upload myProject dev
    
    // - project: myProject
    // - env: stag
    ~/.aws-upload/myProject.stag.json 
    aws-upload myProject stag

    // - project: aws-upload-io
    // - env: prod
    ~/.aws-upload/aws-upload-io.prod.json
    aws-upload aws-upload-io prod
  

### The setting file content
This is the structure of a setting file. As you can see there are 4 main parts: `pem`, `local`, `remote`, `exclude`.

```json
    {
        "pem" : "/home/keys/your-key.pem",
        "local" : "/var/www/project/*",
        "remote" : "ubuntu@ec2-xxx-xxx-xxx-xxx.compute-1.amazonaws.com:/var/www/html",
        "exclude" : [
            ".env",
            ".git/",
            "storage/",
            "tests/",
            "node_modules/"
        ]
    }
```
 
**pem** | *string*:  it contains the path to your pem key.  
**local** | *string*: it contains the path to the directory you want to upload to your ec2 server.  
**exclude** | *array*: it contains the list of files or folders you DO NOT want to upload.   
**remote** | *string*: it contains the information of your server.  
In particular [*user*]@[*host*]:[*remotePath*] where:

- *user* is you user on your server. Possibles values [ec2-user, ubuntu]
- *host* is your ec2 host name. Similar value [ec2-xxx-xxx-xxx-xxx.compute-1.amazonaws.com]  
- *remotePath* is the folder you want to upload your project to. It's the folder on your server.
