"# apirestsymfony" 

This is a project using Symfony and related bundle to make a basic REST API, 
i relealised this project for recrutor and interested people.

This project use :
- hautelook/alice-bundle for fixtures
- JWT for authentication with lexik/jwt-authentication-bundle
- JMS serializer for serialisation


This project is deployed in Heroku with this command :
- heroku create
- heroku config:set SYMFONY_ENV=prod
- heroku push <branch-name>:master
- heroku open 
