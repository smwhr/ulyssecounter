<?php 
namespace UlysseCounter;

class TextIterator implements \Iterator{
  private $cursor = 0;
  public $maxcursor = null;
  const CHUNK_SIZE = 30;

  public function __construct($filename) {
      $this->file = fopen($filename, 'r');
      $this->maxcursor = filesize($filename);
      $this->cursor = 0;
      $this->currentChunk = null;
  }

  function rewind() {
      rewind($this->file);
      $this->cursor = 0;
  }

  function current() {
      $sentence = trim($this->currentChunk);
      $sentence = preg_replace("#\s\s+#u"," ", $sentence);
      return $sentence;
  }

  function key() {
      return $this->cursor + strlen($this->currentChunk);
  }

  function next() {
      $this->cursor += strlen($this->currentChunk);
  }

  function valid() {
      //si on a dépassé la taille du fichier, on arrête l'iterator
      if($this->cursor >= $this->maxcursor) return false;

      //on se positionne au niveau du curseur
      fseek($this->file, $this->cursor);
  
      //on extrait des morceaux de taille self::CHUNK_SIZE jusqu'à ce que
      // - on y trouve un "."" 
      // - ou que le fichier se termine
      $totalChunk = "";
      $dotPositon = 0;
      while( false !== ($chunk = fgets($this->file, self::CHUNK_SIZE)) ){
        $dotPosition = strrpos($chunk, ".");
        
        if($dotPosition !== false){
          $totalChunk .= substr($chunk, 0, $dotPosition+1);
          break;
        }else{
          $totalChunk .= $chunk;
        }

      }

      //on garde en mémoire le chunk courant
      $this->currentChunk = $totalChunk;
      return true;
  }

}