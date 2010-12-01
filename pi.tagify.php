<?php
/*
Copyright (C) 2010 Karl Swedberg (for Fusionary <http://fusionary.com>)
*/

$plugin_info = array(
  'pi_name'     => 'Tagify',
  'pi_version'    => '1.0',
  'pi_author'     => 'Karl Swedberg',
  'pi_author_url'   => 'http://fusionary.com/',
  'pi_description'  => 'Converts faux, square-bracket tags to real, curly, EE template tags.',
  'pi_usage'      => Tagify::usage()
);

class Tagify {
  var $return_data;

  function tagify()
  {

    global $TMPL;

    // get the tag data
    $tagdata = $TMPL->tagdata;
    $tag_name = ($TMPL->fetch_param('tag_name'));

    if ($tag_name) {
      $all_methods = explode('|', $tagdata);
      $all_methods = array_walk( $all_methods, array( get_class($this), 'walk_tags' ), 'replace_' );
    } else {
      $all_methods = get_class_methods($this);
      $all_methods = array_filter( $all_methods, array( get_class($this), 'filter_tags' ) );
    }

    $tagged_data = $tagdata;

    foreach ($all_methods as $key => $replace_method) {

      $tname = str_replace('replace_', '', $replace_method);
      $tag_pattern = '@\[(' . $tname . '[^\]]*)\]@';

      if (method_exists($this, $replace_method)) {
        $tagged_data = preg_replace_callback( $tag_pattern, array( get_class($this), $replace_method ), $tagged_data, -1, $count );
      }

    }

    $this->return_data =  $tagged_data;
  }

  /** =replace callback functions
  ************************************************************
  each function below should start with "replace_"
  and end with the tag_name
  ************************************************************/

  function replace_embed($matches) {
    $params = preg_split('@\s+@', $matches[1]);

    $output = '';
    foreach ($params as $param) {
      $p = explode('=', $param);
      $output .= ' ' . $p[0] . '="' . $p[1] . '"';
    }
    $output = preg_replace('/^\s+/','', $output);

    return "{" . $output . "}";
  }
  
  function replace_current_uri($matches) {
    global $IN;

    $qs = http_build_query($_GET);
    if ($qs) {
      $qs = '?' . preg_replace( '@=$@', '', $qs );
    }

    return '/'. implode('/', $IN->SEGS) . '/' . $qs;

  }

  /** =private utility functions
  ************************************************************/
  function walk_tags(&$item, $index, $prefix) {
    $item = $prefix . $item;
  }

  function filter_tags($item) {
    return substr($item, 0, 8) == 'replace_';
  }

  // USAGE (APPEARS IN THE CONTROL PANEL FOR THE PLUGIN)
  function usage() {

    ob_start();
    ?>
  Sorry. No documentation yet. <?php
      $buffer = ob_get_contents();
      ob_end_clean();
      return $buffer;
    }

  }
?>