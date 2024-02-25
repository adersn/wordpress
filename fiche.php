<?php

class XG_FicheCustomFields
{
    public function __construct()
    {
        //Ajout des champs personnalisés
        add_action( 'add_meta_boxes_xg_fiche', array($this,'meta_box_for_type') );
        //Sauvegarde des champs
        add_action( 'save_post_xg_fiche', array($this,'save_meta_boxes_data'), 10, 2 );

        
        //Ajoute les colonnes à la page admin
        add_filter( 'manage_edit-xg_fiche_columns', array($this,'custom_type_column'), 20 );
        add_action( 'manage_xg_fiche_posts_custom_column' , array($this,'custom_type_list_column_content'), 20, 2 );


    }


    

    public function meta_box_for_type( $post ){
        add_meta_box( 
            'xg_fiche_vehicule', 
            __( 'Caractéristiques de la fiche', 'textdomain' ), 
            array($this,'my_custom_meta_box_html_output'), 
            'xg_fiche', 
            'normal', 
            'high' 
        );
        

        
    }
    
    public function my_custom_meta_box_html_output( $post ) {
        ?>
        Position
        <input style="width:100%" type="text" name="fiche_pos" id="fiche_pos" value="<?php echo esc_attr( get_post_meta($post->ID,sanitize_title('fiche_pos'),true)); ?>" class="regular-text" /><br />
          <?php   
          
          


        $caracs = get_terms( 'xg_fiche_carac', array(
            'parent' => 0,
            'hide_empty' => false,
        ));
        
        foreach($caracs as $c)
        {
            echo "<h3>".$c->name."</h3>";
            $carac = get_terms( 'xg_fiche_carac', array(
                'parent' => $c->term_id,
                'hide_empty' => false,
            ));
            foreach($carac as $f)
            {
                
                $carac_type = get_terms( 'xg_fiche_carac', array(
                    'hide_empty' => false,
                    'parent' => $f->term_id,
                ));
                
                ?>
                <div style="margin-bottom:10px!important">
                    <label ><strong><?=$f->name?></strong></label>
                </div>
                <?php
                if(!empty($carac_type))
                {
                    ?>
                    
                    <select style="width:100%" name="<?= sanitize_title($f->name) ?>" id="<?= sanitize_title($f->name) ?>" class="regular-text" >
                    <?php
                    
                    foreach($carac_type as $t)
                    {
                        $checked = '';
                        if(get_post_meta($post->ID,sanitize_title($f->name),true) == $t->name)
                        {
                            $checked = 'selected';
                        }
                        ?>
                        <option <?= $checked?> value="<?= $t->name?>"><?= $t->name?></option>
                        <?php
                    }
                    ?>
                    </select>
                    <?php

                }
                else{
                    ?>
     
                    <input style="width:100%" type="text" name="<?= sanitize_title($f->name) ?>" id="<?= sanitize_title($f->name) ?>" value="<?php echo esc_attr( get_post_meta($post->ID,sanitize_title($f->name),true)); ?>" class="regular-text" /><br />
                    <?php

                }
                ?>
                    <div style="margin-top:10px;"></div>
                    <?php
                
            } 
        } 
    }
  
  
 
  
    
   
    public function save_meta_boxes_data( $post_id ){

        $caracs = get_terms( 'xg_fiche_carac', array(
            'hide_empty' => false,
            'parent' => 0,
        ));

        foreach($caracs as $c)
        {
            $carac = get_terms( 'xg_fiche_carac', array(
                'hide_empty' => false,
                'parent' => $c->term_id,
            ));
            foreach($carac as $f)
            {
                update_post_meta( $post_id, sanitize_title($f->name), $_POST[sanitize_title($f->name)] );
            }
             
                
        }

        /*
        $arr = ['sd_option_fiche_moteur','sd_option_fiche_frein','sd_option_fiche_trans','sd_option_fiche_cycle','sd_option_fiche_vehicule','sd_option_fiche_perf'];
        foreach($arr as $a)
        {
            $fields = get_option( $a );
            $fields = explode(',', $fields);
            foreach($fields as $f)
            {
                if(isset($_POST[sanitize_title($f)]))
                {
                update_post_meta( $post_id, sanitize_title($f), $_POST[sanitize_title($f)] );
                }
            } 
        }
        */
       
        /* Edit the following lines according to your set fields */
        /*
        update_post_meta( $post_id, 'xg_fiche_facebook', $_POST['xg_fiche_facebook'] );
        update_post_meta( $post_id, 'xg_fiche_twitter', $_POST['xg_fiche_twitter'] );
        update_post_meta( $post_id, 'xg_fiche_instagram', $_POST['xg_fiche_instagram'] );

        update_post_meta( $post_id, 'xg_fiche_member_since', $_POST['xg_fiche_member_since'] );
        */
    }

    
    public function custom_type_column($columns)
    {
        $reordered_columns = array();

        // Inserting columns to a specific location
        foreach( $columns as $key => $column){
            $reordered_columns[$key] = $column;
            if( $key ==  'title' ){
                // Inserting after "Status" column
                $reordered_columns['nb_prices'] = 'Nombre de prix';
                $reordered_columns['updated'] = 'Mise à jour';
            }
        }
        return $reordered_columns;
    }

    public function custom_type_list_column_content( $column, $post_id )
    {
        global $post, $the_order;
        switch ( $column )
        {
            case 'nb_prices' :
                //$arr_metas = get_post_meta($post->ID, "dx_clientnum_prices");
                //echo count($arr_metas[0]);
            break;
            case 'updated' :
                //the_content();
            break;
        }
    }
        

}
    