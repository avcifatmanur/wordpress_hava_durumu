<?php
    
    /* Plugin Name: Hava Durumu
    Plugin URI:  https://developer.wordpress.org/plugins/hava-durumu/
    Description: Basic WordPress Weather Plugin 
    Version:     1.0
    Author:      Fatma Nur AVCI
    Author URI:  https://twitter.com/ftmnuravc
    Text Domain: hava-durumu
    License:     GPL2
    License URI: http://www.gnu.org/licenses/gpl-2.0.html
    
    Copyright (C)2017 Fatma Nur AVCI
    
    {Hava Durumu} is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 2 of the License, or
    any later version.

    {Hava Durumu} is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with {Hava Durumu}. If not, see {http://www.gnu.org/licenses/gpl-2.0.html} */

if ( ! defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly
    }
define( 'Weather_Path', plugin_dir_url( __FILE__ ) );
if ( is_admin() ){
    function register_my_set() {
	register_setting( 'my_op_group', 'my_op_name', 'intval' ); 
} 
add_action( 'admin_init', 'register_my_set' );
}

	
    class Hava_Durumu extends WP_Widget{

        // constructor
        function Hava_Durumu() {
             parent::WP_Widget(false, $name = __('SICAKLIK', 'new_plugin') );
        }

        // widget form creation
        function form($instance) {	
           // Check values
            if( $instance) {
                 $title = esc_attr($instance['title']);
                 $apikey = esc_attr($instance['apikey']);
                 $text = esc_attr($instance['text']);
                 $textsize = esc_attr($instance['textsize']);
                 $textcolor = esc_attr($instance['textcolor']);
                 $bckground = esc_attr($instance['bckground']);
                 
            } else {
                 $title = '';
                 $apikey='';
                 $text = '';
                 $textsize='';
                 $textcolor='';
                 $bckground='';
            }

              $url = "http://api.openweathermap.org/data/2.5/weather?q=Istanbul&appid=$apikey";
              $icerik = @file_get_contents($url);
             if (!$icerik) {
                 $durum= "api hatalı girilmiş.";
             }
            ?>
            <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Başlık:', 'new_plugin'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo  $title; ?>" />
            </p>
            <p>
            <label for="<?php echo $this->get_field_id( 'apikey' ); ?>">API KEY:</label>
            <input class="widefat" type="text" id="<?php echo $this->get_field_id( 'apikey' ); ?>" name="<?php echo $this->get_field_name( 'apikey' ); ?>" value="<?php echo esc_attr( $apikey ); ?>">
            </p>
            <p>
            <label for="<?php echo $this->get_field_id('text'); ?>"><?php _e('Durum:', 'new_plugin'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>" type="text" value="<?php echo $durum; ?>" />
            </p>
            <p>
            <label for="<?php echo $this->get_field_id('textsize'); ?>"><?php _e('Yazı Boyutu:', 'new_plugin'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('textsize'); ?>" name="<?php echo $this->get_field_name('textsize'); ?>" type="text" value="<?php echo esc_attr($textsize); ?>" />
            </p>
            <p>
            <label for="<?php echo $this->get_field_id('textcolor'); ?>"><?php _e('Yazı Rengi:', 'new_plugin'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('textcolor'); ?>" name="<?php echo $this->get_field_name('textcolor'); ?>" type="text" value="<?php echo esc_attr($textcolor); ?>" />
            </p>
            <p>
            <label for="<?php echo $this->get_field_id('bckground'); ?>"><?php _e('Arka Plan:', 'new_plugin'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('bckground'); ?>" name="<?php echo $this->get_field_name('bckground'); ?>" type="text" value="<?php echo esc_attr($bckground); ?>" />
            </p>
            
        <?php }

        // widget guncelle
        function update($new_instance, $old_instance) {
            $instance = $old_instance;
            // Fields
            $instance['title'] = ($new_instance['title']);
            $instance['apikey'] = sanitize_text_field($new_instance['apikey']);
            $instance['text'] = sanitize_text_field($new_instance['text']);
            $instance['textsize'] = sanitize_text_field($new_instance['textsize']);
            $instance['textcolor'] = sanitize_text_field($new_instance['textcolor']);
            $instance['bckground'] = sanitize_text_field($new_instance['bckground']);            
            return $instance;
        }

        // widget display
        function widget($args, $instance) {
            extract( $args );
            // these are the widget options
            $title = apply_filters('widget_title', $instance['title']);
            $apikey = $instance['apikey'];
            $text = $instance['text'];
            $textsize = $instance['textsize'];
            $textcolor = $instance['textcolor'];
            $bckground = $instance['bckground'];

            echo $before_widget;
            // Display the widget
            $style="<style>";
            echo '<div>';

               
               if ( $title ) {
                  echo $before_title . $title . $after_title;
               }

               if( $text ) {
                  echo '<p>'.$text.'</p>'; 
               } 
            
               if( $textsize ) {
                 $style .= "#sonuc { font-size:$textsize}";  
               }
               if( $textcolor ) {
                 $style .= "#sonuc { color:$textcolor}";  
               }
               if( $bckground ) {
                 $style .= "#sonuc { background:$bckground}";  
               }
                $style.="</style>";
                echo $style ;
                ?>
                 

                <form id="veri-formu" method="post">
                  <select id="visible-select" name="Sehir">
                        <option value="0">ŞEHİR SEÇİNİZ</option>
                        <option value="Adana">Adana</option>
                        <option value="Adıyaman">Adıyaman</option>
                        <option value="Afyonkarahisar">Afyonkarahisar</option>
                        <option value="Ağrı">Ağrı</option>
                        <option value="Amasya">Amasya</option>
                        <option value="Ankara">Ankara</option>
                        <option value="Antalya">Antalya</option>
                        <option value="Artvin">Artvin</option>
                        <option value="Aydın">Aydın</option>
                        <option value="Balıkesir">Balıkesir</option>
                        <option value="Bilecik">Bilecik</option>
                        <option value="Bingöl">Bingöl</option>
                        <option value="Bitlis">Bitlis</option>
                        <option value="Bolu">Bolu</option>
                        <option value="Burdur">Burdur</option>
                        <option value="Bursa">Bursa</option>
                        <option value="Çanakkale">Çanakkale</option>
                        <option value="Çankırı">Çankırı</option>
                        <option value="Çorum">Çorum</option>
                        <option value="Denizli">Denizli</option>
                        <option value="Diyarbakır">Diyarbakır</option>
                        <option value="Edirne">Edirne</option>
                        <option value="Elazığ">Elazığ</option>
                        <option value="Erzincan">Erzincan</option>
                        <option value="Erzurum">Erzurum</option>
                        <option value="Eskişehir">Eskişehir</option>
                        <option value="Gaziantep">Gaziantep</option>
                        <option value="Giresun">Giresun</option>
                        <option value="Gümüşhane">Gümüşhane</option>
                        <option value="Hakkâri">Hakkâri</option>
                        <option value="Hatay">Hatay</option>
                        <option value="Isparta">Isparta</option>
                        <option value="Mersin">Mersin</option>
                        <option value="İstanbul">İstanbul</option>
                        <option value="İzmir">İzmir</option>
                        <option value="Kars">Kars</option>
                        <option value="Kastamonu">Kastamonu</option>
                        <option value="Kayseri">Kayseri</option>
                        <option value="Kırklareli">Kırklareli</option>
                        <option value="Kırşehir">Kırşehir</option>
                        <option value="Kocaeli">Kocaeli</option>
                        <option value="Konya">Konya</option>
                        <option value="Kütahya">Kütahya</option>
                        <option value="Malatya">Malatya</option>
                        <option value="Manisa">Manisa</option>
                        <option value="Kahramanmaraş">Kahramanmaraş</option>
                        <option value="Mardin">Mardin</option>
                        <option value="Muğla">Muğla</option>
                        <option value="Muş">Muş</option>
                        <option value="Nevşehir">Nevşehir</option>
                        <option value="Niğde">Niğde</option>
                        <option value="Ordu">Ordu</option>
                        <option value="Rize">Rize</option>
                        <option value="Sakarya">Sakarya</option>
                        <option value="Samsun">Samsun</option>
                        <option value="Siirt">Siirt</option>
                        <option value="Sinop">Sinop</option>
                        <option value="Sivas">Sivas</option>
                        <option value="Tekirdağ">Tekirdağ</option>
                        <option value="Tokat">Tokat</option>
                        <option value="Trabzon">Trabzon</option>
                        <option value="Tunceli">Tunceli</option>
                        <option value="Şanlıurfa">Şanlıurfa</option>
                        <option value="Uşak">Uşak</option>
                        <option value="Van">Van</option>
                        <option value="Yozgat">Yozgat</option>
                        <option value="Zonguldak">Zonguldak</option>
                        <option value="Aksaray">Aksaray</option>
                        <option value="Bayburt">Bayburt</option>
                        <option value="Karaman">Karaman</option>
                        <option value="Kırıkkale">Kırıkkale</option>
                        <option value="Batman">Batman</option>
                        <option value="Şırnak">Şırnak</option>
                        <option value="Bartın">Bartın</option>
                        <option value="Ardahan">Ardahan</option>
                        <option value="Iğdır">Iğdır</option>
                        <option value="Yalova">Yalova</option>
                        <option value="Karabük">Karabük</option>
                        <option value="Kilis">Kilis</option>
                        <option value="Osmaniye">Osmaniye</option>
                        <option value="Düzce">Düzce</option>
                    </select>
                    <input style="display: none;" type="text" name="apikey" value="<?php echo $apikey; ?>">
                    <input style="display: none;" type="text" name="islem" value="sonucgetir">
            </form> 
            
            <div id="sonuc"></div>





<script type="text/javascript">
        jQuery(function(){
            jQuery("#sonuc").hide();
            /* jQuery İşlemlerim */
            jQuery("#visible-select").change(function() {

                /* Post Degerleri Alınsın */
                var value =jQuery("#visible-select").val();
                if(!value){
                    alert("Boş Alan Bıraktın");
                }else{

                    jQuery.ajax({
                        type: "POST",
                        url: "<?php echo Weather_Path.'sonuc-getir.php'; ?>",
                        data: jQuery('#veri-formu').serialize(),
                        error:function(){ jQuery('#yazdir').html("Bir hata algılandı."); },
                        success: function(data){ jQuery("#sonuc").html(data); }
                    });

                }
                jQuery("#sonuc").show();
            });
        });

    </script>

<?php
                   echo '</div>';
                   echo $after_widget; 
        }
        }
                 
        add_action('widgets_init', create_function('', 'return register_widget("Hava_Durumu");'));
            

?>
