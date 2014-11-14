WordCounter
===========
Ce projet montre comment enregistrer phrase à phrase dans une base CouchDb un très long texte que l'on découpe en phrases à l'aide d'un itérateur efficace.

Les phrases seront ensuite découpées par CouchDb qui s'occupera du comptage à l'aide de fonctions map/reduce

Correction améliorée de l'exercice vu en cours de 3e année de PHP le 13/11/2014 à SupInternet.


Installation
============
Le projet a une dépendance à Symfony/Console et à PHP 5.4

    composer install

Le projet nécessite qu'une instance de CouchDb soit en train de tourner avec une base de donnée disponible. Une vue doit être créée à l'aide des fichiers `map.js` et `reduce.js` qui se trouvent dans `src/couchjs/`

Enregistrez la vue sous le design `mydesign` avec le nom `myview` (ou les noms de votre choix).


Usage
=====

    ./console data:send swann.txt

Si votre base de donnée est hébergée sur un autre host que `127.0.0.1:5984` ou si son nom n'est pas `ulyssecounter`

    ./console data:send swann.txt --host="http://1.1.1.1:1111" --database="swanncounter"

Vérifiez que le processus d'indexation s'est bien terminé via l'admin de couch `http://127.0.0.1:5984/_utils/status.html`

Puis lancez votre requête
    
    ./console words:top mydesign myview --min=13 --limit=10

avec éventuellement les paramètres `--host` et `--database` si ils sont

Limites
=======

Les classes utilisées sont réduites à leur plus simple expression. Vous ne pourrez pas insérer de documents avec des `id`s précis ni effacer (il vous faudra supprimer la base et la recréer vide). Il y a peut-être également des cas non-traités, ils sont laissés en exercice.


Performances
============
L'insertion prend environ 2 minutes sur mon MacBook Air.
Le processing par couch des ~4000 phrases de "Du Côté de Chez Swann" se fait en environ 50 secondes.
Les queries vers les vues (assez imposantes en nombre de lignes) mettent une 20aine de secondes à se charger de couchdb vers PHP : c'est tout à fait le genre de chose qu'on mettrait en cache.



License
=======
    The MIT License (MIT)

    Copyright (c) 2014 TroisYaourts

    Permission is hereby granted, free of charge, to any person obtaining a copy
    of this software and associated documentation files (the "Software"), to deal
    in the Software without restriction, including without limitation the rights
    to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
    copies of the Software, and to permit persons to whom the Software is
    furnished to do so, subject to the following conditions:

    The above copyright notice and this permission notice shall be included in
    all copies or substantial portions of the Software.

    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
    IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
    FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
    AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
    LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
    OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
    THE SOFTWARE.