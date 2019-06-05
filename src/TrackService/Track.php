<?php

namespace FedEx\TrackService;

use FedEx\FedEx;

class Track extends FedEx
{

  private $request = array();

  /**
   *  Constructor requires the key, password,
   *  account number and password for the FedEx
   *  account to use the API. You may also override
   *  the default WSDL file.
   *
   *  @param string   // FedEx Key
   *  @param string   // FedEx account password
   *  @param string   // Account number
   *  @param string   // Meter number
   *  @param string   // Full path to WSDL file
   *  @param int      // WSDL Version number
   */
  public function __construct(
    $key,
    $passwd,
    $acct,
    $meter,
    $wsdlFile,
    $version
  ) {
    parent::__construct($wsdlFile, $key, $passwd, $acct, $meter);

    // TODO: Set this in env()?
    $this->endPoint = 'https://wsbeta.fedex.com:443/web-services';

    $this->setCustomerTransactionId('Track Request via PHP');
    $this->setVersion('trck', $version, 0, 0);
  }

  /**
   *  Gets the tracking detials for the
   *  given tracking number and returns
   *  the FedEx request as an object.
   *
   *  @param string   // Tracking #
   *  @return SoapClient Object
   */
  public function getByTrackingId($id)
  {
    // Request syntax needed to track by tracking id
    $this->request['SelectionDetails'] = array(
      'PackageIdentifier' => array(
        'Type' => 'TRACKING_NUMBER_OR_DOORTAG',
        'Value' => $id // Tracking ID to track
      )
    );

    $req = $this->buildRequest($this->request);

    return $this->getSoapClient()->track($req);;
  }
}
