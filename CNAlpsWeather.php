<?php
/*
Plugin Name: CNAlpsWeather
Description: Displays some weather data
Version: 1.0
Author: me
*/

class CNAlpsWeather extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'cn_alps_weather',
            'CNAlps Weather Widget',
            array(
                'customize_selective_refresh' => true,
            )
        );
    }

    public function form($instance): void
    {
        $city = isset($instance['city']) ? esc_attr($instance['city']) : 'Meylan';
        $country = isset($instance['country']) ? esc_attr($instance['country']) : 'France';
        $language = isset($instance['language']) ? esc_attr($instance['language']) : 'french';
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('city'); ?>">Ville:</label>
            <input class="widefat" id="<?php echo $this->get_field_id('city'); ?>" name="<?php echo $this->get_field_name('city'); ?>" type="text" value="<?php echo $city; ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('country'); ?>">Pays:</label>
            <input class="widefat" id="<?php echo $this->get_field_id('country'); ?>" name="<?php echo $this->get_field_name('country'); ?>" type="text" value="<?php echo $country; ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('language'); ?>">Langue:</label>
            <input class="widefat" id="<?php echo $this->get_field_id('language'); ?>" name="<?php echo $this->get_field_name('language'); ?>" type="text" value="<?php echo $language; ?>">
        </p>
        <?php
    }
    public function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['city'] = sanitize_text_field($new_instance['city']);
        $instance['country'] = sanitize_text_field($new_instance['country']);
        $instance['language'] = sanitize_text_field($new_instance['language']);
        return $instance;
    }

    public function widget($args, $instance): void
    {
        echo $args['before_widget'];

        $city = $instance['city'] ?? 'Meylan';
        $country = $instance['country'] ?? 'France';
        $language = $instance['language'] ?? 'french';
        $weatherId = "weather-info";
        ?>
        <div id="<?= $weatherId ?>"></div>

        <script>
            document.addEventListener("DOMContentLoaded", function () {
                let city = "<?= $city; ?>";
                let country = "<?= $country; ?>";
                let language = "<?= $language; ?>";
                let apiUrl = "https://www.weatherwp.com/api/common/publicWeatherForLocation.php?city=" + city + "&country=" + country + "&language=" + language;

                fetch(apiUrl)
                    .then(response => response.json())
                    .then(data => {
                        let weatherInfoDiv = document.getElementById(<?= $weatherId ?>);
                        let temperature = data.temp;
                        let iconUrl = data.icon;
                        let description = data.description;

                        let content = city + " - " + temperature + " °C - " + description + "<br><img src='" + iconUrl + "' alt='Weather Icon'>";
                        weatherInfoDiv.innerHTML = content;
                    })
                    .catch(error => {
                        console.error("Error fetching weather data:", error);
                    });
            });
        </script>

        <?php

        echo $args['after_widget'];
    }

}

function register_weather_widget(): void
{
    register_widget('CNAlpsWeather');
}
add_action('widgets_init', 'register_weather_widget');
?>