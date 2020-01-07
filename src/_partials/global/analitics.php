<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! isset($analitics_id) || empty($analitics_id) ) return;

?>

<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $analitics_id; ?>"></script>
<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }
    gtag('js', new Date());
    gtag('config', '<?php echo $analitics_id; ?>');
</script>