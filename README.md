# MAX Fun

## Introduction

[MAX](http://max.comicdish.com) is a multi-artist art exchange that this project (max_fun) is not officially affiliated with. Sometime during the last year, after over 10 years or run at least once a month, it's ownership was passed onto Sam who agreed to make a sort of secret API for me to fish data from, process and present to users in a nice way. 

So far, a program to download and save data from the api is implemented in this repository (/com) and a simple user-interface was created (/ui) to allow users to search for entries--a ability that had not been available before. The current implementation (containing most of the uploaded files) of the ui can be found on [the current home of max_stuff](http://clemon.x10.mx/max_stuff/index.php).

## Max Parser

The Max Paser (/com) is a program that depending on the main statments can read MAX data from saved text files or from the api. It then saves files in a text file format or in a database. It also download and resizes entry images from the MAX website.

## MAX Stuff

MAX Stuff (/ui) aims to present data from the database in a different user interface that will users to navigate and query the MAX data more smoothly.
