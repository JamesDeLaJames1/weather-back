# Premise

Laravel back end application used as an API layer for front end CPA. Uses sanctum authorisation. Built quickly so the structure and api principles would be much more complex and fully featured in a real application of course 

## Database Setup

Database can be initially created and populated with an admin record using the following commands

```bash
php artisan migrate

php artisan db:seed
```



## Ports and Hosts
Configured to run on the following host

```bash
http://localhost:8000
```

With the following front end

```bash
http://localhost:3000
```



## Considerations
Back and front end applications are built to a quick standard. I have included some quickly done input validation, authorisation and some although limited error handling just to demonstrate the capabilities in laravel.
