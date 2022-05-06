# Alice Sirene

> Anthony Laborie / Mai 2022

## Environnement utilisé durant le développement
* Symfony 4.4.41
* Composer 2.0.13
* Mamp 6.6
    * Apache 2.4.46
    * PHP 7.4.21
    * MySQL 5.7.34
* Node 12.22.8

## Installation
1. Clonez le répository GitHub :
```
    git clone https://github.com/laborieDev/alice-sirene.git
```

2. Téléchargez et installez les packages utilisés dans le projet avec [Composer](https://getcomposer.org/download/) :
```
    composer install
```

## Modifier le JS et/ou le CSS

1. Téléchargez et installez les modules Node.js :
```
    npm install
```

2. Lancer le webpack Encore :
```
    npm run dev-server
```

3. Build les assets
```
    npm run build
```

## Ajouter une route

Si vous souhaitez pouvoir récupérer cette route dans votre code JS, mettez à jour le fichier fos_js_routes.json :
```
    php bin/console fos:js-routing:dump --format=json --target=public/js/fos_js_routes.json
```

## L'API

### Recherche d'une entreprise selon le nom
```
    /api/search/{term}
```

Paramètres :
* term : nom de l'entreprise
* page : page souhaitée (Par défaut : 1)
* perPage : nombre de résultats par page (Par défaut : 3)

Réponse :
* searchName : Le nom recherché
* page : page souhaitée
* perPage : nombre de résultats par page 
* totalPages : Nombre de pages au total
* totalResults : Nombre de résultats au total
* etablissements : Données envoyées par l'API Sirène sur les établissements trouvés
* renderHtml : Rendu Html de la liste

### Recherche des données d'un établissement selon son SIRET
```
    /api/show/{siret}
```

Paramètres :
* siret : SIRET de l'entreprise

Réponse :
* etablissement : Données envoyées par l'API Sirène sur les établissements trouvés
* renderHtml : Rendu Html des informations de l'entreprise
