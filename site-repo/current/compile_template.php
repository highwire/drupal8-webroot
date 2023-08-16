<?php
/**
 * @file
 * Translates a template into a usable feature module
 *
 * This file takes a module template (such as jcorex_templates) and creates a usable module out of it
 * It can be run from command-line using the following syntax:
 *  php compile_template.php <source-template-directory> <target-module-directory>
 *
 * For example:
 *  php compile_template.php jcorex_templates /opt/sites/jcorex_site/jcorex_site
 */

list($args, $options) = parse_args();

// Check to make sure we have the right number of arguments
if (count($args) < 2) {
  if (count($args) == 0) {
    //Display help text if it was run "bare-bones" with no arguments
    print_help_message();
    exit();
  }
  print "Wrong number of arguments\n";
  exit(1);
}


// Check to make sure we have the right number of arguments
if (!isset($options['jcode']) || !isset($options['title'])) {
  //Display help text if it was run "bare-bones" with no arguments
  print_help_message();
  exit();
}

// Set up our arguments
$indir = $args[0];
$outdir = $args[1];

// Check to make sure the input directory exists
if (!is_dir($indir)) {
  print $indir . " is not a directory\n";
  exit(1);
}

// Check to make sure the output directory does not exist
if (is_dir($outdir) || is_file($outdir) || is_link($outdir)) {
  print $outdir . " already exists\n";
  exit(1);
}

// Derive the template name and the target name
$in_dir_parts = explode('/', $indir);
$template_name = array_pop($in_dir_parts);

$out_dir_parts = explode('/', $outdir);
$target_name = str_replace('-', '_', array_pop($out_dir_parts));

// Check to make sure the parent of the output directory exists
$out_dir_parts = explode('/', $outdir);
if (count($out_dir_parts) > 1) {
  array_pop($out_dir_parts);
  $check_dir = implode('/', $out_dir_parts);
  if (!is_dir($check_dir)) {
    print $check_dir . " is not a directory\n";
    exit(1);
  }
}

// Copy the template directory
$success = 1;
system ('cp -r '.$indir.' '.$outdir, $success);
if ($success !== 0) {
  print "Failed to copy source directory\n";
  exit(1);
}

// Rename directories in the output directory
recursive_rename($outdir, $template_name, $target_name);

// If option "name" is set, set the human name
if (isset($options['jcode'])) {
  recursive_replace($outdir, $template_name . '_jcode', $options['jcode']);
}

// If option "title" is set, set the human name
if (isset($options['title'])) {
  recursive_replace($outdir, $template_name . '_title', $options['title']);
}

// Rename directories in the output directory
recursive_replace($outdir, $template_name, $target_name);

// The top level directory should use '-' instead of '_'
// @@TODO - The top level directory should use '-' instead of '_'
if (isset($options['prefix'])) {
  $out_dir_parts = explode('/', $outdir);
  $site_folder = $options['prefix'] . '-' . array_pop($out_dir_parts);
  $new_path = implode('/', $out_dir_parts) . '/' . $site_folder;
  shell_exec('mv ' . $outdir . ' ' . $new_path);
}

// UTILITY FUNCTIONS
// -----------------

/**
* Recursively rename files and directories
*
* @param $directory
*   Target directory
* @param $find
*   needle-text in filenames we want to replace
* @param $replace
*   replacement text that will replace all $find text in filepaths
* @return NULL
*/
function recursive_rename($directory, $find, $replace) {
  $files = scandir($directory);
  foreach ($files as $file) {
    if ($file == '.' || $file == '..') continue;

    $full_path = $directory . '/' . $file;
    if (strpos($file, $find) !== FALSE) {
      $new_path = str_replace($find, $replace, $full_path);
      shell_exec('mv ' . $full_path . ' ' .$new_path);
      $full_path = $new_path;
    }

    // Recurse
    if (is_dir($full_path)) {
      recursive_rename($full_path, $find, $replace);
    }
  }
}

/**
* Recursively replace strings in files
*
* This function will replace the given string in files for all files in
* the given directory and subdirectories (recursively)
*
* @param $directory
*   Target directory that contains
* @param $find
*   needle-text in file content we want to replace
* @param $replace
*   replacement text that will replace all $find text
* @return NULL
*/
function recursive_replace($directory, $find, $replace) {
  $files = scandir($directory);
  foreach ($files as $file) {
    if ($file == '.' || $file == '..' || $file == '.git') continue;

    $full_path = $directory . '/' . $file;
    if (is_file($full_path)) {
      $content = file_get_contents($full_path);
      $content = str_replace($find, $replace, $content);
      file_put_contents($full_path, $content);
    }

    // Recurse
    if (is_dir($full_path)) {
      recursive_replace($full_path, $find, $replace);
    }
  }
}

/**
* Parse command-line arguments
*
* When in CLI mode, this function will parse command-line arguments into an easy to use array
*
* @return array
*/
function parse_args(){
  global $argv;
  $raw = $argv;

  $options = array();
  $args = array();

  array_shift($raw);

  foreach ($raw as $arg){
    if (substr($arg,0,2) == '--'){
      $eqPos = strpos($arg,'=');
      if ($eqPos === false) {
        $key = substr($arg,2);
        $options[$key] = isset($options[$key]) ? $options[$key] : true;
      }
      else {
        $key = substr($arg,2,$eqPos-2);
        $options[$key] = substr($arg,$eqPos+1);
      }
    }
    else if (substr($arg,0,1) == '-'){
      if (substr($arg,2,1) == '='){
        $key = substr($arg,1,1);
        $options[$key] = substr($arg,3);
      }
      else {
        $chars = str_split(substr($arg,1));
        foreach ($chars as $char) {
          $key = $char;
          $options[$key] = isset($options[$key]) ? $options[$key] : true;
        }
      }
    }
    else {
      $args[] = $arg;
    }
  }
  return array(
    $args,
    $options,
  );
}

function print_help_message(){
  print "This utility takes four arguments, ";
  print "\n1. the template directory ";
  print "\n2. the target site name. ";
  print "\n3. journal jcode (--jcode=ptjournal)";
  print "\n4. journal title (--title='Physical Therapy Journal')";
  print "\n5. [optional] output directory prefix (--prefix='drupal-site'), e.g., drupal-site', 'drupal-pub'.";
  print "\nThe target directory name is used to find and replace strings and files in the template.\n";
  print "php compile_template.php <source-template-directory> <target-module-directory> --jcode=<jcode> --title=<journal title>\n";
  print "For Example: php compile_template.php highwire_jnl_template ./jnl_ptjournal --jcode=ptjoutnal --title='Physical Therapy Journal'\n";
}
