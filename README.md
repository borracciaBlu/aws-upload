
# aws-upload
A delicious CLI Tool for uploading files to ec2.

[![Build Status](https://travis-ci.org/borracciaBlu/aws-upload.svg?branch=master)](https://travis-ci.org/borracciaBlu/aws-upload)
[![Turbo Commit](https://img.shields.io/badge/Turbo_Commit-on-3DD1F2.svg)](https://github.com/labs-js/turbo-git/blob/master/README.md)  

## How to install

    composer require global "aws-upload/aws-upload"

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
