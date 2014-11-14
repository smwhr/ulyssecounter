/*
 * On utilise "match" pour récupérer les mots distincts (accents compris) sans prendre la ponctuation
 * Pour la clé, on utilise [longueur du mot, mot]
 * Cela nous permettra de faire des requêtes plus précises par la suite
 */

function(doc){
  words = doc.sentence.match(/[\wàáâãäåçèéêëìíîïðòóôõöùúûüýÿ]+/g)
  words.forEach(function(w){
    emit([w.length, w.toLowerCase()],1);
  })
}