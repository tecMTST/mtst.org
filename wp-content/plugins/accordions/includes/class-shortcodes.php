<?php



if (!defined('ABSPATH')) exit;  // if direct access


class class_accordions_shortcodes
{


    public function __construct()
    {



        add_shortcode('accordions', array($this, 'accordions_display'));
        add_shortcode('accordions_pickplguins', array($this, 'accordions_display'));
        add_shortcode('accordions_pplugins', array($this, 'accordions_display'));

        add_shortcode('accordions_tabs', array($this, 'accordions_tabs_display'));
        add_shortcode('accordions_tabs_pickplguins', array($this, 'accordions_tabs_display'));
    }


    public function accordions_display($atts, $content = null)
    {

        $atts = shortcode_atts(
            array(
                'id' => "",
            ),
            $atts
        );



        $post_id = isset($atts['id']) ?  $atts['id'] : '';

$post_id =  str_replace(['"',"'"], "", $post_id);


        $accordions_options = get_post_meta($post_id, 'accordions_options', true);
        $view_type = isset($accordions_options['view_type']) ? $accordions_options['view_type'] : 'accordion';

        global $accordionsAttrData;




        $accordions_options = get_post_meta($post_id, 'accordions_options', true);
        $accordions_options = !empty($accordions_options) ? $accordions_options : accordions_old_options($post_id);


        $lazy_load = isset($accordions_options['lazy_load']) ? $accordions_options['lazy_load'] : 'yes';
        $lazy_load_src = isset($accordions_options['lazy_load_src']) ? $accordions_options['lazy_load_src'] : '';



        $accordionsAttrData[$post_id]['lazyLoad'] = ($lazy_load == 'yes') ? true : false;
        $accordionsAttrData[$post_id]['id'] = $post_id;
        //$accordionsAttrData['activeIndex'] = $activeHead;


        ob_start();

        if ($view_type == 'tabs') :
            $tabs = isset($accordions_options['tabs']) ? $accordions_options['tabs'] : array();
            $collapsible = !empty($tabs['collapsible']) ? $tabs['collapsible'] : 'true';
            $active_event = isset($tabs['active_event']) ? $tabs['active_event'] : 'click';
            $active_tab = isset($_GET['id']) ? absint($_GET['id']) : 0;

            $accordionsAttrData[$post_id]['event'] = $active_event;
            $accordionsAttrData[$post_id]['collapsible'] = $collapsible;
            $accordionsAttrData[$post_id]['active'] = $active_tab;
            $accordionsAttrData[$post_id]['vertical'] = 0;


?><div id="accordions-tabs-<?php echo esc_attr($post_id); ?>" class="accordions-tabs-<?php echo esc_attr($post_id); ?> accordions-tabs accordions" accordionstabsdata='<?php echo esc_attr(json_encode($accordionsAttrData[$post_id])); ?>'>
                <?php
                do_action('accordions_tabs_main', $atts);
                ?>
            </div><?php
                else :



                    $accordion = isset($accordions_options['accordion']) ? $accordions_options['accordion'] : array();
                    $collapsible = !empty($accordion['collapsible']) ? $accordion['collapsible'] : 'true';
                    $height_style = isset($accordion['height_style']) ? $accordion['height_style'] : 'content';
                    $active_event = !empty($accordion['active_event']) ? $accordion['active_event'] : 'click';
                    $expanded_other = !empty($accordion['expanded_other']) ? $accordion['expanded_other'] : 'no';
                    $animate_style = !empty($accordion['animate_style']) ? $accordion['animate_style'] : 'swing';
                    $animate_delay = !empty($accordion['animate_delay']) ? $accordion['animate_delay'] : 1000;

                    $accordionsAttrData[$post_id]['event'] = $active_event;
                    $accordionsAttrData[$post_id]['collapsible'] = $collapsible;
                    $accordionsAttrData[$post_id]['heightStyle'] = $height_style;
                    $accordionsAttrData[$post_id]['animateStyle'] = $animate_style;
                    $accordionsAttrData[$post_id]['animateDelay'] = $animate_delay;

                    $accordionsAttrData[$post_id]['navigation'] = true;
                    $accordionsAttrData[$post_id]['active'] = 999;
                    $accordionsAttrData[$post_id]['expandedOther'] = $expanded_other;



                    ?><div id="accordions-<?php echo esc_attr($post_id); ?>" class="accordions-<?php echo esc_attr($post_id); ?> accordions" accordionsdata=<?php echo esc_attr(json_encode($accordionsAttrData[$post_id])); ?>>
                <?php
                    do_action('accordions_main', $atts);
                ?>
            </div><?php
                endif;

                return ob_get_clean();
            }


            public function accordions_tabs_display($atts, $content = null)
            {

                $atts = shortcode_atts(
                    array(
                        'id' => "",
                    ),
                    $atts
                );

                $post_id = $atts['id'];

                ob_start();

                    ?>
        <div id="accordions-tabs-<?php echo esc_attr($post_id); ?>" class="accordions-tabs-<?php echo esc_attr($post_id); ?> accordions-tabs accordions">
            <?php
                do_action('accordions_tabs_main', $atts);
            ?>
        </div>
<?php

                return ob_get_clean();
            }
        }

        new class_accordions_shortcodes();
