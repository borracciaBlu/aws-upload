
# aws-upload
A delicious CLI Tool for uploading files to ec2.

[![Build Status](https://travis-ci.org/borracciaBlu/aws-upload.svg?branch=master)](https://travis-ci.org/borracciaBlu/aws-upload)
[![Latest Stable Version](https://poser.pugx.org/aws-upload/aws-upload/version)](https://packagist.org/packages/aws-upload/aws-upload)
[![Turbo Commit](https://img.shields.io/badge/Turbo_Commit-on-3DD1F2.svg)](https://github.com/labs-js/turbo-git/blob/master/README.md)  

## How to install

    composer global require aws-upload/aws-upload

## Add Super Powers

## Demo
<p align="center">
  <img src="https://cloud.githubusercontent.com/assets/2061731/23747869/51d51c6e-0515-11e7-9a72-25d134380d1f.gif" alt="aws-upload Demo"/>
</p>

## How it works?

All the times you'll type `aws-upload [project] [env]`, `aws-upload` will:

1 - check if in `~/.aws-upload/` you have a setting file called `project.env.json`  
2 - read the settings stored in it  
3 - upload the files for you  

## The Setting File

The setting file name use the convetion `[project].[env].json` where:

 - *project* is the name of the project 
 - *env* is the environment

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
    
 
**pem** | *string*:  it contains the path to your pem key.  
**local** | *string*: it contains the path to the directory you want to upload to your ec2 server.  
**exclude** | *array*: it contains the list of files or folders you DO NOT want to upload.   
**remote** | *string*: it contains the information of your server.  
In particular [*user*]@[*host*]:[*remotePath*] where:

- *user* is you user on your server. Possibles values [ec2-user, ubuntu]
- *host* is your ec2 host name. Similar value [ec2-xxx-xxx-xxx-xxx.compute-1.amazonaws.com]  
- *remotePath* is the folder you want to upload your project to. It's the folder on your server.