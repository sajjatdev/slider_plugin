
    <div class="wrap">
    
        <h2 >Mobile App Home Slider</h2>
        <br>
        <br>
        <br>
        <form method="post" action="">
            <?php wp_nonce_field('my_plugin_nonce', 'my_plugin_nonce'); ?>
             <label for="category_id">Category ID:</label>
            <input type="text" name="category_id" required>

             <label for="image_url">Image URL:</label>
            <input type="text" name="image_url" required>
            <?php submit_button('Submit'); ?>
        </form>

        <?php
        // Check if the form has been submitted
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
            // Verify nonce for security
            if (isset($_POST['my_plugin_nonce']) && wp_verify_nonce($_POST['my_plugin_nonce'], 'my_plugin_nonce')) {
                // Process form data here
                $category_id = sanitize_text_field($_POST['category_id']);
                $image_url = sanitize_text_field($_POST['image_url']);

               global $wpdb;
               $table_name = $wpdb->prefix . 'slider_plugin_table';

               $wpdb->insert( 
               $table_name, 
                array( 
                    'category_id'=> $category_id,
                    'image_url' => $image_url , 
                    'create_at' => current_time('mysql', 1)      
                ));
           
              echo '<div class="updated"><p>Data saved successfully ! </p></div>';
               } else {

                 echo '<div class="error"><p>Nonce verification failed!</p></div>';
                }

}
               ?>
    </div>



  <table class="wp-list-table widefat fixed striped">
                <!-- Table header -->
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Category ID</th>
                        <th>Image</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <!-- Table body -->
                <tbody>
                    <?php
                    global $wpdb;

                    $table_name = $wpdb->prefix . 'slider_plugin_table';

                      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        if (isset($_POST['delete_record'])) {
                        
                            $record_id = intval($_POST['delete_record']);
                            delete_record_from_custom_table($record_id);

                            echo '<div class="updated"><p>Record deleted!</p></div>';
                        }
                    }     


                    $results = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);

                    foreach ($results as $result) {
                        echo '<tr>';
                        echo '<td>' . esc_html($result['id']) . '</td>';
                        echo '<td>' . esc_html($result['category_id']) . '</td>';
                        echo '<td><img src="' . esc_url($result['image_url']) . '" alt="Image" style="max-width: 100px;"></td>';
                        echo '<td>' . esc_html($result['create_at']) . '</td>';
                        echo '<td><form method="post"><button type="submit" name="delete_record" value="' . esc_attr($result['id']) . '" class="button">Delete</button></form></td>';
                        echo '</tr>';
                    }
              

                  
                    function delete_record_from_custom_table($record_id) {
                        global $wpdb;

                        $table_name = $wpdb->prefix . 'slider_plugin_table';

                        $wpdb->delete($table_name, array('id' => $record_id));
                    }

                    ?>
                </tbody>
            </table>
  