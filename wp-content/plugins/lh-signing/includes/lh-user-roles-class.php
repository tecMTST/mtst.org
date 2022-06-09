<?php


class LH_User_roles_class {




static function add_all_roles() {
 
if (!get_role('unknown')){
        add_role('unknown', 'Unknown User', array('read' => true,));

}

  
if (!get_role('unclaimed')){
        add_role('unclaimed', 'Unclaimed User', array(
            'read' => true, // True allows that capability, False specifically removes it.
        ));

}

}


}


?>