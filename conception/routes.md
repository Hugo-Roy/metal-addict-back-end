### Nos routes

|Endpoint|Méthode HTTP|Description|Rôles|Retour|Avancement|
|-|-|-|-|-|-|
|`/api/search/{bandId}`|`GET`|Liste des events en fonction des paramètres donnés|USER et VISITOR|200|done|
|`/api/user?setlistId=x`|`GET`|Récupération d'une liste d'utilisateurs en fonction d'un setlistId|USER et VISITOR|200 ou 404|done|
|`/api/user/{id}`|`GET`|Récupération des données de l'utilisateur|USER et VISITOR|200 ou 404|done|
|`/api/user`|`POST`|Création des données de l'utilisateur|VISITOR|200|done|
|`/api/user/{id}`|`PUT`|Modification des données de l'utilisateur|USER(owner)|200, 204 ou 204|doing|
|`/api/user/{id}`|`PATCH`|Modification des données de l'utilisateur|USER(owner)|200, 204ou 204|doing|
|`/api/event/{setlistId}`|`GET`|Récupération d'un évènement et de ses données associées|USER et VISITOR|200 ou 404|done|
|`/api/event/{setlistId}`|`POST`|Création d'un évènement en base donnée et l'associer au user concerné|USER|201 ou 404|done|
|`/api/review`|`GET`|Récupération d'une liste de reviews|USER et VISITOR|200 ou 404|doing|
|`/api/review/{id}`|`GET`|Récupération d'une review et de ses données associées|USER et VISITOR|200 ou 404|done|
|`/api/review/add`|`POST`|Ajout d'une review|USER|201|done|
|`/api/review/{id}`|`PUT`|Modification d'une review et de ses données associées|USER(owner)|200, 204 ou 204|done|
|`/api/review/{id}`|`PATCH`|Modification d'une review et de ses données associées|USER(owner)|200, 204 ou 204|done|
|`/api/review/{id}`|`DELETE`|Suppression d'une review|USER(owner)|200, 204 ou 404|done|
|`/api/picture/{setlistId}`|`POST`|Ajout d'une photo|USER|201|doing|
|`/api/picture/{id}`|`DELETE`|Suppression d'une photo|USER(owner)|200, 204 ou 404|done|
|`/api/band`|`GET`|Récupération de la liste des groupes|VISITOR et USER|200|done|
|`/api/country`|`GET`|Récupération de la liste des pays|VISITOR et USER|200|done|