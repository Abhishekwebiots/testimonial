<?php


class WEBIOTSTestimonails{



    function __construct(){

        // File upload allowed

        $whitelist_files[]      = array("mimetype"=>"image/jpeg","ext"=>"jpg") ;
        $whitelist_files[]      = array("mimetype"=>"image/jpg","ext"=>"jpg") ;
        $whitelist_files[]      = array("mimetype"=>"image/png","ext"=>"png") ;
        $whitelist_files[]      = array("mimetype"=>"text/plain","ext"=>"txt") ;
        $this->whitelist_files = $whitelist_files;
        add_action("plugins_loaded", array($this, "app_textdomain")); //load language/textdomain
        /** register post type **/
        add_action("init", array($this, "post_type_app_testimonials"));

        if(TESTIMONAILS_RESTAPI2 == true){
            /** register rest router **/
            add_action("rest_api_init", array($this,"register_rest_route_app_testimonials"));
        }else{
        }

        /** register metabox for admin **/
       // if(is_admin()){
            add_action("admin_head",array($this,"admin_head_app_testimonails"),1);
            add_action("add_meta_boxes",array($this,"metabox_app_testimonials"));
            add_action("save_post",array($this,"metabox_app_testimonials_save"));
           

       // }
    }


    // Register textdomain
    function app_textdomain(){
        load_plugin_textdomain("app-testimonails", false, TESTIMONAILS_DIR . "/languages");
    }
    /** register post for table test **/
    public function post_type_app_test()
    {
        $labels = array(
            "name" => _x("Tests", "post type general name", "app-testimonails"),
            "singular_name" => _x("Test", "post type singular name", "app-testimonails"),
            "menu_name" => _x("Tests", "admin menu", "app-testimonails"),
            "name_admin_bar" => _x("Tests", "add new on admin bar", "app-testimonails"),
            "add_new" => _x("Add new Tests", "item", "app-testimonails"),
            "add_new_item" => __("Add new Tests", "app-testimonails"),
            "new_item" => __("new item", "app-testimonails"),
            "edit_item" => __("Edit Tests", "app-testimonails"),
            "view_item" => __("View Tests", "app-testimonails"),
            "all_items" => __("All Tests", "app-testimonails"),
            "search_items" => __("Search Tests", "app-testimonails"),
            "parent_item_colon" => __("parent Tests:", "app-testimonails"),
            "not_found" => __("not found", "app-testimonails"),
            "not_found_in_trash" => __("not found in trash", "app-testimonails"));
        $args = array(
            "labels" => $labels,
            "public" => true,
            "menu_icon" => "dashicons-welcome-write-blog",
            "publicly_queryable" => false,
            "show_ui" => true,
            "show_in_menu" => true,
            "query_var" => true,
            "capability_type" => "page",
            "has_archive" => true,
            "hierarchical" => true,
            "menu_position" => null,
            "taxonomies" => array(),
            "supports" => array("title","thumbnail"));
        register_post_type("app_test", $args);
    }

    /** register post for table testimonials **/
    public function post_type_app_testimonials()
    {
        $labels = array(
            "name" => _x("Testimonials", "post type general name", "app-testimonails"),
            "singular_name" => _x("Testimonial", "post type singular name", "app-testimonails"),
            "menu_name" => _x("Testimonials", "admin menu", "app-testimonails"),
            "name_admin_bar" => _x("Testimonials", "add new on admin bar", "app-testimonails"),
            "add_new" => _x("Add new Testimonials", "item", "app-testimonails"),
            "add_new_item" => __("Add new Testimonials", "app-testimonails"),
            "new_item" => __("new item", "app-testimonails"),
            "edit_item" => __("Edit Testimonials", "app-testimonails"),
            "view_item" => __("View Testimonials", "app-testimonails"),
            "all_items" => __("All Testimonials", "app-testimonails"),
            "search_items" => __("Search Testimonials", "app-testimonails"),
            "parent_item_colon" => __("parent Testimonials:", "app-testimonails"),
            "not_found" => __("not found", "app-testimonails"),
            "not_found_in_trash" => __("not found in trash", "app-testimonails"));
        $args = array(
            "labels" => $labels,
            "public" => true,
            "menu_icon" => "dashicons-tickets",
            "publicly_queryable" => false,
            "show_ui" => true,
            "show_in_menu" => true,
            "query_var" => true,
            "capability_type" => "page",
            "has_archive" => true,
            "hierarchical" => true,
            "menu_position" => null,
            "taxonomies" => array(),
            "supports" => array("title","thumbnail"));
        register_post_type("app_testimonials", $args);
    }

    /** register metabox for testimonials **/
    public function metabox_app_testimonials($hook)
    {
        $allowed_hook = array("app_testimonials");
        if(in_array($hook, $allowed_hook))
        {
            add_meta_box("metabox_app_testimonials",
                __("Testimonials - The REST API","app-testimonails"),
                array($this,"metabox_app_testimonials_callback"),
                $hook,
                "normal",
                "high");
             
                     }


    }
    /** callback metabox for testimonials **/
    public function metabox_app_testimonials_callback($post)
    {
       $this->testimonails_enqueue();
        wp_enqueue_style("thickbox");
        wp_nonce_field("metabox_app_testimonials_save","metabox_app_testimonials_nonce");
        printf("<table class=\"form-table\">");
        $value_testimonials_name = get_post_meta($post->ID, "_testimonials_name", true);
        printf("<tr><th scope=\"row\"><label for=\"testimonials_name\">%s</label></th><td><input class=\"widefat\" type=\"text\" id=\"testimonials_name\" name=\"testimonials_name\" value=\"%s\" /></td></tr>",__("Name", "app-testimonails"), esc_attr($value_testimonials_name));
        $settings = array("media_buttons"=>true);
        $value_testimonials_description = get_post_meta($post->ID, "_testimonials_description", true);
        printf("<tr><th scope=\"row\"><label for=\"testimonials_description\">%s</label></th><td>",__("Description","app-testimonails"));
        wp_editor(html_entity_decode($value_testimonials_description),"testimonials_description",$settings);
        printf("</td></tr>");
        $value_testimonials_author_name = get_post_meta($post->ID, "_testimonials_author_name", true);
        printf("<tr><th scope=\"row\"><label for=\"testimonials_author_name\">%s</label></th><td><input class=\"widefat\" type=\"text\" id=\"testimonials_author_name\" name=\"testimonials_author_name\" value=\"%s\" /></td></tr>",__("Author Name", "app-testimonails"), esc_attr($value_testimonials_author_name));
        $value_testimonials_designation = get_post_meta($post->ID, "_testimonials_designation", true);
        printf("<tr><th scope=\"row\"><label for=\"testimonials_designation\">%s</label></th><td><input class=\"widefat\" type=\"text\" id=\"testimonials_designation\" name=\"testimonials_designation\" value=\"%s\" /></td></tr>",__("Designation", "app-testimonails"), esc_attr($value_testimonials_designation));
        $value_testimonials_profile_url = get_post_meta($post->ID, "_testimonials_profile_url", true);
        printf("<tr><th scope=\"row\"><label for=\"testimonials_profile_url\">%s</label></th><td><input class=\"widefat\" placeholder=\"\" type=\"url\" id=\"testimonials_profile_url\" name=\"testimonials_profile_url\" value=\"%s\" /></td></tr>",__("Profile Url", "app-testimonails"), esc_attr($value_testimonials_profile_url));
        $value_testimonials_youtube = get_post_meta($post->ID, "_testimonials_youtube", true);
        printf("<tr><th scope=\"row\"><label for=\"testimonials_youtube\">%s</label></th><td><input class=\"widefat\" type=\"text\" id=\"testimonials_youtube\" name=\"testimonials_youtube\" value=\"%s\" placeholder=\"4HkG8z3sa-0\" /><p class=\"description\">Use Youtube ID example: 4HkG8z3sa-0 get from link: https://www.youtube.com/watch?v=<kbd>4HkG8z3sa-0</kbd></p></td></tr>",__("Youtube", "app-testimonails"), esc_attr($value_testimonials_youtube));
        $value_testimonials_fb_url = get_post_meta($post->ID, "_testimonials_fb_url", true);
        printf("<tr><th scope=\"row\"><label for=\"testimonials_fb_url\">%s</label></th><td><input class=\"widefat\" placeholder=\"\" type=\"url\" id=\"testimonials_fb_url\" name=\"testimonials_fb_url\" value=\"%s\" /></td></tr>",__("Facebook", "app-testimonails"), esc_attr($value_testimonials_fb_url));
        $value_testimonials_linkedin_url = get_post_meta($post->ID, "_testimonials_linkedin_url", true);
        printf("<tr><th scope=\"row\"><label for=\"testimonials_linkedin_url\">%s</label></th><td><input class=\"widefat\" placeholder=\"\" type=\"url\" id=\"testimonials_linkedin_url\" name=\"testimonials_linkedin_url\" value=\"%s\" /></td></tr>",__("Linkedin", "app-testimonails"), esc_attr($value_testimonials_linkedin_url));
        $value_testimonials_twitter = get_post_meta($post->ID, "_testimonials_twitter", true);
        printf("<tr><th scope=\"row\"><label for=\"testimonials_twitter\">%s</label></th><td ><input class=\"widefat\" placeholder=\"\" type=\"url\" id=\"testimonials_twitter\" name=\"testimonials_twitter\" value=\"%s\" /></td></tr>",__("Twitter", "app-testimonails"), esc_attr($value_testimonials_twitter));
       // $value_testimonials_rate = get_post_meta($post->ID, "_testimonials_rate", true);
       // printf("<tr><th scope=\"row\"><label for=\"testimonials_rate\">%s</label></th><td><input class=\"widefat\" placeholder=\"\" type=\"text\" id=\"testimonials_rate\" name=\"testimonials_rate\" value=\"%s\" /></td></tr>",__("Rating", "app-testimonails"), esc_attr($value_testimonials_rate));
        
 $value_testimonials_rate = get_post_meta($post->ID, "_testimonials_rate", true);
 // var_dump($value_testimonials_rate);
     printf("<tr><th scope=\"row\"><label for=\"testimonials_rate\">%s</label></th>",__("Rating", "app-testimonails"), esc_attr($value_testimonials_rate));
     printf(" <td style=\"float: left;\">");
if($value_testimonials_rate == "5") {
printf("<input class=\"star star-5\" placeholder=\"\" type=\"radio\" id=\"star5\" checked name=\"testimonials_rate\"  value=\"5\" />
 <label class=\"star star-5\" for=\"star5\"></label>");
}
else{
    printf("<input class=\"star star-5\" placeholder=\"\" type=\"radio\" id=\"star5\"  name=\"testimonials_rate\"  value=\"5\" />
 <label class=\"star star-5\" for=\"star5\"></label>");
}

if($value_testimonials_rate == "4") {
 printf("<input class=\"star star-4\" checked placeholder=\"\" type=\"radio\" id=\"star4\" name=\"testimonials_rate\" value=\"4\" />
 <label class=\"star star-4\" for=\"star4\"></label>");
}
else{
 printf("<input class=\"star star-4\" placeholder=\"\" type=\"radio\" id=\"star4\" name=\"testimonials_rate\" value=\"4\" />
 <label class=\"star star-4\" for=\"star4\"></label>");
}


if($value_testimonials_rate == "3") {
printf("<input class=\"star star-3\" checked placeholder=\"\" type=\"radio\" id=\"star3\" name=\"testimonials_rate\" value=\"3\" />
 <label class=\"star star-3\" for=\"star3\"></label>");
} 
else {
printf("<input class=\"star star-3\" placeholder=\"\" type=\"radio\" id=\"star3\" name=\"testimonials_rate\" value=\"3\" />
 <label class=\"star star-3\" for=\"star3\"></label>");
}

if($value_testimonials_rate == "2") {
 printf("<input class=\"star star-2\" checked placeholder=\"\" type=\"radio\" id=\"star2\" name=\"testimonials_rate\" value=\"2\" />
 <label class=\"star star-2\" for=\"star2\"></label>");
 }
 else {
printf("<input class=\"star star-2\" placeholder=\"\" type=\"radio\" id=\"star2\" name=\"testimonials_rate\" value=\"2\" />
 <label class=\"star star-2\" for=\"star2\"></label>");
 }

if($value_testimonials_rate == "1") {
 printf("<input class=\"star star-1\" checked placeholder=\"\" type=\"radio\"  id=\"star1\" name=\"testimonials_rate\" value=\"1\" />
 <label class=\"star star-1\" for=\"star1\"></label>");
     }
     else {
 printf("<input class=\"star star-1\" placeholder=\"\" type=\"radio\"  id=\"star1\" name=\"testimonials_rate\" value=\"1\" />
 <label class=\"star star-1\" for=\"star1\"></label>");
     }

        printf("</td>
        </tr>",__("Rating", "app-testimonails"), esc_attr($value_testimonials_rate));
        

        printf("</table>");

    }

    public function metabox_app_testimonials_save($post_id)
    {
      //  var_dump($_REQUEST);
        // Check if our nonce is set.
        if (!isset($_POST["metabox_app_testimonials_nonce"]))
            return $post_id;
        $nonce = $_POST["metabox_app_testimonials_nonce"];
        // Verify that the nonce is valid.
        if(!wp_verify_nonce($nonce, "metabox_app_testimonials_save"))
            return $post_id;
        // If this is an autosave, our form has not been submitted,
        // so we don't want to do anything.
        if (defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
            return $post_id;
        // Check the user's permissions.
        if ("page" == $_POST["post_type"])
        {
            if (!current_user_can("edit_page", $post_id))
                return $post_id;
        } else
        {
            if (!current_user_can("edit_post", $post_id))
                return $post_id;
        }
        // Sanitize the user input.
        $post_testimonials_name = sanitize_text_field($_POST["testimonials_name"] );
        // Update the meta field.
        update_post_meta($post_id, "_testimonials_name", $post_testimonials_name);
        // Sanitize the user input.
        $post_testimonials_description = esc_html($_POST["testimonials_description"] );
        // Update the meta field.
        update_post_meta($post_id, "_testimonials_description", $post_testimonials_description);
        // Sanitize the user input.
        $post_testimonials_author_name = sanitize_text_field($_POST["testimonials_author_name"] );
        // Update the meta field.
        update_post_meta($post_id, "_testimonials_author_name", $post_testimonials_author_name);
        // Sanitize the user input.
        $post_testimonials_designation = sanitize_text_field($_POST["testimonials_designation"] );
        // Update the meta field.
        update_post_meta($post_id, "_testimonials_designation", $post_testimonials_designation);
        // Sanitize the user input.
        $post_testimonials_profile_url = sanitize_text_field($_POST["testimonials_profile_url"] );
        // Update the meta field.
        update_post_meta($post_id, "_testimonials_profile_url", $post_testimonials_profile_url);
        // Sanitize the user input.
        $post_testimonials_youtube = sanitize_text_field($_POST["testimonials_youtube"] );
        // Update the meta field.
        update_post_meta($post_id, "_testimonials_youtube", $post_testimonials_youtube);
        // Sanitize the user input.
        $post_testimonials_fb_url = sanitize_text_field($_POST["testimonials_fb_url"] );
        // Update the meta field.
        update_post_meta($post_id, "_testimonials_fb_url", $post_testimonials_fb_url);
        // Sanitize the user input.
        $post_testimonials_linkedin_url = sanitize_text_field($_POST["testimonials_linkedin_url"] );
        // Update the meta field.
        update_post_meta($post_id, "_testimonials_linkedin_url", $post_testimonials_linkedin_url);
        // Sanitize the user input.
        $post_testimonials_twitter = sanitize_text_field($_POST["testimonials_twitter"] );
        // Update the meta field.
        update_post_meta($post_id, "_testimonials_twitter", $post_testimonials_twitter);

         $post_testimonials_rate = sanitize_text_field($_POST["testimonials_rate"] );
        // Update the meta field.
        update_post_meta($post_id, "_testimonials_rate", $post_testimonials_rate);
    }


    // TODO: register routes app_testimonials
    function register_rest_route_app_testimonials(){
        register_rest_route("testimonails/v2","app_testimonials",array(
            "methods" => "GET",
            "callback" =>array($this, "app_testimonials_callback"),
            "permission_callback" => function (WP_REST_Request $request){return true;}
        ));
    }


    // TODO: callback routes app_testimonials
    function app_testimonials_callback($request){
        if(TESTIMONAILS_RESTAPI2 == true){
            $parameters = $request->get_query_params();
        }else{
            $parameters = $request;
        }
        if(isset($parameters["numberposts"])){
            $numberposts = (int) $parameters["numberposts"];
        }else{
            $numberposts =-1;
        }
        $metakey=$metavalue=null;
        if(isset($parameters["name"])){
            if($parameters["name"]=="-1"){$parameters["name"]="";}
            $metakey = "_testimonials_name";
            $metavalue = esc_sql($parameters["name"]);
        }
        if(isset($parameters["description"])){
            if($parameters["description"]=="-1"){$parameters["description"]="";}
            $metakey = "_testimonials_description";
            $metavalue = esc_sql($parameters["description"]);
        }
        if(isset($parameters["author_name"])){
            if($parameters["author_name"]=="-1"){$parameters["author_name"]="";}
            $metakey = "_testimonials_author_name";
            $metavalue = esc_sql($parameters["author_name"]);
        }
        if(isset($parameters["designation"])){
            if($parameters["designation"]=="-1"){$parameters["designation"]="";}
            $metakey = "_testimonials_designation";
            $metavalue = esc_sql($parameters["designation"]);
        }
        if(isset($parameters["profile_url"])){
            if($parameters["profile_url"]=="-1"){$parameters["profile_url"]="";}
            $metakey = "_testimonials_profile_url";
            $metavalue = esc_sql($parameters["profile_url"]);
        }
        if(isset($parameters["youtube"])){
            if($parameters["youtube"]=="-1"){$parameters["youtube"]="";}
            $metakey = "_testimonials_youtube";
            $metavalue = esc_sql($parameters["youtube"]);
        }
        if(isset($parameters["fb_url"])){
            if($parameters["fb_url"]=="-1"){$parameters["fb_url"]="";}
            $metakey = "_testimonials_fb_url";
            $metavalue = esc_sql($parameters["fb_url"]);
        }
        if(isset($parameters["linkedin_url"])){
            if($parameters["linkedin_url"]=="-1"){$parameters["linkedin_url"]="";}
            $metakey = "_testimonials_linkedin_url";
            $metavalue = esc_sql($parameters["linkedin_url"]);
        }
        if(isset($parameters["twitter"])){
            if($parameters["twitter"]=="-1"){$parameters["twitter"]="";}
            $metakey = "_testimonials_twitter";
            $metavalue = esc_sql($parameters["twitter"]);
        }
        if(isset($parameters["rate"])){
            if($parameters["rate"]=="-1"){$parameters["rate"]="";}
            $metakey = "_testimonials_rate";
            $metavalue = esc_sql($parameters["rate"]);
        }
        $posts = get_posts(array("post_type"=> "app_testimonials","post_status"=>"publish","numberposts"=> $numberposts,"meta_key"=>$metakey,"meta_value"=>$metavalue));
        foreach($posts as $post){
            $metadata[$post->ID]["id"] = $post->ID;
            $metadata[$post->ID]["name"] = get_post_meta($post->ID,"_testimonials_name",true);
            $metadata[$post->ID]["description"] = html_entity_decode(get_post_meta($post->ID,"_testimonials_description",true));
            $metadata[$post->ID]["author_name"] = get_post_meta($post->ID,"_testimonials_author_name",true);
            $metadata[$post->ID]["designation"] = get_post_meta($post->ID,"_testimonials_designation",true);
            $metadata[$post->ID]["profile_url"] = get_post_meta($post->ID,"_testimonials_profile_url",true);
            $metadata[$post->ID]["youtube"] = get_post_meta($post->ID,"_testimonials_youtube",true);
            $metadata[$post->ID]["fb_url"] = get_post_meta($post->ID,"_testimonials_fb_url",true);
            $metadata[$post->ID]["linkedin_url"] = get_post_meta($post->ID,"_testimonials_linkedin_url",true);
            $metadata[$post->ID]["twitter"] = get_post_meta($post->ID,"_testimonials_twitter",true);
            $metadata[$post->ID]["rate"] = get_post_meta($post->ID,"_testimonials_rate",true);
        }
        if(!is_array($metadata)){$metadata = array();}
        $return = array_values($metadata);
        if(isset($_GET["id"])){
            $return = $return[0];
        }
        if (empty($metadata)){return array();}
        return $return;
    }
    /** JSON testimonials **/
    function ajax_app_testimonials(){
        $request = $_GET;
        $rest_api = $this->app_testimonials_callback($request);
        header("Content-type: application/json");
        header("Access-Control-Allow-Origin: *");
        if(defined("JSON_UNESCAPED_UNICODE")){
            die(json_encode($rest_api,JSON_UNESCAPED_UNICODE));
        }else{
            die(json_encode($rest_api));
        }
    }

    /** register css/js testimonails **/
    public function testimonails_enqueue()
    {
        wp_enqueue_media();
        wp_register_style("ionicon", "//code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css",array(),"1.2.4" );
        wp_enqueue_style("ionicon");
        wp_enqueue_script("app_testimonails", plugins_url("/",__FILE__) . "/js/admin.js", array("jquery","thickbox"),"1",true );
    wp_register_style( 'stylecss',plugins_url( 'assets/css/style.css', dirname(__FILE__) ));
         wp_enqueue_style("stylecss");
         wp_register_style("font-awesome", "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css",array(),"1.2.4" );
        wp_enqueue_style("font-awesome");

        
    }




    function admin_head_app_testimonails($hooks){
        echo "<style type=\"text/css\">";
        echo ".app_testimonails_ionicons .ion{cursor:pointer;text-align:center;border:1px solid #eee;font-size:32px;width:32px;height:32px;padding:6px;}";
        echo "</style>";
    }


}


new WEBIOTSTestimonails();




/*
 *  Get All the testimonials 
 */
function shortcode_webiots_testimonials( $atts ) {


    var_dump($atts);
    $slide = $atts['slide'];
    global $wp_query,$post;

    $atts = shortcode_atts( array(
        'testimonials_name' => ''
    ), $atts );

    $loop = new WP_Query( array(
        'posts_per_page'    => 10,
        'post_type'         => 'app_testimonials',

    ) );

    if( ! $loop->have_posts() ) {
        return false;
    }
if($slide=="slider1"){
    include_once(TESTIMONAILS_PATH.'/templates/slider/slide1.php');
}else if($slide=="slider2"){
    include_once(TESTIMONAILS_PATH.'/templates/slider/slide2.php');
}else{
    include_once(TESTIMONAILS_PATH.'/templates/slider/slide1.php');
}


    wp_reset_postdata();
}
/*
 * Registering Scripts and styles
 */



function shortcode_webiots_testimonials_form() {


        wp_enqueue_style("thickbox");
       
        printf("<table class=\"form-table\">");
          printf(" <form id=\"new_post\" name=\"new_post\" method=\"post\" >");
        printf("<tr><th scope=\"row\"><label for=\"testimonials_name\">%s</label></th><td><input class=\"widefat\" type=\"text\" id=\"testimonials_name\" name=\"testimonials_name\" value=\"%s\" /></td></tr>",__("Name", "app-testimonails"), esc_attr($value_testimonials_name));
        
        printf("<tr><th scope=\"row\"><label for=\"testimonials_image\">%s</label></th><td><input class=\"widefat\" type=\"file\" id=\"testimonials_image\" name=\"testimonials_image\" value=\"%s\" /></td></tr>",__("Upload Photo", "app-testimonails"), esc_attr($value_testimonials_image));

        printf("<tr><th scope=\"row\"><label for=\"testimonials_description\">%s</label></th><td>",__("Description","app-testimonails"));
wp_editor(html_entity_decode($value_testimonials_description),"testimonials_description",$settings);       
        printf("</td></tr>");
       
        printf("<tr><th scope=\"row\"><label for=\"testimonials_author_name\">%s</label></th><td><input class=\"widefat\" type=\"text\" id=\"testimonials_author_name\" name=\"testimonials_author_name\" value=\"%s\" /></td></tr>",__("Author Name", "app-testimonails"), esc_attr($value_testimonials_author_name));
       
        printf("<tr><th scope=\"row\"><label for=\"testimonials_designation\">%s</label></th><td><input class=\"widefat\" type=\"text\" id=\"testimonials_designation\" name=\"testimonials_designation\" value=\"%s\" /></td></tr>",__("Designation", "app-testimonails"), esc_attr($value_testimonials_designation));
       
        printf("<tr><th scope=\"row\"><label for=\"testimonials_profile_url\">%s</label></th><td><input class=\"widefat\" placeholder=\"\" type=\"url\" id=\"testimonials_profile_url\" name=\"testimonials_profile_url\" value=\"%s\" /></td></tr>",__("Profile Url", "app-testimonails"), esc_attr($value_testimonials_profile_url));
       
        printf("<tr><th scope=\"row\"><label for=\"testimonials_youtube\">%s</label></th><td><input class=\"widefat\" type=\"text\" id=\"testimonials_youtube\" name=\"testimonials_youtube\" value=\"%s\" placeholder=\"4HkG8z3sa-0\" /><p class=\"description\">Use Youtube ID example: 4HkG8z3sa-0 get from link: https://www.youtube.com/watch?v=<kbd>4HkG8z3sa-0</kbd></p></td></tr>",__("Youtube", "app-testimonails"), esc_attr($value_testimonials_youtube));
       
        printf("<tr><th scope=\"row\"><label for=\"testimonials_fb_url\">%s</label></th><td><input class=\"widefat\" placeholder=\"\" type=\"url\" id=\"testimonials_fb_url\" name=\"testimonials_fb_url\" value=\"%s\" /></td></tr>",__("Facebook", "app-testimonails"), esc_attr($value_testimonials_fb_url));
       
        printf("<tr><th scope=\"row\"><label for=\"testimonials_linkedin_url\">%s</label></th><td><input class=\"widefat\" placeholder=\"\" type=\"url\" id=\"testimonials_linkedin_url\" name=\"testimonials_linkedin_url\" value=\"%s\" /></td></tr>",__("Linkedin", "app-testimonails"), esc_attr($value_testimonials_linkedin_url));
       
        printf("<tr><th scope=\"row\"><label for=\"testimonials_twitter\">%s</label></th><td ><input class=\"widefat\" placeholder=\"\" type=\"url\" id=\"testimonials_twitter\" name=\"testimonials_twitter\" value=\"%s\" /></td></tr>",__("Twitter", "app-testimonails"), esc_attr($value_testimonials_twitter));
        

     printf("<tr><th scope=\"row\"><label for=\"testimonials_rate\">%s</label></th>",__("Rating", "app-testimonails"), esc_attr($value_testimonials_rate));
     printf(" <td style=\"float: left;\">");


    printf("<input class=\"star star-5\" placeholder=\"\" type=\"radio\" id=\"star5\"  name=\"testimonials_rate\"  value=\"5\" />
      <label class=\"star star-5\" for=\"star5\"></label>");

 printf("<input class=\"star star-4\" placeholder=\"\" type=\"radio\" id=\"star4\" name=\"testimonials_rate\" value=\"4\" />
      <label class=\"star star-4\" for=\"star4\"></label>");


printf("<input class=\"star star-3\" placeholder=\"\" type=\"radio\" id=\"star3\" name=\"testimonials_rate\" value=\"3\" />
     <label class=\"star star-3\" for=\"star3\"></label>");
 
printf("<input class=\"star star-2\" placeholder=\"\" type=\"radio\" id=\"star2\" name=\"testimonials_rate\" value=\"2\" />
     <label class=\"star star-2\" for=\"star2\"></label>");
 
 printf("<input class=\"star star-1\" placeholder=\"\" type=\"radio\"  id=\"star1\" name=\"testimonials_rate\" value=\"1\" />
 <label class=\"star star-1\" for=\"star1\"></label>");


        printf("</td>
        </tr>",__("Rating", "app-testimonails"), esc_attr($value_testimonials_rate));
        
       
         printf("<tr><th scope=\"row\"></th><td ><input class=\"widefat\" type=\"submit\" id=\"submit_testimonials\" name=\"submit_testimonials\" value=\"Submit\" /></td></tr>");
  printf("</form>");
        printf("</table>");
     

}



 






//load scripts
function testmonials_scripts_styles() {
//Register Styles

    wp_register_style( 'compiled', plugins_url( 'assets/css/testimonials.css', dirname(__FILE__) ) );
    wp_register_style( 'bootstrapcss', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css' );
    wp_register_style( 'googlefonts', 'https://fonts.googleapis.com/css?family=Roboto' );
    wp_register_style( 'font-awesome', 'http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css' );
    wp_register_style( 'stylecss',plugins_url( 'assets/css/style.css', dirname(__FILE__) ));

    wp_enqueue_style( 'compiled' );
    wp_enqueue_style( 'bootstrapcss' );
    wp_enqueue_style( 'googlefonts' );
    wp_enqueue_style( 'font-awesome' );
    wp_enqueue_style( 'stylecss' );
//Register Scripts
    //  wp_register_script( 'html5js', 'https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js' );
    // wp_register_script( 'respondjs', 'https://oss.maxcdn.com/respond/1.4.2/respond.min.js' );
    wp_register_script( 'jqueryjs', 'https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js',array(),'1.11.3',true);
    wp_register_script( 'bootstrapjs', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js',array(),'3.3.6',true);
    wp_register_script( 'functionjs', plugins_url( 'assets/js/function.js', dirname(__FILE__) ),array(),'1.0',true);


   // wp_enqueue_script( 'html5js' );
    //wp_enqueue_script( 'respondjs' );
    wp_enqueue_script('jqueryjs');
    wp_enqueue_script( 'bootstrapjs' );
    wp_enqueue_script( 'functionjs' );


}
/*
* Funtion to Insert Testimonials From Frontend
*/
include_once(ABSPATH . 'wp-includes/pluggable.php');

 if (isset( $_POST['submit_testimonials'] ) )

  

    {
    //   var_dump($_REQUEST);
      // exit;


 $testimonial_args = array(

'post_title'    => $_POST['testimonials_name'],

'post_content'  => $_POST['testimonials_description'],

'post_status'   => 'pending',

'post_type' => 'app_testimonials'

);

 // insert the post into the database

 $postid = wp_insert_post( $testimonial_args, $wp_error);


      // var_dump($postid);
        // Sanitize the user input.
        $post_testimonials_name = sanitize_text_field($_POST["testimonials_name"] );
        // Update the meta field.
        update_post_meta($postid, "_testimonials_name", $post_testimonials_name);
        // Sanitize the user input.
        $post_testimonials_description = esc_html($_POST["testimonials_description"] );
        // Update the meta field.
        update_post_meta($postid, "_testimonials_description", $post_testimonials_description);
        // Sanitize the user input.
        $post_testimonials_author_name = sanitize_text_field($_POST["testimonials_author_name"] );
        // Update the meta field.
        update_post_meta($postid, "_testimonials_author_name", $post_testimonials_author_name);
        // Sanitize the user input.
        $post_testimonials_designation = sanitize_text_field($_POST["testimonials_designation"] );
        // Update the meta field.
        update_post_meta($postid, "_testimonials_designation", $post_testimonials_designation);
        // Sanitize the user input.
        $post_testimonials_profile_url = sanitize_text_field($_POST["testimonials_profile_url"] );
        // Update the meta field.
        update_post_meta($postid, "_testimonials_profile_url", $post_testimonials_profile_url);
        // Sanitize the user input.
        $post_testimonials_youtube = sanitize_text_field($_POST["testimonials_youtube"] );
        // Update the meta field.
        update_post_meta($postid, "_testimonials_youtube", $post_testimonials_youtube);
        // Sanitize the user input.
        $post_testimonials_fb_url = sanitize_text_field($_POST["testimonials_fb_url"] );
        // Update the meta field.
        update_post_meta($postid, "_testimonials_fb_url", $post_testimonials_fb_url);
        // Sanitize the user input.
        $post_testimonials_linkedin_url = sanitize_text_field($_POST["testimonials_linkedin_url"] );
        // Update the meta field.
        update_post_meta($postid, "_testimonials_linkedin_url", $post_testimonials_linkedin_url);
        // Sanitize the user input.
        $post_testimonials_twitter = sanitize_text_field($_POST["testimonials_twitter"] );
        // Update the meta field.
        update_post_meta($postid, "_testimonials_twitter", $post_testimonials_twitter);

         $post_testimonials_rate = sanitize_text_field($_POST["testimonials_rate"] );
        // Update the meta field.
        update_post_meta($postid, "_testimonials_rate", $post_testimonials_rate);

        

    }
