Curl:
curl -v http://localhost:8000 (Ver la aplicación con verbose (todo el contenido))

curl -d 'id=9&name=name' http://localhost:8000 (Pasar contenido)

curl -d 'id=9&name=name' -X PUT http://localhost:8000 (modificar contenido con PUT)

curl -d {'id'=9&'name'='name'} -H 'Content-Type: application/json'  -X PUT http://localhost:8000 (pasar un json al put)

Ejemplos: 

POST:

curl -X POST -H "Content-Type: application/json" -d '{"name":"marc"}' http://localhost:8000/api/users/store

curl -X POST -d "name=marc2" http://localhost:8000/api/users/store


GET:

curl http://localhost:8000/api/users

curl http://localhost:8000/api/users/1


DELETE 

curl -X DELETE http://localhost:8000/api/users/1


PUT:
curl -X PUT -d "name=newName" http://localhost:8000/api/users/1
curl -X PUT -H "Content-Type: application/json" -d '{"name":"newName"}' http://localhost:8000/api/users/1

