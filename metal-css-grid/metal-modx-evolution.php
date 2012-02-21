<?php

/*

Metal - Responsive CSS Grid Plugin - MODX Evolution plugin for http://cipalabs.com/projects/metal/

Description

- parse all the tables without border class or id and replace them with div columns. Apropiate clases are created
- parse all the images and remove inline width and height


Important
This plugin only works with tables that have only one row and all the columns are equal in width.


Install

- copy metal-css-grid folder to the plugin folder
- create a new plugin and name it: Metal - Responsive CSS Grid Plugin
- add a description: Plugin for Metal - Responsive CSS Grid
- paste the code
    
    include($modx->config['base_path'].'assets/plugins/metal-css-grid/metal-modx-evolution.php');

- in the Configuration area paste: 
    
    &tables_to_div_grid=Convert tables with no id, class or border to div grid;list;yes,no;yes&remove_inline_img_width_height=Remove inline width and height for images;list;yes,no;yes
    
- click outside the configuration area to update the parameters and configure the plugin to your needs
 
- for System Events check OnWebPagePrerender (You can use other system events if you like but for now this is the one I choose until I test other ones)


*/

$numbersToCol = array('','','sixCol','fourCol','threeCol','extraFive','twoCol','','','','','','twelveCol');


require_once('phpQuery/phpQuery.php');

$e = &$modx->Event;
$html = $modx->documentOutput;


$doc = phpQuery::newDocumentHTML($html);

/* remove inline width and height for images */
if($tables_to_div_grid == 'yes'){

    //add class to parent
    pq('table')->parent()->addClass('hasGrid');
    
    $tables = pq('table');
    
    /* parse tables and replace them with div grids  if no class id or border*/
    foreach($tables as $table) {
    	
    	$border = pq($table)->attr('border');
        $id = pq($table)->attr('id');
        $class = pq($table)->attr('class');
        
        if(!intval($border) || !intval($id) || !intval($class)){
                
            $tds = pq($table)->find('tr:first')->children('td');
            $colNo = count($tds);
            
            $rwWrpStart = '<div class="row isGrid">';
            $rwWrpEnd = '</div>';
            
            $replaceWith = "";
            
            foreach($tds as $td){
                
                $replaceWith .= '   <div class="'.$numbersToCol[$colNo].'">'.pq($td)->html().'</div>';
                
            }
            
            pq($table)->replaceWith($rwWrpStart.$replaceWith.$rwWrpEnd);
            
        }
    	
    }
}



/* remove inline width and height for images */
if($remove_inline_img_width_height == 'yes'){
    
  pq('img')->removeAttr('width');
  pq('img')->removeAttr('height');

}


$modx->documentOutput = $doc;
