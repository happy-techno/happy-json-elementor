<?php
/**
 * Plugin Name:       Happy Technology Json for Elementor
 * Plugin URI:        https://plugins.happytechnology.fr/plugins
 * Description:       Connect any JSON source to any elementor widgets.
 * Version:           1.0.0
 * Requires PHP:      7.0
 * Author:            Yohann Joyeux
 * Author URI:        https://www.linkedin.com/in/joyeux-yohann-36861a97/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       happy-technology-json-for-elementor
*/

/*
*************************************************************************************
*EXAMPLES
*************************************************************************************
EXAMPLE 1 for Toogle
JSON Data:
{
   "acf":{
      "sku":"123456789000002",
      "name":"Brugse Zot",
      "flavour":"flavour_brown",
      "degree_of_alcohol":"16",
      "test_kind_of_beer":"4",
      "digitalized":"1",
      "activated":"",
      "timeline":{
         "shipped":"19/07/2019",
         "delivered":"20/07/2019",
         "sell":"03/12/2019",
         "activated":"01/01/2020"
      }
   }
}

Widget Settings Name :
tabs

JSON paths :
acf.timeline

If Array (key=>value), set properties match like myProperty:value (or key) :
tab_title:key
tab_content:value


EXAMPLE 2 for Toogle
JSON Data:
{
  "records" : [ {
    "Id" : "011220036589",
    "Name" : "YanAir",
    "Type" : "Airline",
    "Website" : "www.yanair.com.ua"
  }, {
    "Id" : "023251485000",
    "Name" : "FLYBE",
    "Type" : "Airline",
    "Website" : "www.flybe.com"
  } ]
}

Widget Settings Name :
tabs

JSON paths :
records

If Array (key=>value), set properties match like myProperty:value (or key) :
tab_title:Id
tab_content:Name


EXAMPLE 3 for heading
JSON Data:
{
  "totalSize" : 1,
  "records" : [ {
    "Name" : "YanAir",
    "Type" : "Airline",
    "Website" : "www.yanair.com.ua"
  } ]
}

Widget Settings Name :
title

JSON paths :
records.0.Name


EXAMPLE 4 for heading with ACF (field : cf1ele1)
JSON Data:
{
	"acf":{
		"cf1ele1":"hello",
		"cf1ele2":"world"
	}
}

Widget Settings Name :
title

JSON paths :
acf.cf1ele1
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.
if ( ! defined( 'ELEMENTOR_PATH' ) ) return; // Elementor plg needed


/**************************************************************************************
 *
 *EDITOR RENDER
 *
 **************************************************************************************/
// See -> https://code.elementor.com/php-hooks/

add_action('elementor/widget/before_render_content', function ($widget)
{
    $debug_trace = false;

    if (\Elementor\Plugin::$instance
        ->editor
        ->is_edit_mode())
    {
        if ($debug_trace) error_log(print_r('is_edit_mode === true : EDITOR', true));
        htjfe_before_render_content_actions($widget, true);
    }
    else
    {
        if ($debug_trace) error_log(print_r('is_edit_mode === false : FRONTEND', true));
        htjfe_before_render_content_actions($widget, false);
    }

}
, 10, 2);


/**************************************************************************************
 *
 *DEBUG FUNCTIONS
 *
 **************************************************************************************/
if (!function_exists('htjfe_before_render_content_debug')) {
  function htjfe_before_render_content_debug($settings, $json_object, $context)
  {
      if (!isset($settings['phygital_widget_settings'])) return;

      if($settings['is_debug_js']=='yes')	{
        error_log('');
        error_log("=== START DEBUG MODE : $context ===");
        error_log('*** JSON FORMAT ***');
        error_log(print_r($json_object, true));
        error_log('*** ARRAY FORMAT ***');
        error_log(print_r(json_decode($json_object, true), true));
      	?>
      	<script>
        console.log("=== START DEBUG MODE : <?php echo $context; ?> ===");
      	console.log(<?php echo $json_object; ?>);
      	console.log("=== END DEBUG MODE : <?php echo $context; ?> ===");
      	</script>
      	<?php
        error_log("=== END DEBUG MODE : $context ===");
      }
  }
}



/**************************************************************************************
 *
 *RENDER FUNCTIONS
 *
 **************************************************************************************/
if (!function_exists('htjfe_before_render_content_actions')) {
  function htjfe_before_render_content_actions($widget, $is_editor)
  {
      $debug_trace = false;

      $settings = $widget->get_settings();
      if (isset($settings['phygital_widget_settings']))
      {
          $widget_settings = $settings['phygital_widget_settings'];
      }
      else
      {
          return;
      }
      $item_ws_value = (isset($settings['ws_url']) ? $settings['ws_url'] : null);
      $is_acf_post = (isset($settings['is_acf_post']) ? $settings['is_acf_post'] : null);
      $json_content = (isset($settings['json_content']) ? $settings['json_content'] : null);
      $is_function_data = (isset($settings['is_function_data']) ? $settings['is_function_data'] : null);

      if ($debug_trace) error_log('');
      if ($debug_trace) error_log('');
      if ($debug_trace) error_log('');
      if ($debug_trace) error_log(' ************************************************************************************************************ ');
      if ($debug_trace) error_log(' ****************************            NEW before_render_content               **************************** ');
      if ($debug_trace) error_log(' ************************************************************************************************************ ');
      if ($debug_trace) error_log(' --- PARAMS --- ');
      if ($debug_trace) error_log(' debug_trace : ' . $debug_trace);
      if ($debug_trace) error_log(' widget_settings : ' . print_r($widget_settings, true));
      if ($debug_trace) error_log(' item_ws_value : ' . $item_ws_value);
      if ($debug_trace) error_log(' is_acf_post : ' . $is_acf_post);
      if ($debug_trace) error_log(' is_function_data : ' . $is_function_data);
      if ($debug_trace) error_log(' json_content : ' . print_r($json_content, true));

  	  htjfe_before_render_content_debug($settings, json_encode($widget->get_data(), true), "DATA");

      if ((isset($item_ws_value) && $item_ws_value != '')
        || (isset($json_content) && $json_content != '')
        || (isset($is_acf_post) && $is_acf_post === 'yes')
        || (isset($is_function_data) && $is_function_data != '')
      )
      {

          //SET $json_object value depending on the JSON source
          //choose between a json url, json content and the current post
          $json_object = apply_filters('htjfe_filter_json_pro', $settings);

          if(is_null($json_object)){
            if ($item_ws_value != '')
            {
                //JSON CALL
                $opts = array(
                    'http' => array(
                        'method' => "GET",
                        'header' => "Accept-language: en\r\n"
                    )
                );
                $context = stream_context_create($opts);

                // Accès à un fichier HTTP avec les entêtes HTTP indiqués ci-dessus
                $json = file_get_contents($item_ws_value, false, $context);
                $json_object = json_decode($json, true); //true => Array, False => Object (default)

            }elseif ($json_content != ''){
                //JSON RAW CONTENT
                $json_object = json_decode($json_content, true);
                if(!is_array($json_object)){
                  error_log("ERROR : The JSON Content is not valid, please check content !");
                  error_log("Error Detail : Your JSON Content is => ". $json_content);
                  return;
                }
            }
          }

          if ($debug_trace) error_log(print_r('htjfe_before_render_content_actions > $json_object: ', true));
          if ($debug_trace) error_log(print_r($json_object, true));

  		    htjfe_before_render_content_debug($settings, json_encode($json_object), "JSON");

          //pour chaque propriete du widget
          foreach ($widget_settings as $widget_key => $widget_value)
          {
              //nom de la propriete a modifier
              $widget_settings_name = $widget_value['widget_settings_name'];
              if ($debug_trace) error_log('widget_settings_name: ' . $widget_settings_name);

              //si le settings est vide on passe au suivant
              if (empty($widget_settings_name))
              {
                  if ($debug_trace) error_log(print_r('settings vide, suivant', true));
                  continue;
              }

              $instructions = $widget_value['widget_json_array_todo'];
              if ($debug_trace) error_log('$instructions=>[widget_json_array_todo]');
              if ($debug_trace) error_log(print_r($instructions, true));

              $array_instructions = preg_split('/[\r\n]+/', $instructions);
              if ($debug_trace) error_log(print_r('$array_instructions', true));
              if ($debug_trace) error_log(print_r($array_instructions, true));



              //transformation du chemin json dans le ws (de x.z.z à ['x']['y']['z'])
              $widget_json_paths = $widget_value['widget_json_paths'];


              if($widget_json_paths!=''){
                //$widget_json_paths_array = array_values(array_filter(explode('.', $widget_json_paths)));
                $widget_json_paths_array = array_values(explode('.', $widget_json_paths));

                if ($debug_trace) error_log('widget_json_paths_array => ');
                if ($debug_trace) error_log(print_r($widget_json_paths_array, true));

                $temp_wjp = '';
                foreach ($widget_json_paths_array as $key => $value)
                {
                    $temp_wjp .= '["' . $value . '"]';
                }
                $widget_json_paths = $temp_wjp;
                if ($debug_trace) error_log('$widget_json_paths');
                if ($debug_trace) error_log(print_r($widget_json_paths, true));

                eval("\$temptest = isset(\$json_object{$widget_json_paths});");
                if ($temptest)
                {
                    $ws_json_paths_value = eval("return \$json_object{$widget_json_paths};");
                    if ($debug_trace) error_log('$ws_json_paths_value is specified');
                }
                else
                {
                    error_log("ERROR : The JSON path value : $widget_json_paths is not correct, please check path !"); //["records[0]"]["Name"] records[0].Name
                    continue; //EXIT LOOP
                }
              }
              else //case root json is array directly, no json path needed
              {
                  $ws_json_paths_value = $json_object; //root path if nothing is specified
                  if ($debug_trace) error_log('$ws_json_paths_value is NOT specidied, use root path');
              }

              if ($debug_trace) error_log(print_r('$ws_json_paths_value : ', true));
              if ($debug_trace) error_log(print_r($ws_json_paths_value, true));

              if (!is_array($ws_json_paths_value))
              {
                  if ($debug_trace) error_log(print_r('$ws_json_paths_value is NOT an Array', true));
                  $widget->set_settings($widget_settings_name, $ws_json_paths_value);
              }
              else
              {
                  if ($debug_trace) error_log(print_r('$ws_json_paths_value IS an Array => using array_instructions', true));
                  //verification que les instructions existent
                  if (empty(array_filter($array_instructions)))
                  {
                      if ($debug_trace) error_log(print_r('array_instructions vide, suivant', true));
                      continue; //EXIT LOOP

                  }

                  //instruction a executer pour chaque element du flux
                  $tmp_settings = array();
                  //on parse le flux json de data pour construire le settings $widget_settings_name
                  $array_instructions_error = false;
                  foreach ($ws_json_paths_value as $key => $value)
                  { //[sell] => 25/07/2019
                      $temp = array();
                      if ($debug_trace) error_log('ws_json_paths_value:value=>' . print_r($value, true));

                      foreach ($array_instructions as $ikey => $ivalue)
                      { //tab_title:$key, tab_content:$value, ...
                          $array_instructions_item = array_values(array_filter(explode(':', $ivalue)));

              						if(count(array_filter(explode('=', $ivalue)))>1){
                                          error_log("ERROR : couple of property is 'property:value' with ':' as separator and NOT with '=', please modify it !");
                                          $array_instructions_error = true;
                                          break;
              						}

                          $tmp0 = $array_instructions_item[0];
                          $tmp1 = $array_instructions_item[1];

                          if (($tmp1 === "key" || $tmp1 === "value"))
                          {
                              if ($debug_trace) error_log(print_r($tmp0 . ' -> ' . $tmp1, true));
                              $temp[$tmp0] = $$tmp1;
                          }
                          elseif (!is_null($value[$tmp1]))
                          {
                              if ($debug_trace) error_log(print_r($tmp0 . ' -> ' . $tmp1 . '. ws_json_paths_value[$tmp1]=' . $value[$tmp1], true));
                              $temp[$tmp0] = $value[$tmp1];
                          }
                          else
                          {
                              error_log("ERROR : The instruction must be key or value, nothing else (like $tmp1), please modify it !");
                              $array_instructions_error = true;
                              break;
                          }

                      }
                      if ($array_instructions_error == true)
                      {
                          break;
                      }

                      //Get the initial object and merge with the constructed one (to avoid missing mandatory elements)
                      if (is_array($settings[$widget_settings_name]))
                      {
                          $temp = array_replace_recursive($settings[$widget_settings_name][0], $temp);
                          if ($debug_trace) error_log("temp array:");
                          if ($debug_trace) error_log(print_r($temp, true));
                      }

                      array_push($tmp_settings, $temp);
                  }

                  if ($array_instructions_error == true)
                  {
                      continue;
                  }

                  if ($debug_trace) error_log(print_r('$tmp_settings', true));
                  if ($debug_trace) error_log(print_r($tmp_settings, true));

                  if ($debug_trace) error_log(print_r('$settings[$widget_settings_name] : ' . $widget_settings_name, true));
                  if (!isset($settings[$widget_settings_name]))
                  {
                      error_log("ERROR : The Widget Settings Name : $widget_settings_name does not exist !");
                      continue; //EXIT LOOP

                  }
                  else
                  {
                      if ($debug_trace) error_log(print_r($settings[$widget_settings_name], true));
                  }

                  $tmp_settings = array_replace_recursive($settings[$widget_settings_name], $tmp_settings);
                  if ($debug_trace) error_log(print_r('$tmp_settings after array_replace', true));
                  if ($debug_trace) error_log(print_r($tmp_settings, true));

                  if (!empty(array_filter($tmp_settings)))
                  {
                      $widget->set_settings($widget_settings_name, $tmp_settings);
                  }
                  else
                  {
                      if ($debug_trace) error_log(print_r('$tmp_settings is EMPTY', true));
                  }
              }
          }
      }
      else
      {
          if ($debug_trace) error_log(print_r('before_render_content -> no ws_url, nor post_id => exit : ', true));
      }

  }
}



/**************************************************************************************
 *
 *EDITOR WIDGET ACTIONS
 *
 **************************************************************************************/

require('widgets-editor-action.php');
