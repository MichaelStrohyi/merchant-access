<?php

/**
 * Return true if given $email has a valid form.
 *
 * @param  string  $email
 * @return boolean
 */
function isEmailValid($email)
{
    // First, we check that there's one @ symbol,
  // and that the lengths are right.
  if (!preg_match("/^[^@]{1,64}@[^@]{1,255}$/", $email)) {
      // Email invalid because wrong number of characters
    // in one section or wrong number of @ symbols.
    return false;
  }

  // Split it into sections to make life easier
  $email_array = explode("@", $email);
    $local_array = explode(".", $email_array[0]);
    for ($i = 0; $i < sizeof($local_array); $i++) {
        if (!preg_match("/^(([A-Za-z0-9!#$%&'*+\/=?^_`{|}~-][A-Za-z0-9!#$%&'*+\/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$/",
            $local_array[$i])) {
            return false;
        }
    }
  // Check if domain is IP. If not,
  // it should be valid domain name
  if (!preg_match("/^\[?[0-9\.]+\]?$/", $email_array[1])) {
      $domain_array = explode(".", $email_array[1]);
      if (sizeof($domain_array) < 2) {
          return false; // Not enough parts to domain
      }
      for ($i = 0; $i < sizeof($domain_array); $i++) {
          if (!preg_match("/^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$/",
              $domain_array[$i])) {
              return false;
          }
      }
  }
    return true;
}

/**
 * Return global $twig
 *
 * @return Twig_Environment
 * @author Michael Strohyi
 **/
function getTwig()
{
    global $twig;
    return $twig;
}

/**
 * Get customer's id' from session and return object Customer with data for this customer's id
 *
 * @return App\Customer
 * @author Michael Strohyi
 **/
function getLoggedCustomer()
{
    $customer_id = empty($_SESSION['customer_id']) ? null : $_SESSION['customer_id'];
    return new App\Customer($customer_id);
}

/**
 * Set session for logged customer
 *
 * @param App\Customer $customer
 * @return void
 * @author Michael Strohyi
 **/
function setLoggedCustomer($customer)
{
    $_SESSION['customer_id'] = $customer->getId();
}

/**
 * Destroy current session
 *
 * @return void
 * @author Michael Strohyi
 **/
function clearLoggedCustomer()
{
    session_unset();
}

/**
 * Return true if given $url has a valid form.
 *
 * @param  string $url
 * @return boolean
 * @author Michael Strohyi
 **/
function isUrlValid($url)
{
    if (filter_var($url, FILTER_VALIDATE_URL) !== false && strpos($url, 'http://') === 0 || strpos($url, 'https://') === 0) {
      return true;
    }

    return false;
}

/**
 * Render html-template with error-string according to $error.
 *
 * @param App\Store $store
 * @param string $error
 * @return void
 * @author Michael Strohyi
 **/
function showErrorPage($store, $error)
{
    $twig = getTwig();

    switch ($error) {
      case 'access_denied':
        echo $twig->render('Stores/store-access-denied.html.twig', [
            'message' => 'You have no permission to work with this store',
            'url' => getPath('stores'),
            ]);
        break;

      case 'store_removed':
        echo $twig->render('Stores/store-access-denied.html.twig', [
            'message' => 'This store is waiting for removing validation. Please click validation link from email or <a href="' . getPath('resend_rm_verification', ['store' => $store]) . '">click here</a> to request new validation email',
            'url' => getPath('stores'),
            ]);
        break;

      case 'store_not_active':
        echo $twig->render('Stores/store-access-denied.html.twig', [
            'message' => 'You have not validated this store ownership yet. Please, validate it or <a href="' . getPath('resend_verification', ['store' => $store]) . '">click here</a> to request new validation email',
            'url' => getPath('stores'),
            ]);
        break;
    }
}

/**
 * Return true if given $date has a valid form.
 *
 * @param  string $date
 * @return boolean
 * @author Michael Strohyi
 **/
function isDateValid($date)
{
    return (bool) preg_match( '~^(\d{2})\-(\d{2})-(\d{4})$~', $date, $matches) && checkdate($matches[1], $matches[2], $matches[3]);
}
