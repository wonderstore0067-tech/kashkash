<?php
defined('BASEPATH') or exit('No direct script access allowed');

$config['stripe'] = array(
    //for testing
    // 'secret_key'      => 'sk_test_51PAsjuP5EzaI34jDLgOLkcWkEwdkFtLdsLIKXwryDwgL20OZ5aNYOHFhhimsHveNRjd03UFATK9Ks7auhNQboC3D00a093YFdT',
    // 'publishable_key' => 'pk_test_51PAsjuP5EzaI34jDDlQ0GRaWaFIAkQ2IHtluVWbvjWhQlwZ8Pz6ByoLFOlAe9URLdWjLgbQLb9BYWI0IjyCVfP1p00vuvoqbey',
    // 'endpoint_secret' => 'whsec_MMwcQMurKNMT3OaidJKn06QiFD1H9ucu'

    //for live
    'secret_key'      => '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',
    'publishable_key' => 'pk_live_51PAsjuP5EzaI34jDGcqIi8fpp5TdaBY1edbGCC9DvCgPzfhqVVJbYIbEDmtGQ1GyWzNnj2x1mM9Sislfhtw1hpT000r2DZojN0',
    'endpoint_secret' => 'whsec_MMwcQMurKNMT3OaidJKn06QiFD1H9ucu'

);

?>