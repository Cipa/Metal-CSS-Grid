<?php

/*

Grid in Grid - MODX Evolution plugin for http://cipalabs.com/projects/metal/

Description

This plugin will parse all the tables without border class or id and replace them with div columns.
Apropiate clases are created

Important
This plugin only works with tables that have only one row and all the columns are equal in width.


Install

- copy grid-in-grid folder to the plugin folder
- create a new plugin and name it "Grid in Grid"
- add a description: Metal CSS Responsive Grid Plugin
- paste  include($modx->config['base_path'].'assets/plugins/table-grid-in-grid/tgig-evolution.php');
- for System Events check OnWebPagePrerender

*/

$numbersToCol = array('','','sixCol','fourCol','threeCol','extraFive','twoCol','','','','','','twelveCol');


require_once('phpQuery/phpQuery.php');

$e = &$modx->Event;
$html = $modx->documentOutput;


$doc = phpQuery::newDocumentHTML($html);

//add class to parent
pq('table')->parent()->addClass('hasGrid');

$tables = pq('table');

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

$modx->documentOutput = $doc;
