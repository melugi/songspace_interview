## Deployment

This solution was developed using MariaDB 10.5.8. To configure MariaDB you can change the URL string in the .env file. The URL string template is: `DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=mariadb-10.5.8"`

To install dependencies run `composer install`, migrations are located in the migrations folder. Once MariaDB has been deployed and configured you can run the migrations by executing `php bin/console doctrine:migrations:migrate`.

## Implementation Design

Originally I wanted to go for a console application but it has been a while since I've worked with symfony and so I decided to go with a more standard web app approach where the app exposes various endpoints to do CRUD operations through the browser using forms. I decided on this approach because it allowed me to leverage some of the symfony tools (namely `make:crud` and some doctrine commands) to have fully working CRUD functionality on each entity. Then I worked backwards to implement any additional features and make changes to the code which, in my opinion, made it more readable and maintainable.

I liked this approach because it let me finish the task close to the time limit (~6-7 hours) and I think if we were to put something like this into production we could strip out the logic into several microservices easily. Specifically I think that Catalog/Song management could be it's own service as song pre-processing/catalog management could potentially have enough logic to warrant it.

There are no unit tests as I didn't write any complicated business logic. I considered unit testing the `SongFileManager`, which handle uploading/deleting of song files. However the functionality there is so barebones there aren't any corner failure cases for testing to root out.

## Routes

```
+ user/
|   /           -- GET - Shows all users
|   /new        -- GET|POST - Shows new user form
|   /{id}       -- GET - Shows details for user with {id}
|   /{id}       -- POST - Deletes the user
|   /{id}/edit  -- GET|POST - Edits the user
+ catalog/
|   /           -- GET - Shows all catalogs
|   /new        -- GET|POST - Shows new catalog form
|   /{id}       -- GET - Shows details for catalog with {id}
|   /{id}       -- POST - Deletes the catalog
|   /{id}/edit  -- GET|POST - Edits the catalog
+ song/
|   /           -- GET - Shows all songs
|   /new        -- GET|POST - Shows new song form
|   /{id}       -- GET - Shows details for song with {id}
|   /{id}       -- POST - Deletes the song
|   /{id}/edit  -- GET|POST - Edits the song

```

## Entity Design



<table>
<tr>
<td>

|User    |
:--------|
|name    |
|email   |
|catalogs|

</td>

<td>

|Catalog                   |
:--------------------------|
|title                     |
|user                      |
|songs                     |

</td>

<td>

|Song        |
:------------|
|title       |
|artist      |
|album       |
|songFileName|
|catalogs    |

</td>
</tr>
</table>

## Needed Improvements

Here are the features we can work to include:

* Implement collision detection on song title + artist
* Make the routes HTTP verbs match the actions (i.e Use PUTS and DELETES; `POST /{id}` deletes entities which I think is terrible)

