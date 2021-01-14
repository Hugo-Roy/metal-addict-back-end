### Nos routes

|Endpoint|Méthode HTTP|Description|Rôles|Retour|
|-|-|-|-|-|
|`/api/home`|`GET`|Récupération des dernières reviews postées|USER et VISITOR|200|
|`/api/search/{bandId}`|`POST`|Liste des events en fonction des paramètres donnés|USER et VISITOR|200|
|`/api/user/{id}`|`GET`|Récupération des données de l'utilisateur|USER et VISITOR|200 ou 404|
|`/api/user`|`POST`|Création des données de l'utilisateur|VISITOR|200|
|`/api/user/{id}`|`PUT`|Modification des données de l'utilisateur|USER(owner)|200, 204 ou 204|
|`/api/user/{id}`|`PATCH`|Modification des données de l'utilisateur|USER(owner)|200, 204ou 204|
|`/api/event/{setlistId}`|`GET`|Récupération d'un évènement et de ses données associées|USER et VISITOR|200 ou 404|
|`/api/event/{id}`|`POST`|Création d'un évènement en base donnée et l'associer au user concerné|USER|201 ou 404|
|`/api/review`|`GET`|Récupération d'une liste de reviews|USER et VISITOR|200 ou 404|
|`/api/review/{id}`|`GET`|Récupération d'une review et de ses données associées|USER et VISITOR|200 ou 404|
|`/api/review/add`|`POST`|Ajout d'une review|USER|201|
|`/api/review/{id}`|`PUT`|Modification d'une review et de ses données associées|USER(owner)|200, 204 ou 204|
|`/api/review/{id}`|`PATCH`|Modification d'une review et de ses données associées|USER(owner)|200, 204 ou 204|
|`/api/review/{id}`|`DELETE`|Suppression d'une review|USER(owner)|200, 204 ou 404|
|`/api/picture`|`POST`|Ajout d'une photo|USER|201|
|`/api/picture/{id}`|`DELETE`|Suppression d'une photo|USER(owner)|200, 204 ou 404|