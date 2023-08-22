<?php

namespace HighWire\Utility;

/**
 * Apath Utility Class.
 */
class Apath {

  /**
   * Gets the corpus code from an apath.
   *
   * @param string $apath
   *   The apath to the resource.
   * @return string
   *   The corpus code for the apath.
   */
  public static function getCorpus(string $apath): string {
    $parts = explode("/", $apath);
    if (count($parts) == 2) {
      return str_replace(".atom", "", $parts[1]);
    }
    else {
      return $parts[1];
    }
  }

  /**
   * Get a list of unique corpus codes from a list of apaths.
   * 
   * @param string[] $apaths
   *   A list of atom paths.
   * @return string[]
   *   The corpus codes for the apaths.
   */
  public static function getCorpusCodes(array $apaths = []): array {
    $corpora = [];
    foreach ($apaths as $apath) {
      $corpus = Apath::getCorpus($apath);
      $corpora[$corpus] = TRUE;
    }
    return array_keys($corpora);
  }

  /**
   * Given a list of apaths, sort them by corpora.
   * 
   * @param string[] $apaths
   *   A list of atom paths.
   * @return array
   *   An array of corpus codes, each containing a list of apaths.
   */
  public static function sortByCorpora(array $apaths = []): array {
    $corpora = [];
    foreach ($apaths as $apath) {
      $corpus = Apath::getCorpus($apath);
      $corpora[$corpus][] = $apath;
    }
    return $corpora;
  }

  /**
   * Gets a short content path (cpath) given an apath (eg. /content/1/2/3)
   *
   * @param string $apath
   *   The apath to the resource.
   * @param string $prefix
   *   Replace the corpus code with this prefix.
   * @return string
   *   The content path for the apath.
   */
  public static function getContentPath(string $apath, string $prefix = 'content'): string {
    $parts = explode("/", $apath);
    $parts[1] = $prefix;
    $parts[count($parts) - 1] = str_replace(".atom", "", $parts[count($parts) - 1]);
    return implode('/', $parts);
  }

  /**
   * Gets long content path (cpath) given an apath (eg. /content/corpus/1/2/3)
   * 
   * @param string $apath
   *   The apath to the resource.
   * @param string $prefix
   *   Replace the corpus code with this prefix.
   * @return string
   *   The content path for the apath.
   */
  public static function getLongContentPath(string $apath, string $prefix = 'content'): string {
    $parts = explode("/", $apath);
    $parts[0] = $prefix;
    $parts[count($parts) - 1] = str_replace(".atom", "", $parts[count($parts) - 1]);
    return "/" . implode('/', $parts);
  }

  /**
   * Given a corpus code, validate that it is well formed.
   *
   * @param string $corpus
   *   The corpus code.
   * @return bool
   *   Returns TRUE if the corpus code is well formed, FALSE otherwise.
   */
  public static function validateCorpusCode(string $corpus): bool {
    return boolval(preg_match('/^[a-z][a-z0-9]+$/', $corpus));
  }

  /**
   * Given an apath, validate that it is well formed.
   *
   * @param string $apath
   *   The atom path.
   * @return bool
   *   Returns TRUE if the apath is well formed, FALSE otherwise.
   */
  public static function validate(string $apath): bool {
    $corpus = Apath::getCorpus($apath);
    if (empty($corpus)) {
      return FALSE;
    }

    if (!Apath::validateCorpusCode($corpus)) {
      return FALSE;
    }

    return boolval(preg_match('/^(\/[a-zA-Z0-9_.-]+)+\.atom$/', $apath));
  }

}
