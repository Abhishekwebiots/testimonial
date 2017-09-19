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
        if(is_admin()){
            add_action("admin_head",array($this,"admin_head_app_testimonails"),1);
            add_action("add_meta_boxes",array($this,"metabox_app_testimonials"));
            add_action("save_post",array($this,"metabox_app_testimonials_save"));
        }
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
        printf("<tr><th scope=\"row\"><label for=\"testimonials_twitter\">%s</label></th><td><input class=\"widefat\" placeholder=\"\" type=\"url\" id=\"testimonials_twitter\" name=\"testimonials_twitter\" value=\"%s\" /></td></tr>",__("Twitter", "app-testimonails"), esc_attr($value_testimonials_twitter));
        printf("</table>");
        $this->ionicon_list();
    }
    public function metabox_app_testimonials_save($post_id)
    {
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
    }

    public function ionicon_list(){
        $icons = "alert,alert-circled,android-add,android-add-circle,android-alarm-clock,android-alert,android-apps,android-archive,android-arrow-back,android-arrow-down,android-arrow-dropdown,android-arrow-dropdown-circle,android-arrow-dropleft,android-arrow-dropleft-circle,android-arrow-dropright,android-arrow-dropright-circle,android-arrow-dropup,android-arrow-dropup-circle,android-arrow-forward,android-arrow-up,android-attach,android-bar,android-bicycle,android-boat,android-bookmark,android-bulb,android-bus,android-calendar,android-call,android-camera,android-cancel,android-car,android-cart,android-chat,android-checkbox,android-checkbox-blank,android-checkbox-outline,android-checkbox-outline-blank,android-checkmark-circle,android-clipboard,android-close,android-cloud,android-cloud-circle,android-cloud-done,android-cloud-outline,android-color-palette,android-compass,android-contact,android-contacts,android-contract,android-create,android-delete,android-desktop,android-document,android-done,android-done-all,android-download,android-drafts,android-exit,android-expand,android-favorite,android-favorite-outline,android-film,android-folder,android-folder-open,android-funnel,android-globe,android-hand,android-hangout,android-happy,android-home,android-image,android-laptop,android-list,android-locate,android-lock,android-mail,android-map,android-menu,android-microphone,android-microphone-off,android-more-horizontal,android-more-vertical,android-navigate,android-notifications,android-notifications-none,android-notifications-off,android-open,android-options,android-people,android-person,android-person-add,android-phone-landscape,android-phone-portrait,android-pin,android-plane,android-playstore,android-print,android-radio-button-off,android-radio-button-on,android-refresh,android-remove,android-remove-circle,android-restaurant,android-sad,android-search,android-send,android-settings,android-share,android-share-alt,android-star,android-star-half,android-star-outline,android-stopwatch,android-subway,android-sunny,android-sync,android-textsms,android-time,android-train,android-unlock,android-upload,android-volume-down,android-volume-mute,android-volume-off,android-volume-up,android-walk,android-warning,android-watch,android-wifi,aperture,archive,arrow-down-a,arrow-down-b,arrow-down-c,arrow-expand,arrow-graph-down-left,arrow-graph-down-right,arrow-graph-up-left,arrow-graph-up-right,arrow-left-a,arrow-left-b,arrow-left-c,arrow-move,arrow-resize,arrow-return-left,arrow-return-right,arrow-right-a,arrow-right-b,arrow-right-c,arrow-shrink,arrow-swap,arrow-up-a,arrow-up-b,arrow-up-c,asterisk,at,backspace,backspace-outline,bag,battery-charging,battery-empty,battery-full,battery-half,battery-low,beaker,beer,bluetooth,bonfire,bookmark,bowtie,briefcase,bug,calculator,calendar,camera,card,cash,chatbox,chatbox-working,chatboxes,chatbubble,chatbubble-working,chatbubbles,checkmark,checkmark-circled,checkmark-round,chevron-down,chevron-left,chevron-right,chevron-up,clipboard,clock,close,close-circled,close-round,closed-captioning,cloud,code,code-download,code-working,coffee,compass,compose,connection-bars,contrast,crop,cube,disc,document,document-text,drag,earth,easel,edit,egg,eject,email,email-unread,erlenmeyer-flask,erlenmeyer-flask-bubbles,eye,eye-disabled,female,filing,film-marker,fireball,flag,flame,flash,flash-off,folder,fork,fork-repo,forward,funnel,gear-a,gear-b,grid,hammer,happy,happy-outline,headphone,heart,heart-broken,help,help-buoy,help-circled,home,icecream,image,images,information,information-circled,ionic,ios-alarm,ios-alarm-outline,ios-albums,ios-albums-outline,ios-americanfootball,ios-americanfootball-outline,ios-analytics,ios-analytics-outline,ios-arrow-back,ios-arrow-down,ios-arrow-forward,ios-arrow-left,ios-arrow-right,ios-arrow-thin-down,ios-arrow-thin-left,ios-arrow-thin-right,ios-arrow-thin-up,ios-arrow-up,ios-at,ios-at-outline,ios-barcode,ios-barcode-outline,ios-baseball,ios-baseball-outline,ios-basketball,ios-basketball-outline,ios-bell,ios-bell-outline,ios-body,ios-body-outline,ios-bolt,ios-bolt-outline,ios-book,ios-book-outline,ios-bookmarks,ios-bookmarks-outline,ios-box,ios-box-outline,ios-briefcase,ios-briefcase-outline,ios-browsers,ios-browsers-outline,ios-calculator,ios-calculator-outline,ios-calendar,ios-calendar-outline,ios-camera,ios-camera-outline,ios-cart,ios-cart-outline,ios-chatboxes,ios-chatboxes-outline,ios-chatbubble,ios-chatbubble-outline,ios-checkmark,ios-checkmark-empty,ios-checkmark-outline,ios-circle-filled,ios-circle-outline,ios-clock,ios-clock-outline,ios-close,ios-close-empty,ios-close-outline,ios-cloud,ios-cloud-download,ios-cloud-download-outline,ios-cloud-outline,ios-cloud-upload,ios-cloud-upload-outline,ios-cloudy,ios-cloudy-night,ios-cloudy-night-outline,ios-cloudy-outline,ios-cog,ios-cog-outline,ios-color-filter,ios-color-filter-outline,ios-color-wand,ios-color-wand-outline,ios-compose,ios-compose-outline,ios-contact,ios-contact-outline,ios-copy,ios-copy-outline,ios-crop,ios-crop-strong,ios-download,ios-download-outline,ios-drag,ios-email,ios-email-outline,ios-eye,ios-eye-outline,ios-fastforward,ios-fastforward-outline,ios-filing,ios-filing-outline,ios-film,ios-film-outline,ios-flag,ios-flag-outline,ios-flame,ios-flame-outline,ios-flask,ios-flask-outline,ios-flower,ios-flower-outline,ios-folder,ios-folder-outline,ios-football,ios-football-outline,ios-game-controller-a,ios-game-controller-a-outline,ios-game-controller-b,ios-game-controller-b-outline,ios-gear,ios-gear-outline,ios-glasses,ios-glasses-outline,ios-grid-view,ios-grid-view-outline,ios-heart,ios-heart-outline,ios-help,ios-help-empty,ios-help-outline,ios-home,ios-home-outline,ios-infinite,ios-infinite-outline,ios-information,ios-information-empty,ios-information-outline,ios-ionic-outline,ios-keypad,ios-keypad-outline,ios-lightbulb,ios-lightbulb-outline,ios-list,ios-list-outline,ios-location,ios-location-outline,ios-locked,ios-locked-outline,ios-loop,ios-loop-strong,ios-medical,ios-medical-outline,ios-medkit,ios-medkit-outline,ios-mic,ios-mic-off,ios-mic-outline,ios-minus,ios-minus-empty,ios-minus-outline,ios-monitor,ios-monitor-outline,ios-moon,ios-moon-outline,ios-more,ios-more-outline,ios-musical-note,ios-musical-notes,ios-navigate,ios-navigate-outline,ios-nutrition,ios-nutrition-outline,ios-paper,ios-paper-outline,ios-paperplane,ios-paperplane-outline,ios-partlysunny,ios-partlysunny-outline,ios-pause,ios-pause-outline,ios-paw,ios-paw-outline,ios-people,ios-people-outline,ios-person,ios-person-outline,ios-personadd,ios-personadd-outline,ios-photos,ios-photos-outline,ios-pie,ios-pie-outline,ios-pint,ios-pint-outline,ios-play,ios-play-outline,ios-plus,ios-plus-empty,ios-plus-outline,ios-pricetag,ios-pricetag-outline,ios-pricetags,ios-pricetags-outline,ios-printer,ios-printer-outline,ios-pulse,ios-pulse-strong,ios-rainy,ios-rainy-outline,ios-recording,ios-recording-outline,ios-redo,ios-redo-outline,ios-refresh,ios-refresh-empty,ios-refresh-outline,ios-reload,ios-reverse-camera,ios-reverse-camera-outline,ios-rewind,ios-rewind-outline,ios-rose,ios-rose-outline,ios-search,ios-search-strong,ios-settings,ios-settings-strong,ios-shuffle,ios-shuffle-strong,ios-skipbackward,ios-skipbackward-outline,ios-skipforward,ios-skipforward-outline,ios-snowy,ios-speedometer,ios-speedometer-outline,ios-star,ios-star-half,ios-star-outline,ios-stopwatch,ios-stopwatch-outline,ios-sunny,ios-sunny-outline,ios-telephone,ios-telephone-outline,ios-tennisball,ios-tennisball-outline,ios-thunderstorm,ios-thunderstorm-outline,ios-time,ios-time-outline,ios-timer,ios-timer-outline,ios-toggle,ios-toggle-outline,ios-trash,ios-trash-outline,ios-undo,ios-undo-outline,ios-unlocked,ios-unlocked-outline,ios-upload,ios-upload-outline,ios-videocam,ios-videocam-outline,ios-volume-high,ios-volume-low,ios-wineglass,ios-wineglass-outline,ios-world,ios-world-outline,ipad,iphone,ipod,jet,key,knife,laptop,leaf,levels,lightbulb,link,load-a,load-b,load-c,load-d,location,lock-combination,locked,log-in,log-out,loop,magnet,male,man,map,medkit,merge,mic-a,mic-b,mic-c,minus,minus-circled,minus-round,model-s,monitor,more,mouse,music-note,navicon,navicon-round,navigate,network,no-smoking,nuclear,outlet,paintbrush,paintbucket,paper-airplane,paperclip,pause,person,person-add,person-stalker,pie-graph,pin,pinpoint,pizza,plane,planet,play,playstation,plus,plus-circled,plus-round,podium,pound,power,pricetag,pricetags,printer,pull-request,qr-scanner,quote,radio-waves,record,refresh,reply,reply-all,ribbon-a,ribbon-b,sad,sad-outline,scissors,search,settings,share,shuffle,skip-backward,skip-forward,social-android,social-android-outline,social-angular,social-angular-outline,social-apple,social-apple-outline,social-bitcoin,social-bitcoin-outline,social-buffer,social-buffer-outline,social-chrome,social-chrome-outline,social-codepen,social-codepen-outline,social-css3,social-css3-outline,social-designernews,social-designernews-outline,social-dribbble,social-dribbble-outline,social-dropbox,social-dropbox-outline,social-euro,social-euro-outline,social-facebook,social-facebook-outline,social-foursquare,social-foursquare-outline,social-freebsd-devil,social-github,social-github-outline,social-google,social-google-outline,social-googleplus,social-googleplus-outline,social-hackernews,social-hackernews-outline,social-html5,social-html5-outline,social-instagram,social-instagram-outline,social-javascript,social-javascript-outline,social-linkedin,social-linkedin-outline,social-markdown,social-nodejs,social-octocat,social-pinterest,social-pinterest-outline,social-python,social-reddit,social-reddit-outline,social-rss,social-rss-outline,social-sass,social-skype,social-skype-outline,social-snapchat,social-snapchat-outline,social-tumblr,social-tumblr-outline,social-tux,social-twitch,social-twitch-outline,social-twitter,social-twitter-outline,social-usd,social-usd-outline,social-vimeo,social-vimeo-outline,social-whatsapp,social-whatsapp-outline,social-windows,social-windows-outline,social-wordpress,social-wordpress-outline,social-yahoo,social-yahoo-outline,social-yen,social-yen-outline,social-youtube,social-youtube-outline,soup-can,soup-can-outline,speakerphone,speedometer,spoon,star,stats-bars,steam,stop,thermometer,thumbsdown,thumbsup,toggle,toggle-filled,transgender,trash-a,trash-b,trophy,tshirt,tshirt-outline,umbrella,university,unlocked,upload,usb,videocamera,volume-high,volume-low,volume-medium,volume-mute,wand,waterdrop,wifi,wineglass,woman,wrench,xbox";
        print("<div id=\"ionicons\" style=\"display:none;\">");
        print("<div style=\"width: 100%;height:490px;overflow-x: scroll;\">");
        foreach(explode(",",$icons) as $icon){
            print("<a class=\"app_testimonails_ionicons\" ><i class=\"ion ion-".$icon."\"></i></a>");
        }
        print("</div>");
        print("</div>");
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

//Get the 
    $posts_in = array_map( 'intval', explode( ',', $atts['pids'] ) );

    global $wp_query,$post;

    $atts = shortcode_atts( array(
        'testimonials_name' => ''
    ), $atts );

    $loop = new WP_Query( array(
        'posts_per_page'    => 10,
        'post_type'         => 'app_testimonials',
        'post__in'      => $posts_in
    ) );

    if( ! $loop->have_posts() ) {
        return false;
    }

    echo "<section>
            <div class=\"slider-content\">
                 <div class=\"container\">
                <div class='row'>
                    <div class='col-md-12'>
                        <div class=\"carousel slide\" data-ride=\"carousel\" id=\"quote-carousel\">
                          
                            <!-- Carousel Slides / Quotes -->
                            <div class=\"carousel-inner\">";

    while( $loop->have_posts() ) {
        $loop->the_post();
        ?>
        <div class="item">
            <div class="row">
                <div class="col-md-6">
                    <?php echo get_post_meta($post->ID,"_testimonials_description", true); ?>
                    <br>
                    <div class="line"></div>
                    <div>
                        <ul class="details_client">
                            <li><?php echo get_post_meta($post->ID,"_testimonials_author_name", true); ?></li>
                            <li><?php echo get_post_meta($post->ID, "_testimonials_designation", true); ?></li>
                            <li><?php echo get_post_meta($post->ID, "_testimonials_profile_url", true); ?></li>
                        </ul>
                    </div>
                </div>
                <div class="col-sm-6 text-center">
                    <?php

                    if(get_post_meta($post->ID, "_testimonials_youtube", true)){
                        ?>
                        <iframe width="554" height="376" src="https://www.youtube.com/embed/<?php echo get_post_meta($post->ID, "_testimonials_youtube", true); ?>" frameborder="0" allowfullscreen></iframe>
                    <?php

                    }else{
                        if (has_post_thumbnail( $post->ID ) ) {
                            $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'single-post-thumbnail');
                            ?>
                            <img src="<?php echo $image[0]; ?>">

                            <?php
                        }

                    }
        ?>
                </div>
            </div>

        </div>
        <?php
    }
    echo "</div>  <!-- Carousel Buttons Next/Prev -->
                            <a data-slide=\"prev\" href=\"#quote-carousel\" class=\"left carousel-control\"><i class=\"fa fa-chevron-left arrow-t prev-t\"></i></a>
                            <a data-slide=\"next\" href=\"#quote-carousel\" class=\"right carousel-control\"><i class=\"fa fa-chevron-right arrow-t next-t\"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </section>";

    wp_reset_postdata();
}
/*
 * Registering Scripts and styles
 */

//load scripts
function testmonials_scripts_styles() {
//Register Styles

    wp_register_style( 'bootstrapcss', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css' );
    wp_register_style( 'googlefonts', 'https://fonts.googleapis.com/css?family=Roboto' );
    wp_register_style( 'font-awesome', 'http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css' );
    wp_register_style( 'stylecss',plugins_url( 'assets/css/style.css', dirname(__FILE__) ));

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
