<?php

function init_theme_options(){
  register_setting('options_of_theme','phone');
  register_setting('options_of_theme','company_name');
  register_setting('options_of_theme','company_address');
  register_setting('options_of_theme','email');
  register_setting('options_of_theme','nip');
  register_setting('options_of_theme','owner');
  register_setting('options_of_theme','other_www');
  register_setting('options_of_theme','yt_link');
  register_setting('options_of_theme','gplus_link');
  register_setting('options_of_theme','googlemaps_key');
  register_setting('options_of_theme','googlemaps_location');
  register_setting('options_of_theme','googlemaps');
  register_setting('options_of_theme','analitics');
  register_setting('options_of_theme','contact_1_firstname_lastname');
  register_setting('options_of_theme','contact_1_phone');
  register_setting('options_of_theme','contact_1_email');
  register_setting('options_of_theme','contact_2_firstname_lastname');
  register_setting('options_of_theme','contact_2_phone');
  register_setting('options_of_theme','contact_2_email');
}

function theme_options(){
  ?>
<div class="wrap">
  <?php screen_icon('themes'); ?>
  <h1>Ustawienia dodatkowe</h1>

  <form method="post" action="options.php">

    <?php settings_fields('options_of_theme'); ?>
    <table class="form-table">
      <tr>
        <th scope="row"><label for="phone">Numer kontaktowy</label></th>
        <td>
          <input type="tel" id="phone" name="phone" value="<?php echo get_option('phone'); ?>" />
          <p class="description">Wprowadź kontaktowy numer telefonu</p>
        </td>
      </tr>
      <tr>
        <th scope="row"><label for="email">E-mail</label></th>
        <td>
          <input type="email" id="email" name="email" value="<?php echo get_option('email'); ?>" />
          <p class="description">Wprowadź adres e-mail</p>
        </td>
      </tr>
      <tr>
        <th scope="row"><label for="owner">Właściciel</label></th>
        <td>
          <input type="text" class="regular-text" id="owner" name="owner" value="<?php echo get_option('owner'); ?>" />
        </td>
      </tr>
      <tr>
        <th scope="row"><label for="company_name">Nazwa firmy</label></th>
        <td>
          <input type="text" class="regular-text" id="company_name" name="company_name"
            value="<?php echo get_option('company_name'); ?>" />
          <p class="description">Wprowadź adres firmy</p>
        </td>
      </tr>
      <tr>
        <th scope="row"><label for="company_address">Adres firmy</label></th>
        <td>
          <input type="text" class="regular-text" id="company_address" name="company_address"
            value="<?php echo get_option('company_address'); ?>" />
          <p class="description">Wprowadź adres firmy</p>
        </td>
      </tr>
      <tr>
        <th scope="row"><label for="nip">NIP</label></th>
        <td>
          <input type="text" class="regular-text" id="nip" name="nip" value="<?php echo get_option('nip'); ?>" />
        </td>
      </tr>
      <tr>
        <th scope="row"><label for="other_www">Strona WWW</label></th>
        <td>
          <input type="url" class="regular-text" id="other_www" name="other_www"
            value="<?php echo get_option('other_www'); ?>" />
        </td>
      </tr>
      <tr>
        <th scope="row"><label for="yt_link">Link YouTube</label></th>

        <td>
          <input type="url" class="large-text" id="yt_link" name="yt_link"
            value="<?php echo get_option('yt_link'); ?>" />
          <p class="description">Link prowadzący do kanału na YouTubie</p>
        </td>
      </tr>
      <tr>
        <th scope="row"><label for="gplus_link">Link Google+</label></th>
        <td>
          <input type="url" class="large-text" id="gplus_link" name="gplus_link"
            value="<?php echo get_option('gplus_link'); ?>" />
          <p class="description">Link do prowadzący do konta na Google+</p>
        </td>
      </tr>
      <tr>
        <th scope="row"><label for="googlemaps_key">Klucz Google Maps</label></th>
        <td>
          <input type="text" class="regular-text" id="googlemaps_key" name="googlemaps_key"
            value="<?php echo get_option('googlemaps_key'); ?>" />
          <p class="description">Klucz dostępu do map google</p>
        </td>
      </tr>
      <tr>
        <th scope="row"><label for="googlemaps_location">Lokalizacja Google Maps</label></th>
        <td>
          <input type="text" class="regular-text" id="googlemaps_location" name="googlemaps_location"
            value="<?php echo get_option('googlemaps_location'); ?>" />
          <p class="description">Podaj szerokość i wysokość geograficzna oddzieloną przecinkami na Google Maps</p>
        </td>
      </tr>
      <tr>
        <th scope="row"><label for="googlemaps">Link Google Maps</label></th>
        <td>
          <input type="url" class="large-text" id="googlemaps" name="googlemaps"
            value="<?php echo get_option('googlemaps'); ?>" />
          <p class="description">Link do lokalizacji na Google Maps</p>
        </td>
      </tr>
      <tr>
        <th scope="row"><label for="analitics">ID Analityka Google</label></th>
        <td>
          <input type="text" class="regular-text" id="analitics" name="analitics"
            value="<?php echo get_option('analitics'); ?>" />
          <p class="description">ID użytkownika Google Analitics</p>
        </td>
      </tr>
    </table>
    <h1>Dane dodatkowe</h1>
    <table class="form-table">
      <tr>
        <th scope="row"><label for="contact_1_firstname_lastname">Imię i nazwisko</label></th>
        <td>
          <input type="text" class="regular-text" id="contact_1_firstname_lastname" name="contact_1_firstname_lastname"
            value="<?php echo get_option('contact_1_firstname_lastname'); ?>" />
          <p class="description">Wprowadź imię i nazwisko osoby kontaktowej</p>
        </td>
      </tr>
      <tr>
        <th scope="row"><label for="contact_1_phone">Numer telefonu</label></th>
        <td>
          <input type="tel" id="contact_1_phone" name="contact_1_phone"
            value="<?php echo get_option('contact_1_phone'); ?>" />
          <p class="description">Wprowadź numer telefonu osoby kontaktowej</p>
        </td>
      </tr>
      <tr>
        <th scope="row"><label for="contact_1_email">E-mail</label></th>
        <td>
          <input type="email" class="regular-text" id="contact_1_email" name="contact_1_email"
            value="<?php echo get_option('contact_1_email'); ?>" />
          <p class="description">Wprowadź adres e-mail osoby kontaktowej</p>
        </td>
      </tr>
      <tr>
        <th scope="row"><label for="contact_2_firstname_lastname">Imię i nazwisko</label></th>
        <td>
          <input type="text" class="regular-text" id="contact_2_firstname_lastname" name="contact_2_firstname_lastname"
            value="<?php echo get_option('contact_2_firstname_lastname'); ?>" />
          <p class="description">Wprowadź imię i nazwisko osoby kontaktowej</p>
        </td>
      </tr>
      <tr>
        <th scope="row"><label for="contact_2_phone">Numer telefonu</label></th>
        <td>
          <input type="tel" id="contact_2_phone" name="contact_2_phone"
            value="<?php echo get_option('contact_2_phone'); ?>" />
          <p class="description">Wprowadź numer telefonu osoby kontaktowej</p>
        </td>
      </tr>
      <tr>
        <th scope="row"><label for="contact_2_email">E-mail</label></th>
        <td>
          <input type="email" class="regular-text" id="contact_2_email" name="contact_2_email"
            value="<?php echo get_option('contact_2_email'); ?>" />
          <p class="description">Wprowadź adres e-mail osoby kontaktowej</p>
        </td>
      </tr>
    </table>
    <p class="submit">
      <input type="submit" class="button-primary" value="<?php _e('Save Changes'); ?>" />
    </p>
  </form>

</div>


<?php }

function wtm_add_analitics_script(){
  $analitics = get_option('analitics');
  if(!$analitics)
  return;

  ?>
<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $analitics; ?>"></script>
<script>
  window.dataLayer = window.dataLayer || [];

  function gtag() {
    dataLayer.push(arguments);
  }
  gtag('js', new Date());
  gtag('config', '<?php echo $analitics;?>');
</script>
<?php
 
}
add_action('wp_footer','wtm_add_analitics_script',99);