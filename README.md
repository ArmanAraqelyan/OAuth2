### Project Description:
The project embraces the login and the registration features therefore it redirects to the profile page while there will be displayed name, surname and the registration time. The used technologies:
- Vue.js 
- Docker
- OAuth2
- PHP (Slim Framework)
- PDO
- MySQL

### Usage:
To open the project run the start.sh file which does the following processes:
- create MySQL database
- create PHP  (7.4 v)
- create Vue.js
The view host must be [http://localhost:9090](http://localhost:9090/) and the API must be [http://localhost:8080](http://localhost:8080/).
NOTE: API accepts only JSON type of requests.

### Possible Issues:
During the view and api interaction CORS ERRORS may occur, in this case add the following rows in the public/index.php
- error_reporting(E_ALL & ~E_USER_NOTICE);
- header("Access-Control-Allow-Origin: *");
- header("Access-Control-Allow-Headers: *");
